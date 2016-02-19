<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/13/2016
 * Time: 8:10 PM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class ItemTransformer extends TransformerAbstract
{
    protected $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function transform($item)
    {
        //var_dump($item);
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
        ];

        if (isset($item['details'])) {
            $details = $item["details"];
            $jsonNodes["details"] = [
                'type' => $details['type'],
                'weight_class' => $details['weight_class'],
                'defense' => $details['defense'],
                'suffix_item_id' => $details['suffix_item_id'],
                'secondary_suffix_item_id' => $details['secondary_suffix_item_id'],
                'size' => $details['size'],
                'no_sell_or_sort' => $details['no_sell_or_sort'],
                'description' => $details['description'],
                'duration_ms' => $details['duration_ms'],
                'unlock_type' => $details['unlock_type'],
                'color_id' => $details['color_id'],
                'recipe_id' => $details['recipe_id'],
                'charges' => $details['charges'],
                'flags' => $details['flags'],
                'infusion_upgrade_flags' => $details['infusion_upgrade_flags'],
                'suffix' => $details['suffix'],
                'bonuses' => $details['bonuses'],
                'damage_type' => $details['damage_type'],
                'min_power' => $details['min_power'],
                'max_power' => $details['max_power']
            ];

            if (isset($details['infix_upgrade'])) {
                $jsonNodes["details"]['infix_upgrade'] = array();
                if (isset($details['infix_upgrade']->attributes) && isset($details['infix_upgrade']->attributes['attributes']))
                    $jsonNodes["details"]['infix_upgrade']['attributes'] = $this->fractal->createData(new Collection($details['infix_upgrade']->attributes['attributes'],new InfixUpgradeAttributeTransformer()))->toArray();

                if (isset($details['infix_upgrade']->buff))
                    $jsonNodes["details"]['infix_upgrade']['buff'] = $this->fractal->createData(new Item($details['infix_upgrade']->buff,new InfixUpgradeBuffTransformer()))->toArray();
            }
        }

        if (isset($item['price'])) {
            $price = $item['price'];
            $jsonNodes["price"] = [
                "buy" => [
                    'price' => $price['buyprice'],
                    'quantity' => $price['buyquantity']
                ],
                "sell" => [
                    'price' => $price['sellprice'],
                    'quantity' => $price['sellquantity'],
                ],
                'date_modified' => $price['date_modified']
            ];
        }

        return $jsonNodes;
    }
}