<?php
namespace KaiApp\Utils;
use Httpful\Request;
use JsonMapper;
use KaiApp\RedBO\RedBase;

/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/25/2015
 * Time: 8:18 AM
 */

class GuildWars2Utils {

    const GUILDWAR2_BASE_URL = "https://api.guildwars2.com/v2/";
    const GUILDWAR2_ITEM = "items";
    const GUILDWAR2_RECIPE = "recipes";
    const GUILDWAR2_PRICES = "commerce/prices";
    const GUILDWAR2_DAILIES = "achievements/daily";
    const GUILDWAR2_ACHIEVEMENTS = "achievements";

    const ORDERBY_DEFAULT = 1;
    const ORDERBY_BUYPRICE = 2;
    const ORDERBY_SELLPRICE = 3;
    const ORDERBY_NAME = 4;
    const ORDERBY_DATEUPDATED = 5;

    const ORDER_ASC = 0;
    const ORDER_DESC = 1;

    public static function getItemsUrl() {
        return  SELF::GUILDWAR2_BASE_URL.SELF::GUILDWAR2_ITEM;
    }

    public static function getRecipeUrl() {
        return  SELF::GUILDWAR2_BASE_URL.SELF::GUILDWAR2_RECIPE;
    }

    public static function getPricesUrl() {
        return  SELF::GUILDWAR2_BASE_URL.SELF::GUILDWAR2_PRICES;
    }

    public static function getDailiesUrl() {
        return  SELF::GUILDWAR2_BASE_URL.SELF::GUILDWAR2_DAILIES;
    }

    public static function getAchievementsUrl() {
        return  SELF::GUILDWAR2_BASE_URL.SELF::GUILDWAR2_ACHIEVEMENTS;
    }

    public static function syncWithGuildWars2($url,RedBase $redBase,$jsonMapClass,$callbackMethod) {
        $ids = Request::get($url)->send();
        $mapper = new JsonMapper();

        $ids = substr($ids, 1);
        $ids = substr($ids, 0, -1);
        $ids = explode(",",$ids);

        $unsyncedArr = array();
        $x = 0;
        foreach($ids as $value) {
            $item = $redBase->getByGwId($value);
            if (empty($item)) {
                $redBase->addGwId($value);
                $unsyncedArr[$x++] = $value;
            } else if (empty($item->date_modified) || (strtotime("+2 day", strtotime($item->date_modified)) < time()))
                $unsyncedArr[$x++] = $value;
        }

        $i = 0;
        $url_fetch = $url."?ids=";
        $concat_ids = "";
        foreach($unsyncedArr as $value) {
            $concat_ids .= $value;

            if ($i != 199) {
                $concat_ids .= ",";
                $i++;
            } else {
                $jsonArr = json_decode(Request::get(($url_fetch . $concat_ids))->send());
                foreach ($jsonArr as $json) {
                    $itemJsonObj = $mapper->map($json,$jsonMapClass);
                    call_user_func($callbackMethod, $itemJsonObj);
                }

                $concat_ids = "";
                $i = 0;
            }
        }

        if ($i > 0) {
            if (substr($concat_ids,-1) == ",")
                $concat_ids = substr($concat_ids, 0, -1);
            $jsonArr = json_decode(file_get_contents( $url_fetch.$concat_ids));

            foreach($jsonArr as $json) {
                $itemJsonObj = $mapper->map($json,$jsonMapClass);
                call_user_func($callbackMethod, $itemJsonObj);
            }
        }
    }

}