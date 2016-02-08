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
            'Area' => $events->area_title,
            'Boss' => $events->boss,
            'Zone' => str_replace(" UTC","",$events->zone_title),
            'SpawnTimeUtc' => $events->spawn_time_utc,
        ];
    }

}