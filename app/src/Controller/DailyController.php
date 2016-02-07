<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 8/15/15
 * Time: 6:35 PM
 */

namespace KaiApp\Controller;

use Utils\Common;
use RedBO\RedFactory;

class dailyachievements extends BaseController
{

    public function AddDailyAchievementAction()
    {
        //TO-DO: Re-implement
    }

    public function GetAllAchievementsAction()
    {
        $achievements = RedFactory::GetRedDailyAchivements()->GetAllAchivements();

        if (!empty($achievements) ) {
            echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,Common::ConvertBeanToArray($achievements, "achievements")));
        } else {
            echo json_encode(Common::GenerateResponse(Common::STATUS_NOTFOUND,"No achievements found"));
        }
    }

    public function GetAllAchievementsAfterTodayAction()
    {
        $achievements = RedFactory::GetRedDailyAchivements()->FindAchivementsAfterToday();

        if (!empty($achievements) ) {
            echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,Common::ConvertBeanToArray($achievements, "achievements")));
        } else {
            echo json_encode(Common::GenerateResponse(Common::STATUS_NOTFOUND,"No achievements found"));
        }
    }

}