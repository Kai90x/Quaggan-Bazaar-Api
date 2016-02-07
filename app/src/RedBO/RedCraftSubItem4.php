<?php
namespace RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedCraftSubItem4 extends RedBeanPHP\RedCraftSubItemBase {

    public function __construct()
    {
        parent::__construct('craftsubitem4', 'craftsubitem3Id');
    }

}