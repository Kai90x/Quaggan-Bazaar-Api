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

class RedRequest extends RedBase{

    const REQUEST = 'request';
    public function __construct()
    {
        parent::__construct(SELF::REQUEST);
    }


    public function add($clientid,$driverid, $droplocation, $hasAccepted, $hasEnded) {
        return parent::add(array(
            "client_id" => $clientid,
            "driver_id" => $driverid,
            "droplocation" => $droplocation,
            "price" => -1,
            "hasAccepted" => $hasAccepted,
            "hasEnded" => $hasEnded,
            "hasCancelled" => false
        ));
    }

    public function update($id,$clientid,$driverid, $droplocation,$price, $hasAccepted, $hasEnded,$hasCancelled) {
        return parent::update($id,array(
            "client_id" => $clientid,
            "driver_id" => $driverid,
            "droplocation" => $droplocation,
            "price" => $price,
            "hasAccepted" => $hasAccepted,
            "hasEnded" => $hasEnded,
            "hasCancelled" => $hasCancelled
        ));
    }

    public function getByClient($clientid)
    {
        return Facade::findAll(SELF::REQUEST,'client_id = ? ORDER BY date_modified DESC', array($clientid));
    }

    public function getByDriver($driverid)
    {
        return Facade::findAll(SELF::REQUEST,'driver_id = ? ORDER BY date_modified DESC', array($driverid));
    }

    public function getDriverNewRequests($driverid)
    {
        return Facade::findAll(SELF::REQUEST,'driver_id = ? AND has_accepted = ? AND has_ended = ? AND has_cancelled = ? ORDER BY date_modified DESC', array($driverid,false,false,false));
    }

    public function deleteById($id) {
        return parent::delete("id", $id);
    }

}