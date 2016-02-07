<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/8/16
 * Time: 12:03 AM
 */

namespace KaiApp\JsonTransformers;

class SubItem1Transformer extends LegendaryBaseTransformer
{
    protected $defaultIncludes = [
        'subitem2'
    ];

    public function includeSubitem2($subitem)
    {
        if (!empty($subitem["sub2Item"]))
            return $this->collection( $subitem["sub2Item"], new SubItem2Transformer());
    }
}