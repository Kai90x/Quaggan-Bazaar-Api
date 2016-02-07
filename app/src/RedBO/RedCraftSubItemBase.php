<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 10:18 AM
 */

namespace KaiApp\RedBO;

use RedBeanPHP\Facade;

class RedCraftSubItemBase extends RedQuery
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
        $craft = Facade::findAll($this->type, $this::toBeanColumn($this->foreignColumn).' = ? ', array($id));
        return empty($craft) ? null : $craft;
    }

    public function getAllByCraftIds($ids) {
        $crafts = Facade::findAll($this->type,$this::toBeanColumn($this->foreignColumn) ." IN ( ".Facade::genSlots($ids)." ) ",$ids);
        return empty($crafts) ? null : $crafts;
    }

    public function getAllWithDetails($ids) {
        $table = $this->type;
        $foreignColumn = $this::toBeanColumn($this->foreignColumn);
        $baseQuery = "SELECT ".$table.".id,".$table.".gw_item_id,".$table.".date_created,".$table.".".$foreignColumn.",item.icon,item.type,
                      item.rarity,item.level,item.name FROM ".$table." LEFT JOIN item ON item.gw_item_id = ".$table.".gw_item_id
                      WHERE ".$foreignColumn ." IN ( ".Facade::genSlots($ids)." ) ";
        return Facade::getAll($baseQuery,$ids);
    }

}