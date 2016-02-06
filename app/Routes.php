<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 2:21 PM
 */
$app->get('/legendaries','\KaiApp\Controller\CraftingController:all');
$app->get('/legendaries/:id','\KaiApp\Controller\CraftingController:get');
$app->put('/legendaries','\KaiApp\Controller\CraftingController:reset');
$app->get('/test', 'KaiApp\Controller\test:dispatch');

$app->get('/', function ($request, $response, $args) {
    $args["routes"] = array();
    $args["routes"]["legendary"] = "/legendary";
    return $this->view->render($response, 'Index.twig',$args);
})->setName('index');
