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

class RedInfixAttributes {

    const INFIXATTRIBUTE = 'infixattribute';
	
	public function AddInfixAttribute($infuxId, $attribute, $modifier) {
        $infixAttribute = Facade::dispense(SELF::INFIXATTRIBUTE);

        $infixAttribute->itemdetailsinfixupgradeId = $infuxId;
		$infixAttribute->attribute = $attribute;
		$infixAttribute->modifier = $modifier;

        Facade::store($infixAttribute);
    }

    public function FindByInfixUpgradeId($id) {
        $infixAttribute = Facade::find(SELF::INFIXATTRIBUTE, 'itemdetailsinfixupgrade_id = ? ',array($id));

        if(empty($infixAttribute)) {
            return null;
        } else {
            return $infixAttribute;
        }
    }


    public function FindByInfixUpgradeIds($idArr) {
        $infixAttribute = Facade::find(SELF::INFIXATTRIBUTE, 'itemdetailsinfixupgrade_id IN ('.Facade::genSlots($idArr).') ', ($idArr));

        if(empty($infixAttribute)) {
            return null;
        } else {
            return $infixAttribute;
        }
    }

    public function DeleteInfixAttributeByInfuxId($infuxId) {
		$infixAttributes = Facade::find(SELF::INFIXATTRIBUTE,' itemdetailsinfixupgrade_id = ? ', array( $infuxId ));
		
		if (empty($infixAttributes)) {
			return false;
		} else {
            foreach($infixAttributes as $infixAttribute)
			    Facade::trash($infixAttribute);

			return true;
		}
	}

	public function DeleteAll() {
		  Facade::exec('DELETE FROM '. SELF::INFIXATTRIBUTE );
		  return true;
	}
	
}