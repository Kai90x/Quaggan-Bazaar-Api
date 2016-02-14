<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 8:06 AM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class DailiesTransformer extends TransformerAbstract
{
    public function transform($daily)
    {
        if (!empty($daily->pve_achievement))
            $dailyJson["pvp"] = $this->getAchievementsDetails($daily->pve_achievement,$daily->level1_min,$daily->level1_max);

        if (!empty($daily->pvp_achievement))
            $dailyJson["pve"] = $this->getAchievementsDetails($daily->pvp_achievement,$daily->level2_min,$daily->level2_max);

        if (!empty($daily->wvw_achievement))
            $dailyJson["wvw"] = $this->getAchievementsDetails($daily->wvw_achievement,$daily->level3_min,$daily->level3_max);

        $dailyJson["special"] = unserialize($daily->tokenreward);
        $dailyJson["date"] = $daily->date_created;

        return $dailyJson;
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