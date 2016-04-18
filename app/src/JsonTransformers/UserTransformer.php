<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 7:55 AM
 */

namespace KaiApp\JsonTransformers;

use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform($user)
    {
        $jsonArr = [
            "name" => $user->name,
            "username" => $user->username,
            "email" => $user->email,
            "phone" => $user->phone,
            "type" => $user->type,
        ];

        if (!empty($user->isOnline)) {
            $jsonArr['currentLatitude'] = $user->currentLatitude;
            $jsonArr['currentLongitude'] = $user->currentLongitude;
        }

        return $jsonArr;
    }

}