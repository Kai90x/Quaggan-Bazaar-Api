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

class RedPricesHistory extends RedQuery {

	const GUILDPRICEHISTORY = 'priceshistory';

    public function __construct()
    {
        parent::__construct(SELF::GUILDPRICEHISTORY);
    }

    public function add($itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        return parent::add(array(
            "gwItemId" => $itemId,
            "buyprice" => $buyprice,
            "buyquantity" => $buyquantity,
            "sellprice" => $sellprice,
            "sellquantity" => $sellquantity
        ));
    }

    public function update($id,$itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        return parent::update($id,array(
            "gwItemId" => $itemId,
            "buyprice" => $buyprice,
            "buyquantity" => $buyquantity,
            "sellprice" => $sellprice,
            "sellquantity" => $sellquantity
        ));
    }

    public function getByItemId($id) {
        return parent::getByOne(("gwItemId"),$id);
    }

    public function getAllByItemId($id) {
        return parent::getByAll(("gwItemId"),$id);
    }

    public function getUnsyncedPrices() {
        return Facade::find(SELF::GUILDPRICEHISTORY,"DATE_ADD(".parent::toBeanColumn($this->dateModified).", INTERVAL 15 MINUTE) < UTC_TIMESTAMP()");
    }

    public function getAllUnsyncedPricesByIds($ids) {
        return Facade::find(SELF::GUILDPRICEHISTORY,'DATE_ADD('.parent::toBeanColumn($this->dateModified).', INTERVAL 15 MINUTE) < UTC_TIMESTAMP() AND gw_item_id IN ( '.Facade::genSlots($ids).') ',$ids);
    }

    public function delete($gw_item_id) {
        return parent::delete(("gwItemId"),$gw_item_id);
    }
}