<?php
namespace KaiApp\Serialization\Dailies;
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/11/16
 * Time: 10:24 PM
 */
class RootObject
{
    /**
     *
     * @var Pve[]
     */
    public $pve;


    /**
     *
     * @var Pvp[]
     */
    public $pvp;

    /**
     *
     * @var Wvw[]
     */
    public $wvw;

    /**
     *
     * @var object[]
     */
    public $special;
}