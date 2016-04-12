<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 7:55 AM
 */

namespace KaiApp\JsonTransformers;

use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{

    public function transform($client)
    {
        $jsonArr = [
            "name" => $client->name,
            "username" => $client->username,
            "email" => $client->email,
            "phone" => $client->phone
        ];

        if (!empty($client->isOnline)) {
            $jsonArr['currentLatitude'] = $client->currentLatitude;
            $jsonArr['currentLongitude'] = $client->currentLongitude;
        }

        return $jsonArr;
    }

}