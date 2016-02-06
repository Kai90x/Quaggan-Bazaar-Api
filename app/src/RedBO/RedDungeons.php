<?php
namespace KaiApp\RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedDungeons {

	const DUNGEONS = 'dungeons';

    public function AddDungeons($dungeon,$path,$goldreward,$tokenreward) {
        $dungeons = Facade::dispense(SELF::DUNGEONS);
        $dungeons->dungeon = $dungeon;
        $dungeons->path = $path;
        $dungeons->goldreward = $goldreward;
        $dungeons->tokenreward = $tokenreward;
        $dungeons->creation_date = Facade::isoDateTime();

        return Facade::store($dungeons);
    }

    public function GetAllDungeons() {
        $news = Facade::find(SELF::DUNGEONS,"Order by dungeon ASC ");
        if (empty($news))
            return null;

        return $news;
    }

	public function DeleteAll() {
		  Facade::wipe( SELF::DUNGEONS );
		  return true;
	}
	
}