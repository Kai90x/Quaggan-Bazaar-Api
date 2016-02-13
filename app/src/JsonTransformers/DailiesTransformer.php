<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 8:06 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class DailiesTransformer extends TransformerAbstract
{
    public function transform($daily)
    {
        return [
            "pvp" => [
                "id" => $daily->pvp_id,
                "level" => [
                    "min" => $daily->level1_min,
                    "max" => $daily->level1_max
                ]
            ],
            "pve" => [
                "id" => $daily->pve_id,
                "level" => [
                    "min" => $daily->level2_min,
                    "max" => $daily->level2_max
                ]
            ],
            "wvw" => [
                "id" => $daily->wvw_id,
                "level" => [
                    "min" => $daily->level3_min,
                    "max" => $daily->level3_max
                ]
            ],
            "special" => unserialize($daily->tokenreward),
            "date" => $daily->date_created
        ];
    }

}