<?php
namespace KaiApp\Serialization;
class Recipe
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var int
     */
    public $output_item_id;

    /**
     * @var int
     */
    public $output_item_count;

    /**
     * @var int
     */
    public $time_to_craft_ms;

    /**
     * @var string[]
     */
    public $disciplines;

    /**
     * @var int
     */
    public $min_rating;

    /**
     * @var string[]
     */
    public $flags;

    /**
     * @var Ingredients[]
     */
    public $ingredients;
}
?>