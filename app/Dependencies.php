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

$container['\KaiApp\Controller\PriceController'] = function ($container) {
    $controller = new KaiApp\Controller\PriceController($container->get("RedPrices"),$container->get("RedPricesHistory"));
    $controller->setContainer($container);
    return $controller;
};
