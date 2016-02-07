<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/7/16
 * Time: 9:11 PM
 */

namespace KaiApp\JsonTransformers;

use League\Fractal\TransformerAbstract;
use RedBeanPHP\OODBBean;

class LegendaryIdsTransformer extends TransformerAbstract
{
    public function transform(OODBBean $legendaries)
    {
        return [
            'id' => $legendaries['id'],
            'GuildItemId' => $legendaries['gw_item_id'],
            'DateCreated' => $legendaries['date_created']
        ];
    }
}