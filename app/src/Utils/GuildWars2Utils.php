<?php
namespace KaiApp\Utils;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/25/2015
 * Time: 8:18 AM
 */

class GuildWars2Util {

    const GUILDWAR2_BASE_URL = "https://api.guildwars2.com/v2/";
    const GUILDWAR2_ITEM = "items";
    const GUILDWAR2_RECIPE = "recipes";
    const GUILDWAR2_PRICES = "commerce/prices";
    const GUILDWAR2_DAILIES = "achievements/daily";

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
}