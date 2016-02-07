<?php
namespace KaiApp\Utils;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/25/2015
 * Time: 8:18 AM
 */

class Common {

    const IMPORTIO_API = "3f61fb59-2d0d-4f88-8a0e-d9c1bc9f08e1:U7d4a8iqFvUT4Hs8e4r56fL4HOzmbUZX4YXmVX3i1C3J/7mces1o8qqU6wOyLsK1Kz300XyPXc4UODB3DGXRcw==";

    const EVENT_TIME_API = "https://api.import.io/store/data/00110f42-401a-4c1c-9e5b-fdfc751998f8/_query?input/webpage/url=https%3A%2F%2Fwiki.guildwars2.com%2Fwiki%2FWorld_boss&_user=3f61fb59-2d0d-4f88-8a0e-d9c1bc9f08e1&_apikey=";
    const DAILY_ACHIEVEMENT_API = "https://api.import.io/store/data/cfb38011-a280-4649-ba07-80a9f034eff1/_query?input/webpage/url=https%3A%2F%2Fwiki.guildwars2.com%2Fwiki%2FDaily%2FResearch&_user=3f61fb59-2d0d-4f88-8a0e-d9c1bc9f08e1&_apikey=";
    const GUILDWAR2_BASE_URL = "https://api.guildwars2.com/v2/";
    const GUILDWAR2_ITEM = "items";
    const GUILDWAR2_RECIPE = "recipes";
    const GUILDWAR2_PRICES = "commerce/prices";

    public static function GetEventTimerUrl() {
        return  Common::EVENT_TIME_API.Common::IMPORTIO_API;
    }
}