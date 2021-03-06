<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 2:34 PM
 */
// Define your controllers
use Jgut\Slim\Controller\Resolver;

$controllers = [
    '\KaiApp\Controller\EventController',
    '\KaiApp\Controller\EmailController'
];

$container = $app->getContainer();

$container['RedCrafting'] = function ($c) {
    return new KaiApp\RedBO\RedCrafting();
};
$container['RedCraftingSubItem1'] = function ($c) {
    return new KaiApp\RedBO\RedCraftSubItem1();
};
$container['RedCraftingSubItem2'] = function ($c) {
    return new KaiApp\RedBO\RedCraftSubItem2();
};
$container['RedCraftingSubItem3'] = function ($c) {
    return new KaiApp\RedBO\RedCraftSubItem3();
};
$container['RedCraftingSubItem4'] = function ($c) {
    return new KaiApp\RedBO\RedCraftSubItem4();
};

$container['RedNews'] = function ($c) {
    return new KaiApp\RedBO\RedNews();
};

$container['RedDungeons'] = function ($c) {
    return new KaiApp\RedBO\RedDungeons();
};

$container['RedRecipe'] = function ($c) {
    return new KaiApp\RedBO\RedRecipe();
};

$container['RedIngredients'] = function ($c) {
    return new KaiApp\RedBO\RedIngredients();
};

$container['RedDailies'] = function ($c) {
    return new KaiApp\RedBO\RedDaily();
};
$container['RedAchievements'] = function ($c) {
    return new KaiApp\RedBO\RedAchievements();
};
$container['RedAchievementsBit'] = function ($c) {
    return new KaiApp\RedBO\RedAchievementsBit();
};
$container['RedAchievementsTier'] = function ($c) {
    return new KaiApp\RedBO\RedAchievementsTier();
};

$container['RedItem'] = function ($c) {
    return new KaiApp\RedBO\RedItem();
};
$container['RedItemDetails'] = function ($c) {
    return new KaiApp\RedBO\RedItemDetails();
};
$container['RedItemDetailsInfixUpgrade'] = function ($c) {
    return new KaiApp\RedBO\RedItemDetailsInfixUpgrade();
};
$container['RedInfixAttributes'] = function ($c) {
    return new KaiApp\RedBO\RedInfixAttributes();
};
$container['RedInfusionSlot'] = function ($c) {
    return new KaiApp\RedBO\RedInfusionSlot();
};
$container['RedInfixBuff'] = function ($c) {
    return new KaiApp\RedBO\RedInfixBuff();
};
$container['RedPrices'] = function ($c) {
    return new KaiApp\RedBO\RedPrices();
};
$container['RedPricesHistory'] = function ($c) {
    return new KaiApp\RedBO\RedPricesHistory();
};


// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('Views');
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};

foreach (Resolver::resolve($controllers) as $controller => $callback) {
    $container[$controller] = $callback;
}

$container['\KaiApp\Controller\CraftingController'] = function ($container) {
    $controller = new KaiApp\Controller\CraftingController($container->get("RedCrafting"),$container->get("RedCraftingSubItem1"),$container->get("RedCraftingSubItem2"),
        $container->get("RedCraftingSubItem3"),$container->get("RedCraftingSubItem4"));
    $controller->setContainer($container);
    return $controller;
};

$container['\KaiApp\Controller\NewsController'] = function ($container) {
    $controller = new KaiApp\Controller\NewsController($container->get("RedNews"));
    $controller->setContainer($container);
    return $controller;
};

$container['\KaiApp\Controller\DungeonsController'] = function ($container) {
    $controller = new KaiApp\Controller\DungeonsController($container->get("RedDungeons"));
    $controller->setContainer($container);
    return $controller;
};

$container['\KaiApp\Controller\DailyController'] = function ($container) {
    $controller = new KaiApp\Controller\DailyController($container->get("RedDailies"),$container->get("RedAchievements"));
    $controller->setContainer($container);
    return $controller;
};


$container['\KaiApp\Controller\RecipeController'] = function ($container) {
    $controller = new KaiApp\Controller\RecipeController($container->get("RedRecipe"),$container->get("RedIngredients"));
    $controller->setContainer($container);
    return $controller;
};

$container['\KaiApp\Controller\AchievementController'] = function ($container) {
    $controller = new KaiApp\Controller\AchievementController($container->get("RedAchievements"),$container->get("RedAchievementsTier")
        ,$container->get("RedAchievementsBit"));
    $controller->setContainer($container);
    return $controller;
};

$container['\KaiApp\Controller\ItemsController'] = function ($container) {
    $controller = new KaiApp\Controller\ItemsController(
        $container->get("RedItem"),
        $container->get("RedItemDetails"),
        $container->get("RedItemDetailsInfixUpgrade"),
        $container->get("RedInfusionSlot"),
        $container->get("RedInfixBuff"),
        $container->get("RedInfixAttributes"),
        $container->get("RedPrices"));
    $controller->setContainer($container);
    return $controller;
};

$container['\KaiApp\Controller\PriceController'] = function ($container) {
    $controller = new KaiApp\Controller\PriceController($container->get("RedPrices"),$container->get("RedPricesHistory"));
    $controller->setContainer($container);
    return $controller;
};
