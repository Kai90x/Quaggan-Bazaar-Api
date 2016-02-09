<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/8/16
 * Time: 12:08 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class LegendaryBaseTransformer extends TransformerAbstract
{
    public function transform($legendary)
    {
        return [
            'name' => $legendary['name'],
            'id' => $legendary['gw_item_id'],
            'date_created' => $legendary['date_created'],
            'icon' => $legendary['icon'],
            'rarity' => $legendary['rarity'],
            'type' => $legendary['type']
        ];
    }

}