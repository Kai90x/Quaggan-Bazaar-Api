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
use KaiApp\Serialization\Event\RootObject;
use KaiApp\Utils;
use League\Fractal\Resource\Collection;

class EventController extends BaseController
{

    public function get($request, $response, array $args)
    {
        try {
            $eventJson = (file_get_contents(Utils\ImportioUtils::getEventTimeUrl()));
            $eventJson = str_replace("/_", "_", $eventJson);
            $mapper = new JsonMapper();

            $eventJsonObj = $mapper->map(json_decode($eventJson), new RootObject());

            return $this->complexResponse(new Collection($eventJsonObj->results, new EventTransformer()),$response);
        } catch(\Exception $e) {
            return $this->simpleResponse("An error has occurred",$response,500);
        }
    }

}