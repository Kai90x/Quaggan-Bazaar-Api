<?php
namespace KaiApp\RedBO;

/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
class RedCrafting extends RedBase {

	const CRAFTING = 'craftings';

    public function __construct()
    {
        parent::__construct('craftings');
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

}