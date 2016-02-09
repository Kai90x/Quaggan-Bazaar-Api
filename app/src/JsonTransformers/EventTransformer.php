<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/8/2016
 * Time: 1:07 PM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    public function transform($events)
    {
        return [
            'area' => $events->area_title,
            'boss' => $events->boss,
            'zone' => $events->zone_title,
            'spawn_time_utc' => str_replace(" UTC","",$events->spawn_time_utc),
        ];
    }

}