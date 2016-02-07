<?php
namespace RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use Utils\Common;
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedDailyAchivements {

	const DAILYACHIVEMENTS = 'dailyachivements';

    public function AddAchivements($date,$pve1,$pve2,$pve3,$pve4,$pvp1,$pvp2,$pvp3,$pvp4,$wvw1,$wvw2,$wvw3,$wvw4) {
        $achivements = Facade::dispense(SELF::DAILYACHIVEMENTS);
        $achivements->date = $date;
        $achivements->pve1 = $pve1;
        $achivements->pve2 = $pve2;
        $achivements->pve3 = $pve3;
        $achivements->pve4 = $pve4;
        $achivements->pvp1 = $pvp1;
        $achivements->pvp2 = $pvp2;
        $achivements->pvp3 = $pvp3;
        $achivements->pvp4 = $pvp4;
        $achivements->wvw1 = $wvw1;
        $achivements->wvw2 = $wvw2;
        $achivements->wvw3 = $wvw3;
        $achivements->wvw4 = $wvw4;
        $achivements->creation_date = Facade::isoDateTime();

        return Facade::store($achivements);
    }

    public function FindAchivementsByDate($date) {
        $achivements = Facade::find(SELF::DAILYACHIVEMENTS,"date LIKE ? ORDER BY date ASC",array("%$date%"));

        if(empty($achivements)) {
            return null;
        } else {
            return $achivements;
        }
    }

    public function FindAchivementsAfterToday() {
        $achivements = Facade::find(SELF::DAILYACHIVEMENTS,"date = UTC_DATE() or date > UTC_DATE() ORDER BY date ASC");

        if(empty($achivements)) {
            return null;
        } else {
            return $achivements;
        }
    }

    public function GetAllAchivements() {
        $achivements = Facade::findAll(SELF::DAILYACHIVEMENTS,"ORDER BY date DESC");

        if(empty($achivements)) {
            return null;
        } else {
            return $achivements;
        }
    }

	
}