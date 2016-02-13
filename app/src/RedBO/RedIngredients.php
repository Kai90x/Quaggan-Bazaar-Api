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

class RedIngredients extends RedQuery{

	const INGREDIENTS = 'ingredients';
    public function __construct()
    {
        parent::__construct(SELF::INGREDIENTS);
    }

	public function add($recipeId, $itemId, $count ) {
        return parent::add(array(
            "recipeId" => $recipeId,
            "gwItemId" => $itemId,
            "count" => $count
        ));
    }

    public function getByRecipeId($id) {
        return parent::getByAll("recipeId",$id);
    }
	
	public function deleteByRecipeId($id) {
        return parent::delete("recipeId",$id);
	}

    public function getWithDetails($recipeId) {
        $where = $this->addWhereClause($this->type,array(parent::getParamArray("recipeId",$recipeId)));
        $baseQuery = "SELECT ingredients.id,ingredients.item_id,ingredients.count,item.icon,item.type,
                      item.rarity,item.level,item.name FROM ingredients LEFT JOIN item ON item.gw_item_id = ingredients.gwItemId";
        return Facade::getAll($baseQuery.$where);
    }
}