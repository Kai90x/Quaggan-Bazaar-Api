<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/11/16
 * Time: 10:18 PM
 */

namespace KaiApp\RedBO;

use RedBeanPHP\Facade;

class RedDaily extends RedQuery {

    const DAILY = 'dailies';

    public function __construct()
    {
        parent::__construct(SELF::DAILY);
    }

    public function add($pvp_id,$pve_id,$wvw_id,$level1_min,$level1_max,$level2_min,$level2_max,$level3_min,$level3_max,$special) {
        return parent::add(array(
            "pvp_id" => $pvp_id,
            "pve_id" => $pve_id,
            "wvw_id" => $wvw_id,
            "level1_min" => $level1_min,
            "level1_max" => $level1_max,
            "level2_min" => $level2_min,
            "level2_max" => $level2_max,
            "level3_min" => $level3_min,
            "level3_max" => $level3_max,
            "special" => $special,
        ));
    }

    public function getLatest()
    {
        return Facade::findOne($this->type,' ORDER BY '. $this->toBeanColumn($this->dateCreated).' DESC ');
    }

}