<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 7:55 AM
 */

namespace KaiApp\JsonTransformers;

use League\Fractal\TransformerAbstract;

class DriverTransformer extends TransformerAbstract
{

    public function transform($driver)
    {
        $jsonArr = [
            "name" => $driver->name,
            "username" => $driver->username,
            "email" => $driver->email,
            "phone" => $driver->phone,
            "rating" => $driver->rating,
            "region" => $driver->region
        ];

        if (!empty($driver->isOnline)) {
            $jsonArr['currentLatitude'] = $driver->currentLatitude;
            $jsonArr['currentLongitude'] = $driver->currentLongitude;
        }

        return $jsonArr;
    }

}