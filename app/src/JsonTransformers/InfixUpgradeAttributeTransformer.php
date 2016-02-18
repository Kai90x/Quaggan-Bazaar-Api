<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/18/16
 * Time: 8:56 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class InfixUpgradeAttributeTransformer extends  TransformerAbstract
{
    public function transform($attribute)
    {
        $jsonNodes =  [
            'attribute' => $attribute['attribute'],
            'modifier' => $attribute['modifier'],
        ];

        return $jsonNodes;
    }
}