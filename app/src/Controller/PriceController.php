<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use Utils\Common;
use JsonMapper;
use RedBO\RedFactory;

class price extends BaseController
{
    /**
     * @throws \JsonMapper_Exception
     */
    public function AddItemsPricesAction() {
        $mapper = new JsonMapper();

        $itemIds = (file_get_contents(Common::GUILDWAR2_BASE_URL.Common::GUILDWAR2_PRICES));
        $itemIds = substr($itemIds,1);
        $itemIds = substr($itemIds,0,-1);

        $itemIdArr = explode(",",$itemIds);

        $i = 0;
        $gwItemIds = array();
        $url_prices_fetch = Common::GUILDWAR2_BASE_URL.Common::GUILDWAR2_PRICES."?ids=";
        $concat_ids = "";
        foreach($itemIdArr as $value) {
            $concat_ids .= $value;
            $gwItemIds[$i] = $value;

            if ($i != 199) {
                $concat_ids .= ",";
                $i++;
            } else {
                $jsonArr = json_decode(file_get_contents($url_prices_fetch . $concat_ids));

                foreach ($jsonArr as $json) {
                    $price = $mapper->map($json, new \Serialization\Prices());
                    $redPrice = RedFactory::GetRedGuildPrices()->FindByItemId($price->id);
                    if (empty($redPrice)) {
                        $this->SavePrice($price);
                    }
                }

                $concat_ids = "";
                $gwItemIds = array();
                $i = 0;
            }
        }

        //Process last batch
        if ($i > 0 ) {
            //If no ids are found, then add the prices
            //Remove last comma
            if (substr($concat_ids, -1) == ",")
                $concat_ids = substr($concat_ids, 0, -1);

            $jsonArr = json_decode(file_get_contents($url_prices_fetch . $concat_ids));

            foreach ($jsonArr as $json) {
                $price = $mapper->map($json, new \Serialization\Prices());

                $redPrice = RedFactory::GetRedGuildPrices()->FindByItemId($price->id);
                if (empty($redPrice))
                    $this->SavePrice($price);
            }

        }

        echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,"All prices have been synced"));;
    }

    private function SavePrice($price) {
        if ($price != null) {
            RedFactory::GetRedGuildPrices()->AddPrice($price->id,$price->buys->unit_price,$price->buys->quantity,$price->sells->unit_price,$price->sells->quantity);
            RedFactory::GetRedGuildPricesHistory()->AddPrice($price->id,$price->buys->unit_price,$price->buys->quantity,$price->sells->unit_price,$price->sells->quantity);
        }
    }

    private function UpdatePrice($id,$price) {
        if ($price != null) {
            RedFactory::GetRedGuildPrices()->UpdatePrices($id,$price->id,$price->buys->unit_price,$price->buys->quantity,$price->sells->unit_price,$price->sells->quantity);
            RedFactory::GetRedGuildPricesHistory()->AddPrice($price->id,$price->buys->unit_price,$price->buys->quantity,$price->sells->unit_price,$price->sells->quantity);
        }
    }

    public function UpdateItemsPricesAction() {
        $prices = RedFactory::GetRedGuildPrices()->FindAllUnsyncedPrices();
        $this->UpdateItemsPrices($prices);
    }

    public function UpdateItemsPricesByIdsAction($ids) {
        $idsArr  = explode(",", $ids);
        $prices = RedFactory::GetRedGuildPrices()->FindAllUnsyncedPricesByIds($idsArr);
        $this->UpdateItemsPrices($prices);
    }

    private function UpdateItemsPrices($prices) {

        $mapper = new JsonMapper();
        if (!empty($prices)) {

            $i = 0;
            $url_prices_fetch = Common::GUILDWAR2_BASE_URL . Common::GUILDWAR2_PRICES . "?ids=";
            $concat_ids = "";
            foreach ($prices as $price) {
                $concat_ids .= $price["gw_item_id"];

                if ($i != 199) {
                    $concat_ids .= ",";
                    $i++;
                } else {
                    $jsonArr = json_decode(file_get_contents($url_prices_fetch . $concat_ids));

                    foreach ($jsonArr as $json) {
                        $mappedprice = $mapper->map($json, new \Serialization\Prices());
                        $redPrice = RedFactory::GetRedGuildPrices()->FindByItemId($mappedprice->id);
                        if (!empty($redPrice))
                            $this->UpdatePrice($redPrice->id,$mappedprice);
                        else
                            $this->SavePrice($mappedprice);
                    }

                    $concat_ids = "";
                    $i = 0;
                }
            }

            //Process last batch
            if ($i > 0) {
                //Remove last comma
                if (substr($concat_ids, -1) == ",")
                    $concat_ids = substr($concat_ids, 0, -1);
                $jsonArr = json_decode(file_get_contents($url_prices_fetch . $concat_ids));

                foreach ($jsonArr as $json) {
                    $mappedprice = $mapper->map($json, new \Serialization\Prices());
                    $redPrice = RedFactory::GetRedGuildPrices()->FindByItemId($mappedprice->id);
                    if (!empty($redPrice)) {
                        $this->UpdatePrice($redPrice->id, $mappedprice);
                    } else
                        $this->SavePrice($mappedprice);
                }
            }
        }

        echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,"All prices have been synced"));
    }


	 public function GetAllItemPricesAction($itemid)
    {
        $prices = RedFactory::GetRedGuildPricesHistory()->FindByItemId($itemid);
        echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,Common::ConvertBeanToArray($prices,"prices")));
    }


}