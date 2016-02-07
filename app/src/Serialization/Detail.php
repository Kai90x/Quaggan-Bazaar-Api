<?php
namespace KaiApp\Serialization;
class Detail
{
    /**
     * 
     * @var string
     */
    public $type ;
	
	/**
     * 
     * @var string
     */
    public $weight_class ;
	
	/**
     * 
     * @var int
     */
    public $defense ;
	
	/**
     *
     * @var InfusionSlot[]
     */
    public $infusion_slots ;
	
	/**
     * 
     * @var InfixUpgrade
     */
    public $infix_upgrade ;
	
	/**
     *
     * @var int
     */
    public $suffix_item_id ;
	
	/**
     * 
     * @var string
     */
    public $secondary_suffix_item_id ;
	
	/**
     *
     * @var int
     */
    public $size  ;
	
	/**
     * 
     * @var bool
     */
    public $no_sell_or_sort  ;
	
	/**
     *
     * @var string
     */
    public $description   ;
	
	/**
     * 
     * @var int
     */
    public $duration_ms   ;
	
	/**
     *
     * @var string
     */
    public $unlock_type   ;
	
	/**
     * 
     * @var int
     */
    public $color_id   ;
	
	/**
     * 
     * @var int
     */
    public $recipe_id;
	
	
	/**
     * 
     * @var int
     */
    public $charges;
	
	/**
     * 
     * @var string[]
     */
    public $flags;
	
	/**
     * 
     * @var string[]
     */
    public $infusion_upgrade_flags;
	
	/**
     * 
     * @var string
     */
    public $suffix;
	
	
	/**
     * 
     * @var string[]
     */
    public $bonuses;
	
	/**
     * @var string
     */
    public $damage_type;
	
	/**
     * @var int
     */
    public $min_power;
	
	/**
     * 
     * @var int
     */
    public $max_power;
	
	
}
?>