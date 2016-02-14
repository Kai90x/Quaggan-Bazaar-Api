<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/11/16
 * Time: 10:18 PM
 */

namespace KaiApp\RedBO;

use RedBeanPHP\Facade;

class RedAchievements extends RedQuery {

    const ACHIEVEMENTS = 'achievements';

    public function __construct()
    {
        parent::__construct(SELF::ACHIEVEMENTS);
    }

    public function update($id,$name,$description,$requirement,$type,$flags) {
        return parent::update($id,array(
            "name" => $name,
            "description" => $description,
            "requirement" => $requirement,
            "type" => $type,
            "flags" => serialize($flags)
        ));
    }

    public function getByAchievementId($gwAchievementId) {
       return parent::getByOne("gw_achievements_id",$gwAchievementId);
    }
}