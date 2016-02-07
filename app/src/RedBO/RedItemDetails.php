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

class RedItemDetails {

    const ITEMDETAILS = 'itemdetails';

    public function AddItemDetails($itemId, $type, $weight_class, $defense, $suffix_item_id, $secondary_suffix_item_id, $size, $no_sell_or_sort,
                                   $description, $duration_ms, $unlock_type, $color_id, $recipe_id, $charges, $flags, $infusion_upgrade_flags, $suffix, $bonuses,
                                   $damage_type, $min_power, $max_power ) {
        $itemDetails = Facade::dispense(SELF::ITEMDETAILS);

        $itemDetails->itemId = $itemId;
        $itemDetails->type = $type;
        $itemDetails->weight_class = $weight_class;
        $itemDetails->defense = $defense;
        $itemDetails->suffix_item_id = $suffix_item_id;
        $itemDetails->secondary_suffix_item_id = $secondary_suffix_item_id;
        $itemDetails->size = $size;
        $itemDetails->no_sell_or_sort = $no_sell_or_sort;
        $itemDetails->description = $description;
        $itemDetails->duration_ms = $duration_ms;
        $itemDetails->unlock_type = $unlock_type;
        $itemDetails->color_id = $color_id;
        $itemDetails->recipe_id = $recipe_id;
        $itemDetails->charges = $charges;
        $itemDetails->flags = $flags;
        $itemDetails->infusion_upgrade_flags = $infusion_upgrade_flags;
        $itemDetails->suffix = $suffix;
        $itemDetails->bonuses = $bonuses;
        $itemDetails->damage_type = $damage_type;
        $itemDetails->min_power = $min_power;
        $itemDetails->max_power = $max_power;


        $itemDetailsId = Facade::store($itemDetails);

        return $itemDetailsId;
    }

    public function UpdateItemDetails($updateid,$itemId, $type, $weight_class, $defense, $suffix_item_id, $secondary_suffix_item_id, $size, $no_sell_or_sort,
                                   $description, $duration_ms, $unlock_type, $color_id, $recipe_id, $charges, $flags, $infusion_upgrade_flags, $suffix, $bonuses,
                                   $damage_type, $min_power, $max_power ) {
        $itemDetails = Facade::dispense(SELF::ITEMDETAILS);

        $itemDetails->id = $updateid;
        $itemDetails->itemId = $itemId;
        $itemDetails->type = $type;
        $itemDetails->weight_class = $weight_class;
        $itemDetails->defense = $defense;
        $itemDetails->suffix_item_id = $suffix_item_id;
        $itemDetails->secondary_suffix_item_id = $secondary_suffix_item_id;
        $itemDetails->size = $size;
        $itemDetails->no_sell_or_sort = $no_sell_or_sort;
        $itemDetails->description = $description;
        $itemDetails->duration_ms = $duration_ms;
        $itemDetails->unlock_type = $unlock_type;
        $itemDetails->color_id = $color_id;
        $itemDetails->recipe_id = $recipe_id;
        $itemDetails->charges = $charges;
        $itemDetails->flags = $flags;
        $itemDetails->infusion_upgrade_flags = $infusion_upgrade_flags;
        $itemDetails->suffix = $suffix;
        $itemDetails->bonuses = $bonuses;
        $itemDetails->damage_type = $damage_type;
        $itemDetails->min_power = $min_power;
        $itemDetails->max_power = $max_power;


        $itemDetailsId = Facade::store($itemDetails);

        return $itemDetailsId;
    }

    public function FindByItemId($id) {
        $details = Facade::findOne(SELF::ITEMDETAILS, 'WHERE item_id = ? ',array($id));

        if(empty($details)) {
            return null;
        } else {
            return $details;
        }
    }

    public function FindByItemIds($idArr) {
        $details = Facade::find(SELF::ITEMDETAILS, 'item_id IN ( '.Facade::genSlots($idArr).' ) ',$idArr);

        if(empty($details)) {
            return null;
        } else {
            return $details;
        }
    }

	public function DeleteItemDetails($itemId) {
		$itemDetails = Facade::find(SELF::ITEMDETAILS,' item_id = ? ', array( $itemId ));
		
		if (empty($itemDetails)) {
			return false;
		} else {
            foreach($itemDetails as $itemDetail)
                Facade::trash($itemDetail);
			return true;
		}
	}
	
	public function DeleteAll() {
		  Facade::exec( 'DELETE FROM '.SELF::ITEMDETAILS );
		  return true;
	}

}