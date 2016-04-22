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

        if (empty($id))
            return $this->response(new Item("id are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getById($id);
        if (empty($user))
            return $this->response(new Item("Invalid user id",new SimpleTransformer()),$response,400);

        if ($user->type == "client") {
            $requests = $this->redRequest->getByClient($id);
            return $this->response(new Collection($requests,new RequestTransformer($this->redUser)),$response);
        } else if ($user->type == "driver") {
            $requests = $this->redRequest->getByDriver($id);
            return $this->response(new Collection($requests,new RequestTransformer($this->redUser)),$response);
        }

        return $this->response(new Item("Invalid type provided. Can only be driver or client",new SimpleTransformer()),$response,400);
    }

    public function findRequest(Request $request,Response $response, array $args) {
        $clientid = $request->getParam('clientid');
        $driverid = $request->getParam('driverid');

        if (empty($clientid) || empty($driverid))
            return $this->response(new Item("clientid, driverid are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getById($clientid);
        if (empty($user))
            return $this->response(new Item("Invalid user id",new SimpleTransformer()),$response,400);

        $driver = $this->redUser->getById($driverid);
        if (empty($driver))
            return $this->response(new Item("Invalid driver id",new SimpleTransformer()),$response,400);

        $requests = $this->redRequest->getByClientAndDriver($clientid,$driverid);
        if (empty($requests))
            return $this->response(new Item("None found",new SimpleTransformer()),$response,400);

        return $this->response(new Item($requests,new RequestTransformer($this->redUser)),$response);
    }

    public function notifyDriver(Request $request,Response $response, array $args) {
        $driverid = $request->getParam('driverid');

        if (empty($driverid))
            return $this->response(new Item("clientid are required parameters",new SimpleTransformer()),$response,400);

        $driver = $this->redUser->getById($driverid);
        if (empty($driver))
            return $this->response(new Item("Invalid driver id",new SimpleTransformer()),$response,400);


        $requests = $this->redRequest->getDriverNotification($driverid);
        return $this->response(new Collection($requests,new RequestTransformer($this->redUser)),$response);
    }

    public function updateNotification(Request $request,Response $response, array $args) {
        $userid = $request->getParam('userid');
        $requestid = $request->getParam('id');

        if (empty($userid) || empty($requestid))
            return $this->response(new Item("userid, requestid are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getById($userid);
        if (empty($user))
            return $this->response(new Item("Invalid user id",new SimpleTransformer()),$response,400);


        $request = $this->redRequest->getById($requestid);

        if (!empty($request)) {
            if ($user->type == "client")
                $this->redRequest->updateNotification($request->id, $request->driver_notified, true);
            else
                $this->redRequest->updateNotification($request->id, true, $request->client_notified);
        } else {
            return $this->response(new Item("Invalid request id",new SimpleTransformer()),$response,400);
        }

        return $this->response(new Collection($this->redRequest->getById($requestid),new RequestTransformer($this->redUser)),$response);
    }

    public function updatePrice(Request $request,Response $response, array $args) {
        $requestid = $request->getParam('id');
        $price = $request->getParam('price');

        if (empty($requestid) || empty($price))
            return $this->response(new Item("userid, price are required parameters",new SimpleTransformer()),$response,400);

        $request = $this->redRequest->getById($requestid);

        if (!empty($request)) {
            $this->redRequest->updatePrice($request->id, $price);
        } else {
            return $this->response(new Item("Invalid request id",new SimpleTransformer()),$response,400);
        }

        return $this->response(new Collection($this->redRequest->getById($requestid),new RequestTransformer($this->redUser)),$response);
    }

    public function notifyClient(Request $request,Response $response, array $args) {
        $clientid = $request->getParam('clientid');

        if (empty($clientid))
            return $this->response(new Item("clientid are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getById($clientid);
        if (empty($user))
            return $this->response(new Item("Invalid user id",new SimpleTransformer()),$response,400);

        $requests = $this->redRequest->getClientNotification($clientid);
        return $this->response(new Collection($requests,new RequestTransformer($this->redUser)),$response);
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

        $id = $this->redRequest->update($id,$request->client_id,$request->driverI_id,$request->drop_location,$price,$hasAccepted,$hasEnded,$hasCancelled,$request->driver_notified,$request->client_notified);

        return $this->response(new Item("Request updated",new SimpleTransformer($id)),$response);
    }

    public function updateAccept(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $hasAccepted = $request->getParam('hasAccepted') == '1';

        if (empty($id) || empty($hasAccepted))
            return $this->response(new Item("id, hasAccepted are required parameters",new SimpleTransformer()),$response,400);

        $request = $this->redRequest->getById($id);
        if (empty($request))
            return $this->response(new Item("Invalid request id",new SimpleTransformer()),$response,400);

        $id = $this->redRequest->updateAccept($id,$hasAccepted);

        return $this->response(new Item("Request updated",new SimpleTransformer($id)),$response);
    }

    public function updateCancelled(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $hasCancelled = $request->getParam('hasCancelled') == '1';

        if (empty($id) || empty($hasCancelled))
            return $this->response(new Item("id, hasCancelled are required parameters",new SimpleTransformer()),$response,400);

        $request = $this->redRequest->getById($id);
        if (empty($request))
            return $this->response(new Item("Invalid request id",new SimpleTransformer()),$response,400);

        $id = $this->redRequest->updateCancel($id,$hasCancelled);

        return $this->response(new Item("Request updated",new SimpleTransformer($id)),$response);
    }

    public function updateEnded(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $hasEnded = $request->getParam('hasEnded') == '1';

        if (empty($id) || empty($hasEnded))
            return $this->response(new Item("id, hasAccepted are required parameters",new SimpleTransformer()),$response,400);

        $request = $this->redRequest->getById($id);
        if (empty($request))
            return $this->response(new Item("Invalid request id",new SimpleTransformer()),$response,400);

        $id = $this->redRequest->updateEnded($id,$hasEnded);

        return $this->response(new Item("Request updated",new SimpleTransformer($id)),$response);
    }

}