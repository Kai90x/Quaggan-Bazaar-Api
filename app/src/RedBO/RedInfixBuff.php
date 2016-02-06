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

class RedInfixBuff {

	const INFIXBUFF = 'infixbuff';
	
	public function AddInfixBuff($infuxId, $skill_id , $description ) {
        $infixBuff = Facade::dispense(SELF::INFIXBUFF);

        $infixBuff->itemdetailsinfixupgradeId = $infuxId;
		$infixBuff->skill_id = $skill_id;
		$infixBuff->description = $description;

        Facade::store($infixBuff);
    }

    public function FindByInfixUpgradeId($id) {
        $infixBuff = Facade::findOne(SELF::INFIXBUFF, 'itemdetailsinfixupgrade_id = ? ',array($id));

        if(empty($infixBuff)) {
            return null;
        } else {
            return $infixBuff;
        }
    }

    public function FindByInfixUpgradeIds($idArr) {
        $infixBuff = Facade::find(SELF::INFIXBUFF, 'itemdetailsinfixupgrade_id IN ('.Facade::genSlots($idArr).') ',($idArr));

        if(empty($infixBuff)) {
            return null;
        } else {
            return $infixBuff;
        }
    }
	
	public function DeleteInfixBuffByInfuxId($infuxId) {
		$infixBuffs = Facade::find(SELF::INFIXBUFF,' itemdetailsinfixupgrade_id = ? ', array( $infuxId ));
		
		if (empty($infixBuffs)) {
			return false;
		} else {
            foreach($infixBuffs as $infixBuff)
                Facade::trash($infixBuff);
			return true;
		}
	}
	
	public function DeleteAll() {
		  Facade::exec( 'DELETE FROM '.SELF::INFIXBUFF );
		  return true;
	}

}