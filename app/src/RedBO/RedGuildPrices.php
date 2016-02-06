<?php
namespace KaiApp\RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedGuildPrices {

	const GUILDPRICE = 'guildprices';
	
	public function AddPrice($itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        $price = Facade::dispense(SELF::GUILDPRICE);

        $price->gwItemId = $itemId;
		$price->buyprice = $buyprice;
        $price->buyquantity = $buyquantity;
		$price->sellprice = $sellprice;
        $price->sellquantity = $sellquantity;
        $price->date_updated = Facade::isoDateTime();
        return Facade::store($price);
    }
	
	public function UpdatePrices($id,$itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        $price = Facade::dispense(SELF::GUILDPRICE);

		$price->id = $id;
        $price->gwItemId = $itemId;
		$price->buyprice = $buyprice;
        $price->buyquantity = $buyquantity;
		$price->sellprice = $sellprice;
        $price->sellquantity = $sellquantity;
        $price->date_updated = Facade::isoDateTime();
        return Facade::store($price);
    }

    public function FindByItemId($id) {
        $price = Facade::findOne(SELF::GUILDPRICE, ' gw_item_id = ? ',array($id));

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }

	public function FindByItemIds($ids) {
        $price = Facade::find(SELF::GUILDPRICE, ' gw_item_id IN ( '.Facade::genSlots($ids).') ',$ids);

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }

    public function FindAllUnsyncedPrices() {
        $price = Facade::find(SELF::GUILDPRICE,"WHERE DATE_ADD(date_updated, INTERVAL 15 MINUTE) < UTC_TIMESTAMP()");

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }

    public function FindAllUnsyncedPricesByIds($ids) {
        $price = Facade::find(SELF::GUILDPRICE,'WHERE DATE_ADD(date_updated, INTERVAL 15 MINUTE) < UTC_TIMESTAMP() AND gw_item_id IN ( '.Facade::genSlots($ids).') ',$ids);

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }

    public function FindPricesByDays($days) {
        $price = Facade::find(SELF::GUILDPRICE," DATE_ADD(date_updated, INTERVAL ? DAY) < UTC_TIMESTAMP()", array($days));

        if(empty($price)) {
            return null;
        } else {
            return $price;
        }
    }
	
	public function DeletePrice($gw_item_id) {
        $prices = Facade::find(SELF::GUILDPRICE,' gw_item_id = ? ', array( $gw_item_id ));
		
		if (empty($prices)) {
			return false;
		} else {
            if (is_array($prices)) {
                foreach ($prices as $price)
                    Facade::trash($price);
            } else {
                Facade::trash($prices);
            }
			return true;
		}
	}
	
	public function DeleteAll() {
		  Facade::wipe( SELF::GUILDPRICE );
		  return true;
	}
	
}