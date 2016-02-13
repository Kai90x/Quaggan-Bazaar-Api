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
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $requirement;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var object[]
     */
    public $flags;

    /**
     *
     * @var Tier[]
     */
    public $tiers;

    /**
     *
     * @var Bit[]
     */
    public $bits;
}