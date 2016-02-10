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

class RedInfixAttributes extends RedBase{

    const INFIXATTRIBUTE = 'infixattribute';
    public function __construct()
    {
        parent::__construct(SELF::INFIXATTRIBUTE);
    }

	public function add($infuxId, $attribute, $modifier) {
        return parent::add(array(
            "itemdetailsinfixupgradeId" => $infuxId,
            "attribute" => $attribute,
            "modifier" => $modifier
        ));
    }

    public function getByInfixId($id) {
        return parent::getByAll("itemdetailsinfixupgradeId",$id);
    }

    public function getByInfixIds($idArr) {
        return parent::getByIn("itemdetailsinfixupgradeId", $idArr);
    }

    public function deleteByInfuxId($infuxId) {
        return parent::delete(parent::toBeanColumn("itemdetailsinfixupgradeId"),$infuxId);
	}
	
}