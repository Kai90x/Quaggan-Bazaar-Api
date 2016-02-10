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

class RedGuildPrices extends RedBase {

	const GUILDPRICE = 'prices';

    public function __construct()
    {
        parent::__construct(SELF::GUILDPRICE);
    }

	public function add($itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        return parent::add(array(
            "gwItemId" => $itemId,
            "buyprice" => $itemId,
            "buyquantity" => $itemId,
            "sellprice" => $itemId,
            "sellquantity" => $itemId
        ));
    }
	
	public function update($id,$itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        return parent::update($id,array(
            "gwItemId" => $itemId,
            "buyprice" => $itemId,
            "buyquantity" => $itemId,
            "sellprice" => $itemId,
            "sellquantity" => $itemId
        ));
    }

    public function getByItemId($id) {
        return parent::getByOne(parent::toBeanColumn("gwItemId"),$id);
    }

	public function getByItemIds($ids) {
        return Facade::find(SELF::GUILDPRICE, parent::toBeanColumn("gwItemId").' IN ( '.Facade::genSlots($ids).') ',$ids);
    }

    public function getUnsyncedPrices() {
        return Facade::find(SELF::GUILDPRICE,"DATE_ADD(".parent::toBeanColumn($this->dateModified).", INTERVAL 15 MINUTE) < UTC_TIMESTAMP()");
    }

    public function getAllUnsyncedPricesByIds($ids) {
        return Facade::find(SELF::GUILDPRICE,'DATE_ADD('.parent::toBeanColumn($this->dateModified).', INTERVAL 15 MINUTE) < UTC_TIMESTAMP() AND gw_item_id IN ( '.Facade::genSlots($ids).') ',$ids);
    }

    public function getByDays($days) {
        return Facade::find(SELF::GUILDPRICE,"DATE_ADD(".parent::toBeanColumn($this->dateModified).", INTERVAL ? DAY) < UTC_TIMESTAMP()", array($days));
    }
	
	public function delete($gw_item_id) {
        return parent::delete($this->toBeanColumn("gwItemId"),$gw_item_id);
	}

}