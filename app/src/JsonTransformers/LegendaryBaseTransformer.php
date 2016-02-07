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
            'id' => $legendary['id'],
            'Name' => $legendary['name'],
            'GuildItemId' => $legendary['gw_item_id'],
            'DateCreated' => $legendary['date_created'],
            'Icon' => $legendary['icon'],
            'Rarity' => $legendary['rarity'],
            'Type' => $legendary['type']
        ];
    }

}