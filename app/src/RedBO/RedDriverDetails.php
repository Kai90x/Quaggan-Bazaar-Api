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

class RedDriverDetails extends RedBase{

    const DRIVERDETAILS = 'driverdetails';
    public function __construct()
    {
        parent::__construct(SELF::DRIVERDETAILS);
    }


    public function add($userid,$rating,$region,$seats) {
        return parent::add(array(
            "userId" => $userid,
            "rating" => $rating,
            "region" => $region,
            "seats" => $seats
        ));
    }

    public function update($id,$rating,$region) {
        return parent::update($id,array(
            "rating" => $rating,
            "region" => $region
        ));
    }

    public function getByUserId($userid)
    {
        return parent::getByOne("userId",$userid);
    }


    public function deleteById($id) {
        return parent::delete("id", $id);
    }

}