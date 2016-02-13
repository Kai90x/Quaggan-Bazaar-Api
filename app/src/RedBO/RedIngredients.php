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

class RedIngredients extends RedBase{

	const INGREDIENTS = 'ingredients';
    public function __construct()
    {
        parent::__construct(SELF::INGREDIENTS);
    }

	public function add($recipeId, $itemId, $count ) {
        return parent::add(array(
            "recipeId" => $recipeId,
            "itemId" => $itemId,
            "count" => $count
        ));
    }

    public function getByRecipeId($id) {
        return parent::getByAll("recipeId",$id);
    }
	
	public function deleteByRecipeId($id) {
        return parent::delete("recipeId",$id);
	}

}