<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use Serialization\InfixUpgrade;
use Utils\Common;
use JsonMapper;
use RedBO\RedFactory;

class items extends BaseController
{
    public function SyncItemsAction() {
        $mapper = new JsonMapper();

        $itemIds = (file_get_contents(Common::GUILDWAR2_BASE_URL.Common::GUILDWAR2_ITEM));
        $itemIds = substr($itemIds,1);
        $itemIds = substr($itemIds,0,-1);

        $itemIdArr = explode(",",$itemIds);

        //items to sync array
        $syncItemArr = array();
        $x = 0;

        //Add all gw item ids first
        foreach($itemIdArr as $value) {
            $item = RedFactory::GetRedGuildItem()->SelectitembyGwId($value);
            //Add item id if not found in database
            if (empty($item)) {
                RedFactory::GetRedGuildItem()->AddItemGWIds($value);
                //put in Update item array
                $syncItemArr[$x] = $value;
                $x++;
            } else {
                //If found, check last time item was synced
                $sync_date = $item->synced_time;
                if (empty($sync_date)) {
                    //put in Update item array
                    $syncItemArr[$x] = $value;
                    $x++;
                } else {
                    //Check sync date
                    //Number of days before syncing items (run each week)
                    $date = strtotime($sync_date);
                    $date_to_sync = strtotime("+2 day", $date);

                    $current_time = time();
                    if ($date_to_sync < $current_time) {
                        //put in Update item array
                        $syncItemArr[$x] = $value;
                        $x++;
                    }
                }
            }

        }

        $i = 0;
        $url_item_fetch = Common::GUILDWAR2_BASE_URL.Common::GUILDWAR2_ITEM."?ids=";
        $concat_ids = "";
        foreach($syncItemArr as $value) {
            $concat_ids .= $value;

            if ($i != 199) {
                $concat_ids .= ",";
                $i++;
            } else {
                $jsonArr = json_decode(file_get_contents($url_item_fetch . $concat_ids));

                foreach ($jsonArr as $json) {
                    $item = $mapper->map($json, new \Serialization\Item());
                    $redItem = RedFactory::GetRedGuildItem()->SelectitembyGwId($item->id);

                    $this->UpdateItem($item,$redItem->id);
                }

                $concat_ids = "";
                $i = 0;
            }

        }

        //Process last batch
        if ($i > 0) {
            if (substr($concat_ids,-1) == ",")
                $concat_ids = substr($concat_ids, 0, -1);

            $jsonArr = json_decode(file_get_contents($url_item_fetch . $concat_ids));

            foreach ($jsonArr as $json) {
                $item = $mapper->map($json, new \Serialization\Item());
                $redItem = RedFactory::GetRedGuildItem()->SelectitembyGwId($item->id);

                $this->UpdateItem($item,$redItem->id);
            }
        }

        echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,"All items have been synced"));

    }

    public function SearchItemAction() {

        $name = $this->app->request()->get('name');
        $type = $this->app->request()->get('type');
        $subtype = $this->app->request()->get('subtype');
        $buyPriceMin = $this->app->request()->get('buyPriceMin');
        $buyPriceMax = $this->app->request()->get('buyPriceMax');
        $sellPriceMin = $this->app->request()->get('sellPriceMin');
        $sellPriceMax = $this->app->request()->get('sellPriceMax');
        $rarity = $this->app->request()->get('rarity');
        $levelmin = $this->app->request()->get('levelmin');
        $levelmax = $this->app->request()->get('levelmax');
        $name = str_replace('+',' ',$name);

        $order_by = $this->app->request()->get('orderby');
        $order_desc_or_asc = $this->app->request()->get('ascOrdesc');
        if (empty($order_by))
            $order_by = 1;

        $batch_size = $this->app->request()->params('batch_size');
        $batch_num = $this->app->request()->params('batch_num');
        $light = $this->app->request()->params('light');
        $islight = empty($light) ? false : true;
        $includePrice = $this->app->request()->params('includePrice');
        $includePrice = empty($includePrice) ? false : true;

        if (empty($batch_size) || empty($batch_num)) {
            $batch_num = 1;
            $batch_size = 100;
        }

        //$start = microtime(true);
        $totalBatches = RedFactory::GetRedGuildItem()->GetSearchTotalBatch($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$batch_size,$order_by);

        $items = RedFactory::GetRedGuildItem()->SearchItem($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$batch_num,$batch_size,$order_by,$order_desc_or_asc);

        //$finish = microtime(true) - $start;
        //echo "Time to fetch items: ".$finish."<br>";
        $this->ReturnItemsDetails($items,$totalBatches,$batch_num,$islight,$includePrice);

    }

    public function GetItemByIDListAction($ids)
    {
        $idsArr  = explode(",", $ids);

        $batch_size = $this->app->request()->post('batch_size');
        $batch_num = $this->app->request()->post('batch_num');
        $light = $this->app->request()->post('light');
        $islight = empty($light) ? false : true;
        $includePrice = $this->app->request()->post('includePrice');
        $includePrice = empty($includePrice) ? false : true;

        if (empty($batch_size) || empty($batch_num)) {
            $batch_num = 1;
            $batch_size = 100;
        }
        $totalBatches = RedFactory::GetRedGuildItem()->FindItemByGWIdsBatchTotal($idsArr,$batch_size);

        $items = RedFactory::GetRedGuildItem()->FindItemByGWIds($idsArr,$batch_num,$batch_size);
        $this->ReturnItemsDetails($items,$totalBatches,$batch_num,$islight,$includePrice);
    }

    public function GetItemsByBatchAction()
    {
        $batch_size = $this->app->request()->post('batch_size');
        $batch_num = $this->app->request()->post('batch_num');
        $light = $this->app->request()->post('light');
        $islight = empty($light) ? false : true;
        $includePrice = $this->app->request()->post('includePrice');
        $includePrice = empty($includePrice) ? false : true;

        if (empty($batch_size) || empty($batch_num)) {
            $batch_num = 1;
            $batch_size = 100;
        }
        $totalBatches = RedFactory::GetRedGuildItem()->GetTotalBatches($batch_size);

        $items = RedFactory::GetRedGuildItem()->SelectItemByBatch($batch_num,$batch_size);

        $this->ReturnItemsDetails($items,$totalBatches,$batch_num,$islight,$includePrice);
    }


    private function ReturnItemsDetails($items,$batch_total,$batch_current,$isLight,$includePrice) {

        if (empty($items)) {
            echo json_encode(Common::GenerateResponse(Common::STATUS_NOTFOUND,"No items found"));
        } else {
            if (is_array($items)) {
                //$start = microtime(true);
                $items = $this->PutBatchitemDetails($items,$isLight,$includePrice);
                $reponse = Common::GenerateResponse(Common::STATUS_SUCCESS, Common::ConvertBeanToArray($items, "items"));
                $reponse["BatchTotalNum"] = $batch_total;
                $reponse["CurrentBatch"] = $batch_current;
                //$finish = microtime(true) - $start;
                //echo "Time to add details: $finish <br>";
                echo json_encode($reponse);
            } else {
                if($isLight)
                    $item = $this->PutItemLightDetails($items,$includePrice);
                else
                    $item = $this->PutItemDetails($items);

                echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,$item->export()));
            }
        }
    }

    private function PutBatchitemDetails($items,$islight,$includePrice)
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
            $details = RedFactory::GetRedItemDetails()->FindByItemIds($itemIds);

            if (!empty($details)) {
                $x = 0;
                foreach($details as $detail) {
                    $detailIds[$x] = $detail->id;
                    $x++;
                }

                $infusionSlots = RedFactory::GetRedInfusionSlot()->FindByItemDetailsIds($detailIds);
                $infixUpgrades = RedFactory::GetRedInfixUpgrade()->FindByItemDetailsIds($detailIds);

                $x = 0;
                if (!empty($infixUpgrades)) {
                    foreach ($infixUpgrades as $infixUpgrade) {
                        $infixUpgradeIds[$x] = $infixUpgrade->id;
                        $x++;
                    }

                    $infixBuffs = RedFactory::GetRedInfixBuff()->FindByInfixUpgradeIds($infixUpgradeIds);
                    $infixAttrs = RedFactory::GetRedInfixAttributes()->FindByInfixUpgradeIds($infixUpgradeIds);
                }
            }
        }

        if ($includePrice) {
            $prices = RedFactory::GetRedGuildPrices()->FindByItemIds($itemGwIds);
        }

        foreach($items as $item) {

            $item->flags = unserialize($item->flags);
            $item->game_types = unserialize($item->game_types);
            $item->restrictions = unserialize($item->restrictions);

            if (!$islight && !empty($details)) {
                //Get Item Details from batch retrieved
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

                        //Add infix upgrades
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

            if (!empty($prices) && $includePrice) {
                //Get Item Prices from batch retrieved
                foreach ($prices as $price) {
                    if ($price->gw_item_id == $item->gwItemId) {

                        $item->price = $price;

                        break;
                    }
                }
            }

        }

        return $items;
    }


    private function PutItemDetails($item)
    {
        $item->flags = unserialize($item->flags);
        $item->game_types = unserialize($item->game_types);
        $item->restrictions = unserialize($item->restrictions);

        $detail = RedFactory::GetRedItemDetails()->FindByItemId($item->id);

        if (!empty($detail)) {
            $item->details = $detail;
            $item->details->flags = unserialize($item->details->flags);
            $item->details->infusion_upgrade_flags = unserialize($item->details->infusion_upgrade_flags);
            $item->details->bonuses = unserialize($item->details->bonuses);

            $infusionSlots = RedFactory::GetRedInfusionSlot()->FindByItemDetailsId($detail->id);
            if (!empty($infusionSlots)) {
                $item->details->infusion_slots = Common::ConvertBeanToArray($infusionSlots, "infusion_slots");
                foreach ($item->details->infusion_slots as $infusion) {
                    $infusion->flags = unserialize($infusion->flags);
                }
            }

            $infixUpgrade = RedFactory::GetRedInfixUpgrade()->FindByItemDetailsId($detail->id);
            if (!empty($infixUpgrade)) {
                $attributes = RedFactory::GetRedInfixAttributes()->FindByInfixUpgradeId($infixUpgrade->id);
                if (!empty($attributes)) {
                    $item->details->infix_upgrade = new InfixUpgrade();
                    $item->details->infix_upgrade->attributes = Common::ConvertBeanToArray($attributes, "attributes");
                }
            }

            if (!empty($infixUpgrade)) {
                $buff = RedFactory::GetRedInfixBuff()->FindByInfixUpgradeId($infixUpgrade->id);
                if (!empty($buff))
                    $item->details->infix_upgrade->buff = $buff;
            }

            $price = RedFactory::GetRedGuildPrices()->FindByItemId($item->gwItemId);

            $item->price = $price;

        }


        return $item;
    }

    private function PutItemLightDetails($item,$includePrice)
    {
        $item->flags = unserialize($item->flags);
        $item->game_types = unserialize($item->game_types);
        $item->restrictions = unserialize($item->restrictions);

        if ($includePrice) {
            $price = RedFactory::GetRedGuildPrices()->FindByItemId($item->gwItemId);

            $item->price = $price;
        }

        return $item;
    }


    private function UpdateItem($item,$id) {
        if ($item != null) {

            $item_id = RedFactory::GetRedGuildItem()->UpdateItem($id,$item->id,$item->name,$item->icon,$item->description,$item->type,$item->rarity,$item->level,$item->vendor_value,$item->default_skin,
                serialize($item->flags),serialize($item->game_types),serialize($item->restrictions));

            $reddetail = RedFactory::GetRedItemDetails()->FindByItemId($id);
            if ($item->details != null ) {
                $details = $item->details;

                $detailsId = 0;
                if(empty($reddetail)) {
                    //if not found, add new item details
                    $detailsId = RedFactory::GetRedItemDetails()->AddItemDetails($item_id, $details->type, $details->weight_class, $details->defense, $details->suffix_item_id, $details->secondary_suffix_item_id, $details->size, $details->no_sell_or_sort,
                        $details->description, $details->duration_ms, $details->unlock_type, $details->color_id, $details->recipe_id, $details->charges,
                        serialize($details->flags), serialize($details->infusion_upgrade_flags),
                        $details->suffix, serialize($details->bonuses), $details->damage_type, $details->min_power, $details->max_power);
                } else {
                    //if found just update it
                    $detailsId = RedFactory::GetRedItemDetails()->UpdateItemDetails($reddetail->id, $item_id, $details->type, $details->weight_class, $details->defense, $details->suffix_item_id, $details->secondary_suffix_item_id, $details->size, $details->no_sell_or_sort,
                        $details->description, $details->duration_ms, $details->unlock_type, $details->color_id, $details->recipe_id, $details->charges,
                        serialize($details->flags), serialize($details->infusion_upgrade_flags),
                        $details->suffix, serialize($details->bonuses), $details->damage_type, $details->min_power, $details->max_power);
                }

                //Delete all current infusion slots
                RedFactory::GetRedInfusionSlot()->DeleteItemDetailsInfixUpgrade($detailsId);
                //Add All infusion slots again
                if ($details->infusion_slots != null) {
                    foreach($details->infusion_slots as $infusionSlots){
                        RedFactory::GetRedInfusionSlot()->AddItemDetailsInfusionSlot($detailsId,serialize($infusionSlots->flags),$infusionSlots->item_id);
                    }
                }

                //Delete all infuxUpgrade first
                $infuxUpgrade = RedFactory::GetRedInfixUpgrade()->FindByItemDetailsId($detailsId);
                if (!empty($infuxUpgrade)) {
                    RedFactory::GetRedInfixAttributes()->DeleteInfixAttributeByInfuxId($infuxUpgrade->id);
                    RedFactory::GetRedInfixBuff()->DeleteInfixBuffByInfuxId($infuxUpgrade->id);
                    RedFactory::GetRedInfixUpgrade()->DeleteItemDetailsInfixUpgrade($detailsId);
                }

                //Add all infix upgrade again
                if ($details->infix_upgrade != null) {
                    $infixUpgradeId = RedFactory::GetRedInfixUpgrade()->AddItemDetailsInfixUpgrade($detailsId);

                    if ($details->infix_upgrade->attributes != null) {
                        foreach($details->infix_upgrade->attributes as $attributes) {
                            RedFactory::GetRedInfixAttributes()->AddInfixAttribute($infixUpgradeId, $attributes->attribute, $attributes->modifier);
                        }
                    }

                    if ($details->infix_upgrade->buff != null) {
                        $buff = $details->infix_upgrade->buff;
                        RedFactory::GetRedInfixBuff()->AddInfixBuff($infixUpgradeId,$buff->skill_id,$buff->description);
                    }
                }

            }

        }
    }


}