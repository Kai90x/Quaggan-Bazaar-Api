<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/13/16
 * Time: 11:14 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class IngredientsTransformer extends TransformerAbstract
{
    public function transform($ingredients)
    {
        return [
            "id" => $ingredients["id"],
            "item_id" => $ingredients["gw_item_id"],
            "count" => $ingredients["count"],
            'name' => $ingredients['name'],
            'icon' => $ingredients['icon'],
            'rarity' => $ingredients['rarity'],
            'type' => $ingredients['type']
        ];
    }
}