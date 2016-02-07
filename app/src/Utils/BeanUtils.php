<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/7/2016
 * Time: 8:56 AM
 */

namespace KaiApp\Utils;


class BeanUtils
{

    public static function beanToArray($beans){
        $arr = array();
        $i = 0;

        if (!empty($beans)) {
            foreach ($beans as $bean) {
                $arr[$i] = $bean->export();
                $i++;
            }

            return $arr;
        } else
            return $arr;
    }

}