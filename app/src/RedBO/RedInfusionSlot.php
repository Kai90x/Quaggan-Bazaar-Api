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

class RedInfusionSlot {

    const ITEMDETAILSINFUSIONSLOT = 'itemdetailsinfusionslots';

    public function AddItemDetailsInfusionSlot($itemDetailsId,$falgs,$item_id) {
        $infusionSlot = Facade::dispense(SELF::ITEMDETAILSINFUSIONSLOT);

        $infusionSlot->itemdetailsId = $itemDetailsId;
        $infusionSlot->flags = $falgs;
        $infusionSlot->item_id = $item_id;

        Facade::store($infusionSlot);
    }

    public function FindByItemDetailsId($id) {
        $infusionSlot = Facade::find(SELF::ITEMDETAILSINFUSIONSLOT, 'itemdetails_id = ? ',array($id));

        if(empty($infusionSlot)) {
            return null;
        } else {
            return $infusionSlot;
        }
    }

    public function FindByItemDetailsIds($idArr) {
        $infusionSlot = Facade::find(SELF::ITEMDETAILSINFUSIONSLOT, 'itemdetails_id IN ('.Facade::genSlots($idArr).') ',($idArr));

        if(empty($infusionSlot)) {
            return null;
        } else {
            return $infusionSlot;
        }
    }

    public function DeleteItemDetailsInfixUpgrade($itemDetailsId) {
        $infusionSlots = Facade::find(SELF::ITEMDETAILSINFUSIONSLOT,' itemdetails_id = ? ', array( $itemDetailsId ));

        if (empty($infusionSlots)) {
            return false;
        } else {
            foreach($infusionSlots as $infusionSlot)
                Facade::trash($infusionSlot);
            return true;
        }
    }

	public function DeleteAll() {
		  Facade::exec( 'DELETE FROM '.SELF::ITEMDETAILSINFUSIONSLOT );
		  return true;
	}
	
}