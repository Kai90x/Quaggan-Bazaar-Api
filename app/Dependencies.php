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
    '\KaiApp\Controller\EventController'
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