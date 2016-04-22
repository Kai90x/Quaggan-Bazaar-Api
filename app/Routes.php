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
$app->put('/account/setting','\KaiApp\Controller\AccountController:setOnline');
$app->get('/request','\KaiApp\Controller\RequestController:allRequest');
$app->post('/request','\KaiApp\Controller\RequestController:add');
$app->put('/request','\KaiApp\Controller\RequestController:update');
$app->put('/request/update/accept','\KaiApp\Controller\RequestController:updateAccept');
$app->put('/request/update/cancel','\KaiApp\Controller\RequestController:updateCancelled');
$app->put('/request/update/end','\KaiApp\Controller\RequestController:updateEnded');
$app->get('/request/find','\KaiApp\Controller\RequestController:findRequest');
$app->get('/request/notify/driver','\KaiApp\Controller\RequestController:notifyDriver');
$app->get('/request/notify/client','\KaiApp\Controller\RequestController:notifyClient');
$app->put('/request/notify','\KaiApp\Controller\RequestController:updateNotification');
$app->put('/request/notify/price','\KaiApp\Controller\RequestController:updatePrice');

$app->get('/', function ($request, $response, $args) {
    $args["routes"] = array();
    $args["routes"]["register"] = "/account/register";
    $args["routes"]["login"] = "/account/login";
    $args["routes"]["location"] = "/account/location";
    $args["routes"]["request"] = "/request";
    return $this->view->render($response, 'Index.twig',$args);
})->setName('index');
