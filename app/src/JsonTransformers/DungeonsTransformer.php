<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 8:06 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class DungeonsTransformer extends TransformerAbstract
{
    public function transform($dungeon)
    {
        return [
            "dungeon" => $dungeon->dungeon,
            "path" => $dungeon->path,
            "goldreward" => $dungeon->goldreward,
            "tokenreward" => $dungeon->tokenreward
        ];
    }

}