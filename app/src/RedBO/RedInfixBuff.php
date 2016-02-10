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

class RedInfixBuff extends RedBase{

	const INFIXBUFF = 'infixbuff';
    public function __construct()
    {
        parent::__construct(SELF::INFIXBUFF);
    }

	public function add($infuxId, $skill_id , $description ) {
        return parent::add(array(
            "itemdetailsinfixupgradeId" => $infuxId,
            "skillId" => $skill_id,
            "description" => $description
        ));
    }

    public function getByInfixId($id) {
        return parent::getByOne(parent::toBeanColumn("itemdetailsinfixupgradeId"),$id);
    }

    public function getByInfixIds($idArr) {
        return parent::getByIn("itemdetailsinfixupgradeId",$idArr);
    }
	
	public function deleteByInfuxId($infuxId) {
		return parent::delete("itemdetailsinfixupgradeId", $infuxId );
	}

}