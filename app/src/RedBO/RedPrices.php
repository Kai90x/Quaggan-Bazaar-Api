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

class RedPrices extends RedBase {

	const GUILDPRICE = 'prices';

    public function __construct()
    {
        parent::__construct(SELF::GUILDPRICE);
    }

    public function addGwId($recipeId) {
        return parent::add(array(
            "gwPricesId" => $recipeId
        ));
    }

	public function add($itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        return parent::add(array(
            "gwPricesId" => $itemId,
            "buyprice" => $buyprice,
            "buyquantity" => $buyquantity,
            "sellprice" => $sellprice,
            "sellquantity" => $sellquantity
        ));
    }
	
	public function update($id,$itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        return parent::update($id,array(
            "gwPricesId" => $itemId,
            "buyprice" => $buyprice,
            "buyquantity" => $buyquantity,
            "sellprice" => $sellprice,
            "sellquantity" => $sellquantity
        ));
    }

    public function getByItemId($id) {
        return parent::getByOne("gwPricesId",$id);
    }

	public function getByItemIds($ids) {
        return Facade::find(SELF::GUILDPRICE, parent::toBeanColumn("gwPricesId").' IN ( '.Facade::genSlots($ids).') ',$ids);
    }

    public function getUnsyncedPrices() {
        return Facade::find(SELF::GUILDPRICE,"DATE_ADD(".parent::toBeanColumn($this->dateModified).", INTERVAL 15 MINUTE) < UTC_TIMESTAMP()");
    }

    public function getAllUnsyncedPricesByIds($ids) {
        return Facade::find(SELF::GUILDPRICE,'DATE_ADD('.parent::toBeanColumn($this->dateModified).', INTERVAL 15 MINUTE) < UTC_TIMESTAMP() AND '.parent::toBeanColumn('gwPricesId').' IN ( '.Facade::genSlots($ids).') ',$ids);
    }

    public function getByDays($days) {
        return Facade::find(SELF::GUILDPRICE,"DATE_ADD(".parent::toBeanColumn($this->dateModified).", INTERVAL ? DAY) < UTC_TIMESTAMP()", array($days));
    }
	
	public function delete($gw_item_id) {
        return parent::delete(("gwItemId"),$gw_item_id);
	}

}