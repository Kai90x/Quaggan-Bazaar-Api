<?php
namespace KaiApp\RedBO;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedGuildPricesHistory extends RedQuery {

	const GUILDPRICEHISTORY = 'priceshistory';
	
	public function AddPrice($itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        $price = Facade::dispense(SELF::GUILDPRICEHISTORY);

        $price->gwItemId = $itemId;
        $price->buyprice = $buyprice;
        $price->buyquantity = $buyquantity;
        $price->sellprice = $sellprice;
        $price->sellquantity = $sellquantity;
        $price->date_updated = Facade::isoDateTime();
        return Facade::store($price);
    }

    public function FindByItemId($id) {
        $price = Facade::find(SELF::GUILDPRICEHISTORY, ' gw_item_id = ? ',array($id));

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }
	
	public function FindByItemIds($ids) {
        $price = Facade::find(SELF::GUILDPRICEHISTORY, ' gw_item_id = '.Facade::genSlots($ids).' ',$ids);

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }


    public function FindAllUnsyncedPrices() {
        $price = Facade::find(SELF::GUILDPRICEHISTORY,"WHERE DATE_ADD(date_updated, INTERVAL 15 MINUTE) < UTC_TIMESTAMP()");

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }

    public function FindPricesByDays($days) {
        $price = Facade::find(SELF::GUILDPRICEHISTORY," DATE_ADD(date_updated, INTERVAL ? DAY) < UTC_TIMESTAMP()", array($days));

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }

    public function DeleteOldPrices() {
        Facade::exec("DELETE FROM guildpriceshistory WHERE DATE_ADD(date_updated, INTERVAL 2 DAY) < UTC_TIMESTAMP() ORDER BY id LIMIT 500");
    }
	
	public function DeletePrice($gw_item_id) {
        return parent::delete("gwItemId",$gw_item_id);
	}

}