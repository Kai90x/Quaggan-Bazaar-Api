<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 2:34 PM
 */
use Jgut\Slim\Controller\Resolver;
// Define your controllers
$controllers = [
    'KaiApp\Controller\test',
    'KaiApp\Controller\CraftingController'
];

$container = $app->getContainer();

// Register Controllers
foreach (Resolver::resolve($controllers) as $controller => $callback) {
    $container[$controller] = $callback;
}

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('Views');
    $view->addExtension(new \Slim\Views\TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));

    return $view;
};