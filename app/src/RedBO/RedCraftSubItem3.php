<?php
namespace KaiApp\RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
class RedCraftSubItem3 extends RedCraftSubItemBase {

    public function __construct()
    {
        parent::__construct('craftsubitem3', 'craftsubitem2Id');
    }
	
}