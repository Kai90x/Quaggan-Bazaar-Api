<?php
namespace KaiApp\RedBO;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;

class RedItemDetailsInfixUpgrade extends RedBase{
	
	const ITEMDETAILSINFIXUPGRADE = 'itemdetailsinfixupgrade';
    public function __construct()
    {
        parent::__construct(SELF::ITEMDETAILSINFIXUPGRADE);
    }

	public function add($itemDetailsId) {
        return parent::add(array(
            "itemdetailsId" => $itemDetailsId
        ));
    }

    public function getByItemDetailsId($id) {
        return parent::getByOne("itemdetailsId",$id);
    }

    public function getByItemDetailsIds($idArr) {
        return parent::getByIn("itemdetailsId",$idArr);
    }

	public function deleteByItemDetailsId($itemDetailsId) {
		return parent::delete("itemdetailsId",$itemDetailsId);
	}

}