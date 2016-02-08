<?php
namespace KaiApp\RedBO;
use RedBeanPHP\Facade;

/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
class RedCrafting extends RedQuery {

	const CRAFTING = 'craftings';

    public function __construct()
    {
        parent::__construct(SELF::CRAFTING);
    }

	public function add($itemid) {
        $craft = array("gwItemId" => $itemid);
        return parent::add($craft);
    }

    public function getById($gw_item_id)
    {
        return parent::getOne(parent::toBeanColumn("gwItemId"),$gw_item_id);
    }

	public function delete($gw_item_id) {
        return parent::delete(parent::toBeanColumn("gwItemId"),$gw_item_id);
	}

    public function getWithDetails($id) {
        $where = $this->addWhereClause($this->type,array(parent::getParamArray("id",$id)));
        $baseQuery = "SELECT craftings.id,craftings.gw_item_id,craftings.date_created,item.icon,item.type,
                      item.rarity,item.level,item.name FROM craftings LEFT JOIN item ON item.gw_item_id = craftings.gw_item_id";
        return Facade::getAll($baseQuery.$where);
    }

}