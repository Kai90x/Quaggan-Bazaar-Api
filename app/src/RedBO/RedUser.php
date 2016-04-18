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

class RedUser extends RedBase{

	const USER = 'user';
    public function __construct()
    {
        parent::__construct(SELF::USER);
    }


    public function add($name,$username, $email, $phone, $password,$currentLatitude,$currentLongitude,$isOnline,$type ) {
        return parent::add(array(
            "name" => $name,
            "username" => $username,
            "email" => $email,
            "phone" => $phone,
            "password" => $password,
            "currentLatitude" => $currentLatitude,
            "currentLongitude" => $currentLongitude,
            "isOnline" => $isOnline,
            "type" => $type
        ));
    }

    public function update($id,$name,$username, $email, $phone, $password,$currentLatitude,$currentLongitude,$isOnline) {
        return parent::update($id,array(
            "name" => $name,
            "username" => $username,
            "email" => $email,
            "phone" => $phone,
            "password" => $password,
            "currentLatitude" => $currentLatitude,
            "currentLongitude" => $currentLongitude,
            "isOnline" => $isOnline
        ));
    }

    public function updateLocation($id,$currentLatitude,$currentLongitude) {
        return parent::update($id,array(
            "currentLatitude" => $currentLatitude,
            "currentLongitude" => $currentLongitude
        ));
    }

    public function updateOnline($id,$isOnline) {
        return parent::update($id,array(
            "isOnline" => $isOnline
        ));
    }

    public function getByUsername($username)
    {
        return parent::getByOne("username",$username);
    }

    public function getByName($name)
    {
        return parent::getByOne("name",$name);
    }

    public function getByEmail($email)
    {
        return parent::getByOne("email",$email);
    }

    public function getAllDrivers()
    {
        return parent::getByAll("type","driver");
    }

    public function getByUsernameAndPassword($username, $password, $email)
    {
        return Facade::findOne(SELF::USER, '(username = ? and password = ? ) or (email = ? and password = ?)', array($username,$password,$email,$password));
    }

	public function deleteById($id) {
       return parent::delete("id", $id);
	}

}