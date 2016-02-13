<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/11/16
 * Time: 10:18 PM
 */

namespace KaiApp\RedBO;

use RedBeanPHP\Facade;

class RedAchievementsBit extends RedQuery {

    const ACHIEVEMENTS_BIT = 'achievementsbit';

    public function __construct()
    {
        parent::__construct(SELF::ACHIEVEMENTS_BIT);
    }

    public function add($achievementid,$type,$text)
    {
        return parent::add(array(
            "achievements_id" => $achievementid,
            "type" => $type,
            "text" => $text
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