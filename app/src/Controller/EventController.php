<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use JsonMapper;
use KaiApp\JsonTransformers\EventTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\Serialization\Event\RootObject;
use KaiApp\Utils;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class EventController extends BaseController
{

    public function get($request, $response, array $args)
    {
        try {
            $jsonResponse = \Httpful\Request::get(Utils\ImportioUtils::getEventTimeUrl())->send();
            $jsonResponse = str_replace("/_", "_", $jsonResponse);
            $mapper = new JsonMapper();

            $eventJsonObj = $mapper->map(json_decode($jsonResponse), new RootObject());

            return $this->response(new Collection($eventJsonObj->results, new EventTransformer()),$response);
        } catch(\Exception $e) {
            return $this->response(new Item("An error has occurred", new SimpleTransformer()),$response,500);
        }
    }

}