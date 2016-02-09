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

class RedGuildPrices extends RedBase {

	const GUILDPRICE = 'guildprices';

    public function __construct()
    {
        parent::__construct(SELF::GUILDPRICE);
    }

	public function add($itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
        parent::add(array(
            "gwItemId" => $itemId,
            "buyprice" => $itemId,
            "buyquantity" => $itemId,
            "sellprice" => $itemId,
            "sellquantity" => $itemId
        ));
    }
	
	public function update($id,$itemId,$buyprice,$buyquantity,$sellprice,$sellquantity) {
          parent::update($id,array(
            "gwItemId" => $itemId,
            "buyprice" => $itemId,
            "buyquantity" => $itemId,
            "sellprice" => $itemId,
            "sellquantity" => $itemId
        ));
    }

    public function getByItemId($id) {
        return parent::getOne(parent::toBeanColumn("gw_item_id"),$id);
    }

	public function getByItemIds($ids) {
        $prices = Facade::find(SELF::GUILDPRICE, ' gw_item_id IN ( '.Facade::genSlots($ids).') ',$ids);
        return empty($prices) ? null : $prices;
    }

    public function getUnsyncedPrices() {
        $prices = Facade::find(SELF::GUILDPRICE,"DATE_ADD(date_updated, INTERVAL 15 MINUTE) < UTC_TIMESTAMP()");
        return empty($prices) ? null : $prices;
    }

    public function getAllUnsyncedPricesByIds($ids) {
        $prices = Facade::find(SELF::GUILDPRICE,'DATE_ADD(date_updated, INTERVAL 15 MINUTE) < UTC_TIMESTAMP() AND gw_item_id IN ( '.Facade::genSlots($ids).') ',$ids);
        return empty($prices) ? null : $prices;
    }

    public function getByDays($days) {
        $price = Facade::find(SELF::GUILDPRICE,"DATE_ADD(date_updated, INTERVAL ? DAY) < UTC_TIMESTAMP()", array($days));
        return empty($price) ? null : $price;
    }
	
	public function delete($gw_item_id) {
        return parent::delete($this->toBeanColumn("gwItemId"),$gw_item_id);
	}

}