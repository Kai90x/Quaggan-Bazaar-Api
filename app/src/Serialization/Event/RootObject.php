<?php
namespace KaiApp\Serialization\Event;

/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/8/2016
 * Time: 11:46 AM
 */
class RootObject
{
    /**
     *
     * @var int
     */
    public $offset ;

    /**
     *
     * @var Result[]
     */
    public $results ;

    /**
     *
     * @var string[]
     */
    public $cookies ;

    /**
     *
     * @var string
     */
    public $connectorVersionGuid  ;

    /**
     *
     * @var string
     */
    public $connectorGuid  ;

    /**
     *
     * @var string
     */
    public $pageUrl  ;

    /**
     *
     * @var OutputProperty[]
     */
    public $outputProperties  ;

}