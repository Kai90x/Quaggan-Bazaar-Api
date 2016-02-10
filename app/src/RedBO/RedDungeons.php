<?php
namespace KaiApp\RedBO;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedDungeons extends RedBase{

	const DUNGEONS = 'dungeons';

    public function __construct()
    {
        parent::__construct(SELF::DUNGEONS);
    }

    public function add($dungeon,$path,$goldreward,$tokenreward) {
        return parent::add(array(
            "dungeon" => $dungeon,
            "path" => $path,
            "goldreward" => $goldreward,
            "tokenreward" => $tokenreward
        ));
    }

    public function getAll() {
        return parent::getAll("dungeon");
    }
	
}