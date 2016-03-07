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
use KaiApp\JsonTransformers\ImportioErrorTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\Serialization\Event\RootObject;
use KaiApp\Serialization\Importio\ImportioError;
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

            //Check for importio error
            $eventJsonObj = $mapper->map(json_decode($jsonResponse), new RootObject());
            if (empty($eventJsonObj)) {
                $importioErrorJson = $mapper->map(json_decode($jsonResponse), new ImportioError());
                return $this->response(new Item($importioErrorJson, new ImportioErrorTransformer()),$response,400);
            }

            return $this->response(new Collection($eventJsonObj->results, new EventTransformer()),$response);
        } catch(\Exception $e) {
            return $this->response(new Item("An error has occurred", new SimpleTransformer()),$response,500);
        }
    }

}