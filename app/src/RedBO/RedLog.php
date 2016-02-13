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

class RedLog extends RedBase {

	const LOG = 'log';

    public function __construct()
    {
        parent::__construct(SELF::LOG);
    }

	public function add($file,$class,$method,\Exception $exception) {
        return parent::add(array(
            "file" => $file,
            "class" => $class,
            "method" => $method,
            "exception" => $exception->getTraceAsString()
        ));
    }

}