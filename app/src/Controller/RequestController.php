<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use KaiApp\JsonTransformers\RequestTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedUser;
use KaiApp\RedBO\RedDriver;
use KaiApp\RedBO\RedRequest;
use KaiApp\Utils;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class RequestController extends BaseController
{
    private $redUser;
    private $redRequest;

    public function __construct(RedUser $_redUser, RedRequest $_redRequest) {
        $this->redUser = $_redUser;
        $this->redRequest = $_redRequest;
        parent::__construct();
    }

    public function allRequest(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');

        if (empty($id) || empty($type))
            return $this->response(new Item("id, type are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getById($id);
        if (empty($user))
            return $this->response(new Item("Invalid user id",new SimpleTransformer()),$response,400);

        if ($user->type == "client") {
            $requests = $this->redRequest->getByClient($id);
            return $this->response(new Collection($requests,new RequestTransformer($this->redClient,$this->redDriver)),$response);
        } else if ($user->type == "driver") {
            $requests = $this->redRequest->getByDriver($id);
            return $this->response(new Collection($requests,new RequestTransformer($this->redClient,$this->redDriver)),$response);
        }

        return $this->response(new Item("Invalid type provided. Can only be driver or client",new SimpleTransformer()),$response,400);
    }

    public function add(Request $request,Response $response, array $args) {
        $clientId = $request->getParam('clientId');
        $driverId = $request->getParam('driverId');
        $dropLocation = $request->getParam('droplocation');

        if (empty($clientId) || empty($driverId) || empty($dropLocation))
            return $this->response(new Item("clientId,driverId, droplocation are required parameters",new SimpleTransformer()),$response,400);


        $driver = $this->redUser->getById($driverId);
        $client = $this->redUser->getById($clientId);
        if (empty($client))
            return $this->response(new Item("Invalid client id",new SimpleTransformer()),$response,400);

        if (empty($driver))
            return $this->response(new Item("Invalid driver id",new SimpleTransformer()),$response,400);

        $id = $this->redRequest->add($clientId,$driverId,$dropLocation,false,false);

        return $this->response(new Item("Request created",new SimpleTransformer($id)),$response);
    }

    public function update(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $hasAccepted = $request->getParam('hasAccepted') == '1';
        $hasCancelled = $request->getParam('hasCancelled') == '1';
        $hasEnded = $request->getParam('hasEnded') == '1';
        $price = $request->getParam('price');

        if (empty($id) || empty($price))
            return $this->response(new Item("id, price are required parameters",new SimpleTransformer()),$response,400);

        $request = $this->redRequest->getById($id);
        if (empty($request))
            return $this->response(new Item("Invalid request id",new SimpleTransformer()),$response,400);

        $id = $this->redRequest->update($id,$request->client_id,$request->driverI_id,$request->drop_location,$price,$hasAccepted,$hasEnded,$hasCancelled);

        return $this->response(new Item("Request updated",new SimpleTransformer($id)),$response);
    }

}