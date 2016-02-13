<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 7:55 AM
 */

namespace KaiApp\JsonTransformers;

use League\Fractal\TransformerAbstract;

class SimpleTransformer extends TransformerAbstract
{
    private $extra;

    public function  __construct($_extra = null) {
        $this->extra = $_extra;
    }

    public function transform($message)
    {
        $jsonArr = [
            'message' => $message
        ];

        if (!empty($this->extra))
            $jsonArr['extra'] = $this->extra;

        return $jsonArr;
    }

}