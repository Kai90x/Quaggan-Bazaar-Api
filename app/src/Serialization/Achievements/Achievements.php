<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 8/15/15
 * Time: 6:45 PM
 */
namespace KaiApp\Serialization\Achievements;

class Achievements
{
    /**
     *
     * @var int
     */
    public $offset;

    /**
     *
     * @var Result[]
     */
    public $results;

    /**
     *
     * @var Object[]
     */
    public $cookies;

    /**
     *
     * @var string
     */
    public $connectorVersionGuid;

    /**
     *
     * @var string
     */
    public $connectorGuid;

    /**
     *
     * @var string
     */
    public $pageUrl;

    /**
     *
     * @var OutputProperty[]
     */
    public $outputProperties;
}