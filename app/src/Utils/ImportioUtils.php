<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/8/2016
 * Time: 11:38 AM
 */

namespace KaiApp\Utils;


class ImportioUtils
{
    const IMPORTIO_API = "3f61fb59-2d0d-4f88-8a0e-d9c1bc9f08e1:U7d4a8iqFvUT4Hs8e4r56fL4HOzmbUZX4YXmVX3i1C3J/7mces1o8qqU6wOyLsK1Kz300XyPXc4UODB3DGXRcw==";
    const EVENT_TIME_API = "https://api.import.io/store/data/00110f42-401a-4c1c-9e5b-fdfc751998f8/_query?input/webpage/url=https%3A%2F%2Fwiki.guildwars2.com%2Fwiki%2FWorld_boss&_user=3f61fb59-2d0d-4f88-8a0e-d9c1bc9f08e1&_apikey=";

    public static function getEventTimeUrl() {
        return  Common::EVENT_TIME_API.Common::IMPORTIO_API;
    }
}