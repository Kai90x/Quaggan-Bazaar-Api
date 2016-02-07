<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/7/2016
 * Time: 9:01 AM
 */

namespace KaiApp\Utils;


class SanitizationUtils
{
    public static function StripHTMLCharacter($str) {
        return trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($str))))));;
    }
}