<?php
namespace KaiApp\Utils;
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

    public static function getIds($url) {
        $recipeIds = \Httpful\Request::get($url)->send();

        $recipeIds = substr($recipeIds, 1);
        $recipeIds = substr($recipeIds, 0, -1);

        $ids = explode(",",$recipeIds);
        return $ids;
    }
}