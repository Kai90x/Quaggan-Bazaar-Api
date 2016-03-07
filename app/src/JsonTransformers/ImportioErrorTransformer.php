<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 3/7/16
 * Time: 2:21 PM
 */

namespace KaiApp\JsonTransformers;

use KaiApp\Serialization\Importio\ImportioError;
use League\Fractal\TransformerAbstract;

class ImportioErrorTransformer extends TransformerAbstract
{
    public function transform(ImportioError $error)
    {
        return [
            "type" => $error->errorType,
            "message" => $error->error
        ];
    }

    private function getAchievementsDetails($achievement,$levelmin,$levelmax) {
        return [
            "name" => $achievement->name,
            "description" => $achievement->description,
            "requirement" => $achievement->requirement,
            "type" => $achievement->type,
            "flags" => unserialize($achievement->flags),
            "level" => [
                "min" => $levelmin,
                "max" => $levelmax
            ]
        ];
    }

}