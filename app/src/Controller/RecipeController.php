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
        Utils\GuildWars2Utils::syncWithGuildWars2(Utils\GuildWars2Utils::getRecipeUrl(),$this->redRecipe,new Recipe(),array($this,'update'));
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

    public function update($recipe) {
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