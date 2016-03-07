<?php
namespace KaiApp\Serialization\Importio;
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 3/7/16
 * Time: 2:19 PM
 */
class ImportioError
{
    /**
     *
     * @var string
     */
    public $guid ;

    /**
     *
     * @var string
     */
    public $errorType ;

    /**
     *
     * @var string
     */
    public $error ;

    /**
     *
     * @var string
     */
    public $connectorGuid ;

    /**
     *
     * @var string
     */
    public $pageUrl ;
}