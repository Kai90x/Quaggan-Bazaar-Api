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
    public function transform($message)
    {
        return [
            'message' => $message
        ];
    }

}