<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use JsonMapper;
use KaiApp\JsonTransformers\BatchTransformer;
use KaiApp\JsonTransformers\ItemTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedInfixAttributes;
use KaiApp\RedBO\RedInfixBuff;
use KaiApp\RedBO\RedInfusionSlot;
use KaiApp\RedBO\RedItem;
use KaiApp\RedBO\RedItemDetails;
use KaiApp\RedBO\RedItemDetailsInfixUpgrade;
use KaiApp\RedBO\RedPrices;
use KaiApp\Serialization\Items\InfixUpgrade;
use KaiApp\Serialization\Items\Item;
use KaiApp\Utils\BeanUtils;
use KaiApp\Utils\GuildWars2Util;
use KaiApp\Utils\GuildWars2Utils;
use Slim\Http\Request;
use Slim\Http\Response;

class ItemsController extends BaseController
{
    private $redItem;
    private $redItemDetails;
    private $redItemDetailsInfixUpgrade;
    private $redinfusionSlot;
    private $redInfixBuff;
    private $redInfixAttribute;
    private $redPrices;

    public function __construct(RedItem $_redItem,
                                RedItemDetails $_redItemDetails,
                                RedItemDetailsInfixUpgrade $_redItemDetailsInfixUpgrade,
                                RedInfusionSlot $_redinfusionSlot,
                                RedInfixBuff $_redInfixBuff,
                                RedInfixAttributes $_redInfixAttribute,
                                RedPrices $_redPrices) {
        $this->redItem = $_redItem;
        $this->redItemDetails = $_redItemDetails;
        $this->redItemDetailsInfixUpgrade = $_redItemDetailsInfixUpgrade;
        $this->redinfusionSlot = $_redinfusionSlot;
        $this->redInfixBuff = $_redInfixBuff;
        $this->redInfixAttribute = $_redInfixAttribute;
        $this->redPrices = $_redPrices;
        parent::__construct();
    }

    public function sync(Request $request,Response $response, array $args) {
        GuildWars2Utils::syncWithGuildWars2(GuildWars2Utils::getItemsUrl(),$this->redItem,new Item(),array($this,"update"));
        return $this->response(new \League\Fractal\Resource\Item("All items have been synced",new SimpleTransformer()),$response);
    }

    public function search(Request $request,Response $response, array $args) {

        $name = $request->getParam('name');
        $type = $request->getParam('type');
        $subtype = $request->getParam('subtype');
        $buyPriceMin = $request->getParam('buyPriceMin');
        $buyPriceMax = $request->getParam('buyPriceMax');
        $sellPriceMin = $request->getParam('sellPriceMin');
        $sellPriceMax = $request->getParam('sellPriceMax');
        $rarity = $request->getParam('rarity');
        $levelmin = $request->getParam('levelmin');
        $levelmax = $request->getParam('levelmax');
        $name = str_replace('+',' ',$name);

        $order_by = empty($request->getParam('orderby')) ? 1 : $request->getParam('orderby');
        $order_desc_or_asc = $request->getParam('orderDesc');

        $batch_size = empty($request->getParam('batch_size')) ? 100 : $request->getParam('batch_size');
        $page = empty($request->getParam('page')) ? 1 : $request->getParam('page');
        $islight = $request->getParam('islight') == "1";
        $includePrice = $request->getParam('includePrice') == "1";

        $totalBatches = $this->redItem->getSearchTotalBatch($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$batch_size,$order_by);
        $items = $this->redItem->search($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$page,$batch_size,$order_by,$order_desc_or_asc);

        return $this->returnItemsDetails($response,$items,$totalBatches,$page,$islight,$includePrice);
    }

    public function getByIds(Request $request,Response $response, array $args)
    {
        $idsArr  = explode(",", $args['ids']);
        $batch_size = empty($request->getParam('batch_size')) ? 100 : $request->getParam('batch_size');
        $page = empty($request->getParam('page')) ? 1 : $request->getParam('page');
        $islight =  $request->getParam('islight') == "1";
        $includePrice = $request->getParam('includePrice') == "1";

        $totalBatches = $this->redItem->getByGwIdsBatchTotal($idsArr,$batch_size);
        $items = $this->redItem->getByGwIds($idsArr,$page,$batch_size);
        return $this->returnItemsDetails($response,$items,$totalBatches,$page,$islight,$includePrice);
    }

    private function returnItemsDetails(Response $response,$items,$batch_total,$page,$islight,$includePrice) {
        if (empty($items))
            return $this->response(new \League\Fractal\Resource\Item("No items found",new SimpleTransformer()),$response,404);
        else {
            if (is_array($items)) {
                $items = $this->putBatchitemDetails($items,$islight,$includePrice);
                return $this->response(new \League\Fractal\Resource\Item($items, new BatchTransformer(new ItemTransformer(),$page,$batch_total)),$response);
            } else {
                $item = ($islight)? $this->putItemLightDetails($items,$includePrice) :  $this->putItemDetails($items);
                return $this->response(new \League\Fractal\Resource\Item($item, new BatchTransformer(new ItemTransformer(),$page,$batch_total)),$response);
            }
        }
    }

    private function putBatchitemDetails($items,$islight,$includePrice)
    {
        $x = 0;
        $itemGwIds = array();
        $itemIds = array();
        $priceIds = array();
        $detailIds = array();
        $infixUpgradeIds = array();
        $infusionSlots = null;
        $infixUpgrades = null;
        $infixBuffs = null;
        $infixAttrs  = null;
        $buyPrices = null;
        $sellPrices = null;
        $details = null;

        foreach ($items as $item) {
            $itemGwIds[$x] = $item->gwItemId;
            $itemIds[$x] = $item->id;
            $x++;
        }

        if (!$islight) {
            $details = $this->redItemDetails->getByItemIds($itemIds);

            if (!empty($details)) {
                $x = 0;
                foreach($details as $detail) {
                    $detailIds[$x] = $detail->id;
                    $x++;
                }

                $infusionSlots = $this->redinfusionSlot->getByItemDetailsIds($detailIds);
                $infixUpgrades = $this->redItemDetailsInfixUpgrade->getByItemDetailsIds($detailIds);

                $x = 0;
                if (!empty($infixUpgrades)) {
                    foreach ($infixUpgrades as $infixUpgrade) {
                        $infixUpgradeIds[$x] = $infixUpgrade->id;
                        $x++;
                    }

                    $infixBuffs = $this->redInfixBuff->getByInfixIds($infixUpgradeIds);
                    $infixAttrs = $this->redInfixAttribute->getByInfixIds($infixUpgradeIds);
                }
            }
        }

        if ($includePrice)
            $prices = $this->redPrices->getByItemIds($itemGwIds);


        foreach($items as $item) {
            $item->flags = unserialize($item->flags);
            $item->game_types = unserialize($item->game_types);
            $item->restrictions = unserialize($item->restrictions);

            if (!$islight && !empty($details)) {
                foreach ($details as $detail) {

                    if ($detail->itemId == $item->id) {
                        $item->details = $detail;
                        $item->details->flags = unserialize($item->details->flags);
                        $item->details->infusion_upgrade_flags = unserialize($item->details->infusion_upgrade_flags);
                        $item->details->bonuses = unserialize($item->details->bonuses);

                        //Adding Infusion Slots
                        if (!empty($infusionSlots)) {

                            $x = 0;
                            $localInfusionArr = array();
                            foreach($infusionSlots as $infusionSlot) {
                                if ($infusionSlot->itemdetailsId == $detail->id) {
                                    $infusion = $infusionSlot->export();
                                    $infusion["flags"] = unserialize($infusion["flags"]);
                                    $localInfusionArr["infusion_slots"][$x] = $infusion;
                                    $x++;
                                }
                            }

                            $item->details->infusion_slots = $localInfusionArr ;
                        }

                        if (!empty($infixUpgrades)) {
                            foreach($infixUpgrades as $infixUpgrade) {
                                if ($infixUpgrade->itemdetailsId == $detail->id) {
                                    $item->details->infix_upgrade = new InfixUpgrade();
                                    //Get single infix buff
                                    if (!empty($infixBuffs)) {
                                        foreach ($infixBuffs as $infixBuff) {
                                            if ($infixBuff->itemdetailsinfixupgradeId == $infixUpgrade->id) {
                                                $item->details->infix_upgrade->buff = $infixBuff;
                                            }
                                            break;
                                        }
                                    }

                                    //Get multiple infix attributes
                                    if (!empty($infixAttrs)) {
                                        $x = 0;
                                        $localAttrArr = array();
                                        foreach ($infixAttrs as $infixAttr) {
                                            if ($infixAttr->itemdetailsinfixupgradeId == $infixUpgrade->id) {
                                                $localAttrArr["attributes"][$x] = $infixAttr->export();
                                                $x++;
                                            }
                                        }

                                        if ($x > 0) {
                                            $item->details->infix_upgrade->attributes = $localAttrArr;
                                        }

                                    }
                                    //Only one infixUpgrade per item
                                    break;
                                }

                            }

                        }

                    }

                }
            }

            if (!empty($prices)) {
                //Get Item Prices from batch retrieved
                foreach ($prices as $price) {
                    if ($price->gw_prices_id == $item->gwItemId) {
                        $item->price = $price;
                        break;
                    }
                }
            }

        }
        return $items;
    }

    private function putItemDetails($item)
    {
        $item->flags = unserialize($item->flags);
        $item->game_types = unserialize($item->game_types);
        $item->restrictions = unserialize($item->restrictions);

        $detail = $this->redItemDetails->getByItemId($item->id);

        if (!empty($detail)) {
            $item->details = $detail;
            $item->details->flags = unserialize($item->details->flags);
            $item->details->infusion_upgrade_flags = unserialize($item->details->infusion_upgrade_flags);
            $item->details->bonuses = unserialize($item->details->bonuses);

            $infusionSlots = $this->redinfusionSlot->getByItemDetailsId($detail->id);
            if (!empty($infusionSlots)) {
                $item->details->infusion_slots = $infusionSlots;
                foreach ($item->details->infusion_slots as $infusion) {
                    $infusion->flags = unserialize($infusion->flags);
                }
            }

            $infixUpgrade = $this->redItemDetailsInfixUpgrade->getByItemDetailsId($detail->id);
            if (!empty($infixUpgrade)) {
                $attributes = $this->redInfixAttribute->getByInfixId($infixUpgrade->id);
                if (!empty($attributes)) {
                    $item->details->infix_upgrade = new InfixUpgrade();
                    $item->details->infix_upgrade->attributes = $attributes;
                }
            }

            if (!empty($infixUpgrade)) {
                $buff = $this->redInfixBuff->getByInfixId($infixUpgrade->id);
                if (!empty($buff))
                    $item->details->infix_upgrade->buff = $buff;
            }

            $price = $this->redPrices->getByItemId($item->gwItemId);
            $item->price = $price;
        }

        return $item;
    }

    private function putItemLightDetails($item,$includePrice)
    {
        $item->flags = unserialize($item->flags);
        $item->game_types = unserialize($item->game_types);
        $item->restrictions = unserialize($item->restrictions);

        if ($includePrice) {
            $price = $this->redPrices->getByItemId($item->gwItemId);
            $item->price = $price;
        }

        return $item;
    }

    public function update($item) {
        if ($item != null) {
            $id = $this->redItem->getByGwId($item->id)->id;
            $item_id = $this->redItem->update($id,$item->id,$item->name,$item->icon,$item->description,$item->type,$item->rarity,$item->level,$item->vendor_value,$item->default_skin,
                serialize($item->flags),serialize($item->game_types),serialize($item->restrictions));

            $reddetail = $this->redItemDetails->getByItemId($id);
            if ($item->details != null ) {
                $details = $item->details;

                $detailsId = 0;
                if(empty($reddetail))
                    $detailsId = $this->redItemDetails->add($item_id, $details->type, $details->weight_class, $details->defense, $details->suffix_item_id, $details->secondary_suffix_item_id, $details->size, $details->no_sell_or_sort,
                        $details->description, $details->duration_ms, $details->unlock_type, $details->color_id, $details->recipe_id, $details->charges,
                        serialize($details->flags), serialize($details->infusion_upgrade_flags),
                        $details->suffix, serialize($details->bonuses), $details->damage_type, $details->min_power, $details->max_power);
                else
                    $detailsId = $this->redItemDetails->update($reddetail->id, $item_id, $details->type, $details->weight_class, $details->defense, $details->suffix_item_id, $details->secondary_suffix_item_id, $details->size, $details->no_sell_or_sort,
                        $details->description, $details->duration_ms, $details->unlock_type, $details->color_id, $details->recipe_id, $details->charges,
                        serialize($details->flags), serialize($details->infusion_upgrade_flags),
                        $details->suffix, serialize($details->bonuses), $details->damage_type, $details->min_power, $details->max_power);

                //Delete all current infusion slots
                $this->redinfusionSlot->deleteByItemDetailsId($detailsId);
                //Add All infusion slots again
                if ($details->infusion_slots != null) {
                    foreach($details->infusion_slots as $infusionSlots)
                        $this->redinfusionSlot->add($detailsId,serialize($infusionSlots->flags),$infusionSlots->item_id);
                }

                //Delete all infuxUpgrade first
                $infuxUpgrade = $this->redItemDetailsInfixUpgrade->getByItemDetailsId($detailsId);
                if (!empty($infuxUpgrade)) {
                    $this->redInfixAttribute->deleteByInfuxId($infuxUpgrade->id);
                    $this->redInfixBuff->deleteByInfuxId($infuxUpgrade->id);
                    $this->redItemDetailsInfixUpgrade->deleteByItemDetailsId($detailsId);
                }

                //Add all infix upgrade again
                if ($details->infix_upgrade != null) {
                    $infixUpgradeId = $this->redItemDetailsInfixUpgrade->add($detailsId);

                    if ($details->infix_upgrade->attributes != null) {
                        foreach($details->infix_upgrade->attributes as $attributes)
                            $this->redInfixAttribute->add($infixUpgradeId, $attributes->attribute, $attributes->modifier);
                    }

                    if ($details->infix_upgrade->buff != null) {
                        $buff = $details->infix_upgrade->buff;
                        $this->redInfixBuff->add($infixUpgradeId,$buff->skill_id,$buff->description);
                    }
                }

            }

        }
    }


}