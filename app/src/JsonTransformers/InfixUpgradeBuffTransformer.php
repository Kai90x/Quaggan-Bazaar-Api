<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/18/16
 * Time: 9:14 AM
 */

namespace KaiApp\JsonTransformers;

use League\Fractal\TransformerAbstract;

class InfixUpgradeBuffTransformer extends  TransformerAbstract
{
    public function transform($buff)
    {
        $jsonNodes =  [
            'skill_id' => $buff['skill_id'],
            'description' => $buff['description'],
        ];

        return $jsonNodes;
    }

}