<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 2:21 PM
 */
$app->post('/news','\KaiApp\Controller\NewsController:sync');
$app->get('/news','\KaiApp\Controller\NewsController:get');
$app->get('/legendaries','\KaiApp\Controller\CraftingController:all');
$app->get('/legendaries/{id}','\KaiApp\Controller\CraftingController:get');
$app->put('/legendaries','\KaiApp\Controller\CraftingController:reset');
$app->get('/events','\KaiApp\Controller\EventController:get');
$app->put('/dungeons','\KaiApp\Controller\DungeonsController:refresh');
$app->get('/dungeons','\KaiApp\Controller\DungeonsController:all');
$app->post('/email','\KaiApp\Controller\EmailController:send');
$app->put('/recipes','\KaiApp\Controller\RecipeController:sync');
$app->get('/recipes/{id}','\KaiApp\Controller\RecipeController:getByItemId');
$app->get('/dailies','\KaiApp\Controller\DailyController:get');
$app->put('/achievements','\KaiApp\Controller\AchievementController:sync');

$app->get('/', function ($request, $response, $args) {
    $args["routes"] = array();
    $args["routes"]["legendaries"] = "/legendaries";
    return $this->view->render($response, 'Index.twig',$args);
})->setName('index');
