<?php
namespace KaiApp\Serialization;
class Item
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;
	
	/**
     * @var string
     */
    public $icon;
	
	/**
     * @var string
     */
    public $description;
	
	/**
     * @var string
     */
    public $type;
	
	/**
     * @var string
     */
    public $rarity;
	
	/**
     * @var int
     */
    public $level;
	
	/**
     * @var int
     */
    public $vendor_value;
	
	/**
     * @var int
     */
    public $default_skin;
	
	/**
     * @var string[]
     */
    public $flags;
	
	/**
     * @var string[]
     */
    public $game_types;
	
	/**
     * @var string[]
     */
    public $restrictions;
	
	/**
     * @var Detail
     */
    public $details;
}
?>