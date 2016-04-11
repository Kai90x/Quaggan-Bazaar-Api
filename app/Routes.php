<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 2:21 PM
 */
$app->put('/news','\KaiApp\Controller\NewsController:sync');

$app->get('/', function ($request, $response, $args) {
    $args["routes"] = array();
    $args["routes"]["legendaries"] = "/legendaries";
    return $this->view->render($response, 'Index.twig',$args);
})->setName('index');
