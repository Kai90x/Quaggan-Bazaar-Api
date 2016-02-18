<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 8:06 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class PriceTransformer extends TransformerAbstract
{
    public function transform($price)
    {
        return  [
            'id' => $price['gw_priceshistory_id'],
            "buy" => [
                'price' => $price['buyprice'],
                'quantity' => $price['buyquantity']
            ],
            "sell" => [
                'price' => $price['sellprice'],
                'quantity' => $price['sellquantity'],
            ],
            'date_modified' => $price['date_modified']
        ];

    }
}