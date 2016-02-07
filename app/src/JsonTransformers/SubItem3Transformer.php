<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/8/16
 * Time: 12:19 AM
 */

namespace KaiApp\JsonTransformers;


class SubItem3Transformer extends LegendaryBaseTransformer
{
    protected $defaultIncludes = [
        'subitem4'
    ];

    public function includeSubitem4($subitem)
    {
        if (!empty($subitem["sub4Item"]))
            return $this->collection( $subitem["sub4Item"], new SubItem1Transformer());
    }
}