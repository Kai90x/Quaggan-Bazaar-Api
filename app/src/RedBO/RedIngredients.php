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

class RedIngredients {

	const INGREDIENTS = 'ingredients';
	
	public function AddIngredients($recipeId, $itemId, $count ) {
        $ingredient = Facade::dispense(SELF::INGREDIENTS);

        $ingredient->recipeId = $recipeId;
        $ingredient->itemId = $itemId;
        $ingredient->count = $count;

        Facade::store($ingredient);
    }

    public function FindByRecipeId($id) {
        $ingredients = Facade::find(SELF::INGREDIENTS, 'recipe_id = ? ',array($id));

        if(empty($ingredients)) {
            return null;
        } else {
            return $ingredients;
        }
    }
	
	public function DeleteIngredientsByRecipeId($id) {
        $ingredients = Facade::find(SELF::INGREDIENTS,' recipe_id = ? ', array( $id ));
		
		if (empty($ingredients)) {
			return false;
		} else {
            foreach($ingredients as $ingredient)
                Facade::trash($ingredient);
			return true;
		}
	}
	
	public function DeleteAll() {
		  Facade::wipe( SELF::INGREDIENTS );
		  return true;
	}

}