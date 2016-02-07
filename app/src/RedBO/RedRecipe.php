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

class RedRecipe {

	const RECIPE = 'recipe';

    public function addId($recipeId) {
        $recipe = Facade::dispense(SELF::RECIPE);

        $recipe->gwRecipeId = $recipeId;

        return Facade::store($recipe);
    }

    public function add($recipeId, $type , $output_item_id, $output_item_count, $time_to_craft_ms, $disciples,$min_rating, $flags ) {
        $recipe = Facade::dispense(SELF::RECIPE);

        $recipe->gwRecipeId = $recipeId;
        $recipe->type = $type;
        $recipe->output_item_id = $output_item_id;
        $recipe->output_item_count = $output_item_count;
        $recipe->time_to_craft_ms = $time_to_craft_ms;
        $recipe->disciples = $disciples;
        $recipe->min_rating = $min_rating;
        $recipe->flags = $flags;

        return Facade::store($recipe);
    }

    public function update($id,$recipeId, $type , $output_item_id, $output_item_count, $time_to_craft_ms, $disciples,$min_rating, $flags ) {
        $recipe = Facade::dispense(SELF::RECIPE);

        $recipe->id = $id;
        $recipe->gwRecipeId = $recipeId;
        $recipe->type = $type;
        $recipe->output_item_id = $output_item_id;
        $recipe->output_item_count = $output_item_count;
        $recipe->time_to_craft_ms = $time_to_craft_ms;
        $recipe->disciples = $disciples;
        $recipe->min_rating = $min_rating;
        $recipe->flags = $flags;
        $recipe->sync_date = Facade::isoDateTime();

        return Facade::store($recipe);
    }

    public function FindByOutputItemId($id) {
        $recipe = Facade::findOne(SELF::RECIPE, 'output_item_id = ? ',array($id));

        if(empty($recipe)) {
            return null;
        } else {
            return $recipe;
        }
    }

    public function FindByRecipeId($id) {
        $recipe = Facade::findOne(SELF::RECIPE, 'gw_recipe_id = ? ',array($id));

        if(empty($recipe)) {
            return null;
        } else {
            return $recipe;
        }
    }
	
	public function DeleteRecipe($id) {
        $recipes = Facade::find(SELF::RECIPE,' id = ? ', array( $id ));
		
		if (empty($recipes)) {
			return false;
		} else {
            foreach($recipes as $recipe)
                Facade::trash($recipe);
			return true;
		}
	}
	
	public function DeleteAll() {
		  Facade::wipe( SELF::RECIPE );
		  return true;
	}
	
}