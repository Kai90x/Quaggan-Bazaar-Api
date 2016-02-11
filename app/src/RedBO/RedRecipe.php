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

class RedRecipe extends RedBase{

	const RECIPE = 'recipe';
    public function __construct()
    {
        parent::__construct(SELF::RECIPE);
    }

    public function addId($recipeId) {
        return parent::add(array(
            "gwRecipeId" => $recipeId
        ));
    }

    public function add($recipeId, $type , $output_item_id, $output_item_count, $time_to_craft_ms, $disciples,$min_rating, $flags ) {
        return parent::add(array(
            "gwRecipeId" => $recipeId,
            "type" => $type,
            "output_item_id" => $output_item_id,
            "output_item_count" => $output_item_count,
            "time_to_craft_ms" => $time_to_craft_ms,
            "disciples" => $disciples,
            "min_rating" => $min_rating,
            "flags" => $flags,
        ));
    }

    public function update($id,$recipeId, $type , $output_item_id, $output_item_count, $time_to_craft_ms, $disciples,$min_rating, $flags ) {
        return parent::update($id,array(
            "gwRecipeId" => $recipeId,
            "type" => $type,
            "output_item_id" => $output_item_id,
            "output_item_count" => $output_item_count,
            "time_to_craft_ms" => $time_to_craft_ms,
            "disciples" => $disciples,
            "min_rating" => $min_rating,
            "flags" => $flags,
        ));
    }

    public function getByOutputItemId($id) {
        return parent::getByOne("output_item_id",$id);
    }

    public function getByRecipeId($id) {
        return parent::getByOne("gw_recipe_id",$id);
    }
	
	public function deleteById($id) {
       return parent::delete("id", $id);
	}

}