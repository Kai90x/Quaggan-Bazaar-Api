<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use KaiApp\JsonTransformers\RecipesTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedIngredients;
use KaiApp\RedBO\RedRecipe;
use KaiApp\Serialization\Recipe\Recipe;
use KaiApp\Utils;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;
use JsonMapper;

class RecipeController extends BaseController
{
    private $redRecipe;
    private $redIngredients;

    public function __construct(RedRecipe $_redRecipe,RedIngredients $_redIngredients) {
        $this->redRecipe = $_redRecipe;
        $this->redIngredients = $_redIngredients;
        parent::__construct();
    }

    public function sync(Request $request,Response $response, array $args) {
        $mapper = new JsonMapper();

        $recipeArr = Utils\GuildWars2Utils::getIds(Utils\GuildWars2Utils::getRecipeUrl());
        $unsyncedRecipeArr = array();

        $x = 0;
        foreach($recipeArr as $value) {
            $recipe = $this->redRecipe->getByRecipeId($value);
            if (empty($recipe)) {
                $this->redRecipe->addId($value);
                //put in Update recipe array
                $unsyncedRecipeArr[$x] = $value;
                $x++;
            } else {
                //If found, check last time recipe was synced
                if (empty($recipe->date_modified) || (strtotime("+2 day", strtotime($recipe->date_modified)) < time())) {
                    //put in Update item array
                    $unsyncedRecipeArr[$x] = $value;
                    $x++;
                }
            }
        }


        $i = 0;
        $url_recipe_fetch = Utils\GuildWars2Utils::getRecipeUrl()."?ids=";
        $concat_ids = "";
        foreach($unsyncedRecipeArr as $value) {
            $concat_ids .= $value;

            if ($i != 199) {
                $concat_ids .= ",";
                $i++;
            } else {
                $jsonArr = json_decode(\Httpful\Request::get(($url_recipe_fetch . $concat_ids))->send());

                foreach ($jsonArr as $json) {
                    $recipe = $mapper->map($json, new Recipe());
                    $this->update($recipe);
                }

                $concat_ids = "";
                $i = 0;
            }
        }

        if ($i > 0) {
            if (substr($concat_ids,-1) == ",")
                $concat_ids = substr($concat_ids, 0, -1);
            $jsonArr = json_decode(file_get_contents( $url_recipe_fetch.$concat_ids));

            foreach($jsonArr as $json) {
                $recipe = $mapper->map($json, new Recipe());
                $this->update($recipe);
            }
        }

        return $this->response(new Item("All recipes have been synced",new SimpleTransformer()),$response);
	}

    public function getByItemId(Request $request,Response $response, array $args) {
        $recipe = $this->redRecipe->getByOutputItemId($args['id']);
        if (empty($recipes)) {
            return $this->response(new Item("No recipe found", new SimpleTransformer()),$response,404);
        } else {
            $recipe->disciples = unserialize($recipe->disciples);
            $recipe->flags = unserialize($recipe->flags);

            $recipe->ingredients = $this->redIngredients->getWithDetails($recipe->id);

            return $this->response(new Item($recipe, new RecipesTransformer()),$response);
        }
    }

    private function update($recipe) {
        if ($recipe != null) {
            $redRecipe = $this->redRecipe->getByRecipeId($recipe->id);

            $recipe_id = $this->redRecipe->update($redRecipe->id,$recipe->id, $recipe->type,$recipe->output_item_id,$recipe->output_item_count,
                $recipe->time_to_craft_ms,serialize($recipe->disciplines),$recipe->min_rating,serialize($recipe->flags));

            $this->redIngredients->deleteByRecipeId($recipe_id);

            if (!empty($recipe->ingredients)) {
                foreach($recipe->ingredients as $ingredient)
                    $this->redIngredients->add($recipe_id,$ingredient->item_id,$ingredient->count);
            }
        }
    }

}