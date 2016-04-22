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
            "hasCancelled" => false,
            "driver_notified" => false,
            "client_notified" => false
        ));
    }

    public function update($id,$clientid,$driverid, $droplocation,$price, $hasAccepted, $hasEnded,$hasCancelled,$driverNotified,$clientNotified) {
        return parent::update($id,array(
            "client_id" => $clientid,
            "driver_id" => $driverid,
            "droplocation" => $droplocation,
            "price" => $price,
            "hasAccepted" => $hasAccepted,
            "hasEnded" => $hasEnded,
            "hasCancelled" => $hasCancelled,
            "driver_notified" => $driverNotified,
            "client_notified" => $clientNotified
        ));
    }

    public function updateNotification($id,$driverNotified,$clientNotified) {
        return parent::update($id,array(
            "driver_notified" => $driverNotified,
            "client_notified" => $clientNotified
        ));
    }

    public function updatePrice($id,$price) {
        return parent::update($id,array(
            "price" => $price
        ));
    }

    public function updateAccept($id,$hasAccepted) {
        return parent::update($id,array(
            "hasAccepted" => $hasAccepted
        ));
    }

    public function updateCancel($id,$hasCancelled) {
        return parent::update($id,array(
            "hasCancelled" => $hasCancelled
        ));
    }

    public function updateEnded($id,$hasEnded) {
        return parent::update($id,array(
            "hasEnded" => $hasEnded
        ));
    }

    public function getByClient($clientid)
    {
        return Facade::findAll(SELF::REQUEST,'client_id = ? ORDER BY date_modified DESC', array($clientid));
    }

    public function getByClientAndDriver($clientid,$driverid)
    {
        return Facade::findOne(SELF::REQUEST,'client_id = ? AND driver_id = ? AND has_accepted = 0 AND has_ended = 0 AND has_cancelled = 0 ORDER BY date_modified DESC', array($clientid,$driverid));
    }

    public function getByDriver($driverid)
    {
        return Facade::findAll(SELF::REQUEST,'driver_id = ? ORDER BY date_modified DESC', array($driverid));
    }

    public function checkDriverAvailable($driverid)
    {
        return empty(Facade::findOne(SELF::REQUEST,'driver_id = ? AND has_accepted = 1 AND (has_ended = 0 OR has_cancelled = 0) ', array($driverid)));
    }

    public function getDriverNotification($driverid)
    {
        return Facade::findAll(SELF::REQUEST,'driver_id = ? AND has_accepted = 0 AND has_ended = 0 AND has_cancelled = 0 AND price = ? AND driver_notified = 0', array($driverid,-1));
    }

    public function getClientNotification($clientid)
    {
        return Facade::findAll(SELF::REQUEST,'client_id = ? AND has_accepted = 0 AND has_ended = 0 AND has_cancelled = 0 AND price <> ? AND driver_notified = 1 AND client_notified = 0', array($clientid,-1));
    }

    public function getDriverNewRequests($driverid)
    {
        return Facade::findAll(SELF::REQUEST,'driver_id = ? AND has_accepted = ? AND has_ended = ? AND has_cancelled = ? ORDER BY date_modified DESC', array($driverid,false,false,false));
    }

    public function deleteById($id) {
        return parent::delete("id", $id);
    }

}