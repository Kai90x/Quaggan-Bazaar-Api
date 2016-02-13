<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/11/16
 * Time: 10:18 PM
 */

namespace KaiApp\RedBO;

use RedBeanPHP\Facade;

class RedAchievementsTier extends RedQuery {

    const ACHIEVEMENTS_TIER = 'achievementstier';

    public function __construct()
    {
        parent::__construct(SELF::ACHIEVEMENTS_TIER);
    }


    public function add($achievementid,$count,$points)
    {
        return parent::add(array(
            "achievements_id" => $achievementid,
            "count" => $count,
            "points" => $points
        ));
    }

    public function getByAchievementId($id)
    {
        return parent::getByAll("achievements_id",$id);
    }

    public function deleteByAchievementId($id)
    {
        return parent::delete("achievements_id",$id);
    }

}