<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use Serialization\InfixUpgrade;
use Utils\Common;
use JsonMapper;
use RedBO\RedFactory;

class recipe extends BaseController
{
	public function SyncRecipesAction() {
        $mapper = new JsonMapper();
		$recipeIds = (file_get_contents(Common::GUILDWAR2_BASE_URL.Common::GUILDWAR2_RECIPE));

        $recipeIds = substr($recipeIds, 1);
        $recipeIds = substr($recipeIds, 0, -1);

        $recipeArr = explode(",",$recipeIds);
        $unsyncedRecipeArr = array();

        $x = 0;
        foreach($recipeArr as $value) {
            $recipe = RedFactory::GetRedRecipe()->FindByRecipeId($value);
            if (empty($recipe)) {
                RedFactory::GetRedRecipe()->addId($value);
                //put in Update recipe array
                $unsyncedRecipeArr[$x] = $value;
                $x++;
            } else {
                //If found, check last time recipe was synced
                $sync_date = $recipe->sync_date;
                if (empty($sync_date)) {
                    //put in Update item array
                    $unsyncedRecipeArr[$x] = $value;
                    $x++;
                } else {
                    //Check sync date
                    //Number of days before syncing recipe (run each week)
                    $date = strtotime($sync_date);
                    $date_to_sync = strtotime("+2 day", $date);

                    $current_time = time();
                    if ($date_to_sync < $current_time) {
                        //put in Update recipe array
                        $unsyncedRecipeArr[$x] = $value;
                        $x++;
                    }
                }
            }
        }


        $i = 0;
        $url_recipe_fetch = Common::GUILDWAR2_BASE_URL.Common::GUILDWAR2_RECIPE."?ids=";
        $concat_ids = "";
        foreach($unsyncedRecipeArr as $value) {
            $concat_ids .= $value;

            if ($i != 199) {
                $concat_ids .= ",";
                $i++;
            } else {
                $jsonArr = json_decode(file_get_contents($url_recipe_fetch . $concat_ids));

                foreach ($jsonArr as $json) {
                    $recipe = $mapper->map($json, new \Serialization\Recipe());
                    $this->UpdateRecipe($recipe);
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

                $recipe = $mapper->map($json, new \Serialization\Recipe());

                $this->UpdateRecipe($recipe);

            }
        }

        echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,"All recipes have been synced"));;
	}

    public function GetRecipeByItemIdAction($itemId) {
        $recipes = RedFactory::GetRedRecipe()->FindByOutputItemId($itemId);
        $this->ReturnRecipeDetails($recipes);
    }

    private function ReturnRecipeDetails($recipes) {

        if (empty($recipes)) {
            echo json_encode(Common::GenerateResponse(Common::STATUS_NOTFOUND,"No recipe found"));
        } else {
            $recipeDetail = $this->PutRecipeDetails($recipes);
            echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS, $recipeDetail->export()));
        }
    }

    private function PutRecipeDetails($recipe) {
        $recipe->disciples = unserialize($recipe->disciples);
        $recipe->flags = unserialize($recipe->flags);

        $ingredients = RedFactory::GetRedIngredients()->FindByRecipeId($recipe->id);

        if (!empty($ingredients)) {
            $recipe->ingredients = Common::ConvertBeanToArray($ingredients,"ingredients");
        }

        return $recipe;
    }

    private function SaveRecipe($recipe) {
        if ($recipe != null) {

            //AddRecipe($recipeId, $type , $output_item_id, $output_item_count, $time_to_craft_ms, $disciples,$min_rating, $flags ) {
            $recipe_id = RedFactory::GetRedRecipe()->add($recipe->id, $recipe->type,$recipe->output_item_id,$recipe->output_item_count,
                $recipe->time_to_craft_ms,serialize($recipe->disciples),$recipe->min_rating,serialize($recipe->flags));


            if (!empty($recipe->ingredients)) {
                foreach($recipe->ingredients as $ingredient) {
                    RedFactory::GetRedIngredients()->AddIngredients($recipe_id,$ingredient->itemId,$ingredient->count);
                }
            }

        }
    }

    private function UpdateRecipe($recipe) {
        if ($recipe != null) {
            $redRecipe = RedFactory::GetRedRecipe()->FindByRecipeId($recipe->id);

            //AddRecipe($recipeId, $type , $output_item_id, $output_item_count, $time_to_craft_ms, $disciples,$min_rating, $flags ) {
            $recipe_id = RedFactory::GetRedRecipe()->update($redRecipe->id,$recipe->id, $recipe->type,$recipe->output_item_id,$recipe->output_item_count,
                $recipe->time_to_craft_ms,serialize($recipe->disciplines),$recipe->min_rating,serialize($recipe->flags));

            //Delete all recipe ingredients first and then add everything
            RedFactory::GetRedIngredients()->DeleteIngredientsByRecipeId($recipe_id);

            if (!empty($recipe->ingredients)) {
                foreach($recipe->ingredients as $ingredient) {
                    RedFactory::GetRedIngredients()->AddIngredients($recipe_id,$ingredient->item_id,$ingredient->count);
                }
            }

        }
    }

    private function WipeRecipes(){
        RedFactory::GetRedIngredients()->DeleteAll();
        RedFactory::GetRedRecipe()->DeleteAll();
    }

}