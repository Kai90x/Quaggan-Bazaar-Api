<?php
namespace KaiApp\RedBO;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;

class RedItemDetails extends RedBase{

    const ITEMDETAILS = 'itemdetails';
    public function __construct()
    {
        parent::__construct(SELF::ITEMDETAILS);
    }

    public function add($itemId, $type, $weight_class, $defense, $suffix_item_id, $secondary_suffix_item_id, $size, $no_sell_or_sort,
                                   $description, $duration_ms, $unlock_type, $color_id, $recipe_id, $charges, $flags, $infusion_upgrade_flags, $suffix, $bonuses,
                                   $damage_type, $min_power, $max_power ) {
        return parent::add(array(
            "itemId" => $itemId,
            "type" => $type,
            "weight_class" => $weight_class,
            "defense" => $defense,
            "suffix_item_id" => $suffix_item_id,
            "secondary_suffix_item_id" => $secondary_suffix_item_id,
            "size" => $size,
            "no_sell_or_sort" => $no_sell_or_sort,
            "description" => $description,
            "duration_ms" => $duration_ms,
            "unlock_type" => $unlock_type,
            "color_id" => $color_id,
            "recipe_id" => $recipe_id,
            "charges" => $charges,
            "flags" => $flags,
            "infusion_upgrade_flags" => $infusion_upgrade_flags,
            "suffix" => $suffix,
            "bonuses" => $bonuses,
            "damage_type" => $damage_type,
            "min_power" => $min_power,
            "max_power" => $max_power,
        ));
    }

    public function update($updateid,$itemId, $type, $weight_class, $defense, $suffix_item_id, $secondary_suffix_item_id, $size, $no_sell_or_sort,
                                   $description, $duration_ms, $unlock_type, $color_id, $recipe_id, $charges, $flags, $infusion_upgrade_flags, $suffix, $bonuses,
                                   $damage_type, $min_power, $max_power ) {
        return parent::update($updateid,array(
            "itemId" => $itemId,
            "type" => $type,
            "weight_class" => $weight_class,
            "defense" => $defense,
            "suffix_item_id" => $suffix_item_id,
            "secondary_suffix_item_id" => $secondary_suffix_item_id,
            "size" => $size,
            "no_sell_or_sort" => $no_sell_or_sort,
            "description" => $description,
            "duration_ms" => $duration_ms,
            "unlock_type" => $unlock_type,
            "color_id" => $color_id,
            "recipe_id" => $recipe_id,
            "charges" => $charges,
            "flags" => $flags,
            "infusion_upgrade_flags" => $infusion_upgrade_flags,
            "suffix" => $suffix,
            "bonuses" => $bonuses,
            "damage_type" => $damage_type,
            "min_power" => $min_power,
            "max_power" => $max_power,
        ));
    }

    public function getByItemId($id) {
        return parent::getByOne("itemId",$id);
    }

    public function getByItemIds($idArr) {
        return parent::getByIn("itemId",$idArr);
    }

	public function deleteByItemId($itemId) {
        return parent::delete("itemId",$itemId);
	}

}