<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/8/16
 * Time: 12:18 AM
 */

namespace KaiApp\JsonTransformers;

class SubItem2Transformer extends LegendaryBaseTransformer
{
    protected $defaultIncludes = [
        'subitem3'
    ];

    public function includeSubitem3($subitem)
    {
        if (!empty($subitem["sub3Item"]))
            return $this->collection( $subitem["sub3Item"], new SubItem3Transformer());
    }
}