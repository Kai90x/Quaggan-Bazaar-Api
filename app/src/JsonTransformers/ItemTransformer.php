<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/13/2016
 * Time: 8:10 PM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
{
    public function transform($item)
    {
        $jsonNodes =  [
            'id' => $item['gw_item_id'],
            'name' => $item['name'],
            'icon' => $item['icon'],
            'description' => $item['description'],
            'rarity' => $item['rarity'],
            'type' => $item['type'],
            'level' => $item['level'],
            'vendor_value' => $item['vendor_value'],
            'default_skin' => $item['default_skin'],
            'flags' => $item['flags'],
            'game_types' => $item['game_types'],
            'restrictions' => $item['restrictions'],
            'game_types' => $item['game_types'],
        ];

        if (array_key_exists("amount",$legendary))
            $jsonNodes["amount"] = $legendary["amount"];

        return $jsonNodes;
    }
}