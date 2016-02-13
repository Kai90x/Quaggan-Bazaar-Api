<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 8:06 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class RecipesTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'ingredients'
    ];

    public function transform($recipe)
    {
        return [
            "id" => $recipe->id,
            "type" => $recipe->type,
            "output_item_id" => $recipe->output_item_id,
            "output_item_count" => $recipe->output_item_count,
            "time_to_craft_ms" => $recipe->time_to_craft_ms,
            "disciples" => $recipe->disciples,
            "min_rating" => $recipe->min_rating,
            "flags" => $recipe->flags,
        ];
    }

    public function includeIngredients($recipe)
    {
        if (!empty($recipe->ingredients))
            return $this->collection( $recipe->ingredients, new IngredientsTransformer());
    }

}