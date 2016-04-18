<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 2:21 PM
 */
$app->post('/account/register','\KaiApp\Controller\AccountController:register');
$app->get('/account/driver','\KaiApp\Controller\AccountController:allDrivers');
$app->post('/account/login','\KaiApp\Controller\AccountController:login');
$app->put('/account/location','\KaiApp\Controller\AccountController:updateLocation');
$app->get('/request','\KaiApp\Controller\RequestController:allRequest');
$app->post('/request','\KaiApp\Controller\RequestController:add');
$app->put('/request','\KaiApp\Controller\RequestController:update');

$app->get('/', function ($request, $response, $args) {
    $args["routes"] = array();
    $args["routes"]["register"] = "/account/register";
    $args["routes"]["login"] = "/account/login";
    $args["routes"]["location"] = "/account/location";
    $args["routes"]["request"] = "/request";
    return $this->view->render($response, 'Index.twig',$args);
})->setName('index');
