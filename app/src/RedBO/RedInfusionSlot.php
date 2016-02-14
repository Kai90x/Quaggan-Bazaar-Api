<?php
namespace KaiApp\RedBO;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;

class RedInfusionSlot extends RedBase {

    const ITEMDETAILSINFUSIONSLOT = 'itemdetailsinfusionslots';
    public function __construct()
    {
        parent::__construct(SELF::ITEMDETAILSINFUSIONSLOT);
    }

    public function add($itemDetailsId,$flags,$item_id) {
        return parent::add(array(
            "itemdetailsId" => $itemDetailsId,
            "flags" => $flags,
            "itemId" => $item_id
        ));
    }

    public function getByItemDetailsId($id) {
        return parent::getByAll("itemdetailsId",$id);
    }

    public function getByItemDetailsIds($idArr) {
        return parent::getByIn("itemdetailsId",$idArr);
    }

    public function deleteByItemDetailsId($itemDetailsId) {
        return parent::delete("itemdetailsId",$itemDetailsId);
    }
	
}