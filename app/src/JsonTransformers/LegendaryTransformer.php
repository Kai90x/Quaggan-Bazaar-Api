<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/7/16
 * Time: 9:10 PM
 */

namespace KaiApp\JsonTransformers;

class LegendaryTransformer extends LegendaryBaseTransformer
{
    protected $defaultIncludes = [
        'subitem1'
    ];

    public function includeSubitem1($legendary)
    {
        return $this->collection( $legendary["sub1Item"], new SubItem1Transformer());
    }
}