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
    '\KaiApp\Controller\AccountController',
    '\KaiApp\Controller\RequestController'
];

$container = $app->getContainer();
$container['RedUser'] = function ($c) {
    return new KaiApp\RedBO\RedUser();
};
$container['RedDriverDetails'] = function ($c) {
    return new KaiApp\RedBO\RedDriverDetails();
};
$container['RedRequest'] = function ($c) {
    return new KaiApp\RedBO\RedRequest();
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

$container['\KaiApp\Controller\AccountController'] = function ($container) {
    $controller = new KaiApp\Controller\AccountController($container->get("RedUser"),$container->get("RedDriverDetails"),$container->get("RedRequest"));
    $controller->setContainer($container);
    return $controller;
};
$container['\KaiApp\Controller\RequestController'] = function ($container) {
    $controller = new KaiApp\Controller\RequestController($container->get("RedUser"),$container->get("RedRequest"));
    $controller->setContainer($container);
    return $controller;
};
