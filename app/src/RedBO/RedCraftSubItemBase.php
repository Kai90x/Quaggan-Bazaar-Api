<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 10:18 AM
 */

namespace KaiApp\RedBO;

use RedBeanPHP\Facade;

class RedCraftSubItemBase extends RedBase
{
    private $foreignColumn;

    public function __construct($type,$foreignColumn)
    {
        $this->foreignColumn = $foreignColumn;
        parent::__construct($type);
    }

    public function add($craftid,$itemid,$amount) {
        $craft = array($this->foreignColumn => $craftid,
            "gwItemId" => $itemid,
            "amount" => $amount);

        return parent::add($craft);
    }

    public function getAllByCraftId($id)
    {
        $craft = Facade::findAll($this->type, ' ? = ? ', array($this::toBeanColumn($this->foreignColumn), $id));
        return empty($craft) ? null : $craft;
    }

    public function getAllByCraftIds($ids) {
        $crafts = Facade::findAll($this->type,$this::toBeanColumn($this->foreignColumn) ." IN ( ".Facade::genSlots($ids)." ) ",$ids);
        return empty($crafts) ? null : $crafts;
    }

}