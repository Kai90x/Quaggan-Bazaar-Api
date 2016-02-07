<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 6/13/15
 * Time: 3:29 PM
 */

namespace RedBO;

class RedFactory {

    function __construct() {
    }

    public static function GetRedItem() {
        return new RedItem();
    }

    public static function GetRedIngredients() {
        return new RedIngredients();
    }

    public static function GetRedRecipe() {
        return new RedRecipe();
    }

    public static function GetRedItemDetails() {
        return new RedItemDetails();
    }

    public static function GetRedInfixUpgrade() {
        return new RedItemDetailsInfixUpgrade();
    }

    public static function GetRedInfixAttributes() {
        return new RedInfixAttributes();
    }

    public static function GetRedInfixBuff() {
        return new RedInfixBuff();
    }

    public static function GetRedInfusionSlot() {
        return new RedInfusionSlot();
    }

    public static function GetRedNews() {
        return new RedNews();
    }
	
	public static function GetRedGuildPrices() {
        return new RedGuildPrices();
    }
	
	public static function GetRedGuildPricesHistory() {
        return new RedGuildPricesHistory();
    }

    public static function GetRedDailyAchivements() {
        return new RedDailyAchivements();
    }

    public static function GetRedQuery() {
        return new RedQuery();
    }

    public static function GetRedGuildItem() {
        return new RedGuildItem();
    }

    public static function GetRedDungeons() {
        return new RedDungeons();
    }

    public static function GetRedCrafting() {
        return new RedCrafting();
    }

    public static function GetRedCraftSubItem1() {
        return new RedCraftSubItem1();
    }

    public static function GetRedCraftSubItem2() {
        return new RedCraftSubItem2();
    }

    public static function GetRedCraftSubItem3() {
        return new RedCraftSubItem3();
    }

    public static function GetRedCraftSubItem4() {
        return new RedCraftSubItem4();
    }
}