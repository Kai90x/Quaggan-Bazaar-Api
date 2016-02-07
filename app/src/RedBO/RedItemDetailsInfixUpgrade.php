<?php
namespace RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedItemDetailsInfixUpgrade {
	
	const ITEMDETAILSINFIXUPGRADE = 'itemdetailsinfixupgrade';
	
	public function AddItemDetailsInfixUpgrade($itemDetailsId) {
        $infuxUpgrade = Facade::dispense(SELF::ITEMDETAILSINFIXUPGRADE);

        $infuxUpgrade->itemdetailsId = $itemDetailsId;

        return Facade::store($infuxUpgrade);
    }

    public function FindByItemDetailsId($id) {
        $infixUpgrade = Facade::findOne(SELF::ITEMDETAILSINFIXUPGRADE, 'itemdetails_id = ? ',array($id));

        if(empty($infixUpgrade)) {
            return null;
        } else {
            return $infixUpgrade;
        }
    }

    public function FindByItemDetailsIds($idArr) {
        $infixUpgrade = Facade::find(SELF::ITEMDETAILSINFIXUPGRADE, 'itemdetails_id IN ('.Facade::genSlots($idArr).') ',($idArr));

        if(empty($infixUpgrade)) {
            return null;
        } else {
            return $infixUpgrade;
        }
    }

	public function DeleteItemDetailsInfixUpgrade($itemDetailsId) {
		$infuxUpgrades = Facade::find(SELF::ITEMDETAILSINFIXUPGRADE,' itemdetails_id = ? ', array( $itemDetailsId ));
		
		if (empty($infuxUpgrades)) {
			return false;
		} else {
            foreach($infuxUpgrades as $infuxUpgrade)
                Facade::trash($infuxUpgrade);
			return true;
		}
	}
	
	public function DeleteAll() {
		  Facade::exec( 'DELETE FROM '.SELF::ITEMDETAILSINFIXUPGRADE );
		  return true;
	}
	
}