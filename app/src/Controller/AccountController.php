<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use KaiApp\JsonTransformers\ClientTransformer;
use KaiApp\JsonTransformers\DriverTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\JsonTransformers\UserTransformer;
use KaiApp\RedBO\RedUser;
use KaiApp\RedBO\RedDriverDetails;
use KaiApp\Utils;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class AccountController extends BaseController
{
    private $redUser;
    private $redDriverDetails;

    public function __construct(RedUser $_redUser, RedDriverDetails $_redDriverDetails) {
        $this->redUser = $_redUser;
        $this->redDriverDetails = $_redDriverDetails;
        parent::__construct();
    }

    public function register(Request $request,Response $response, array $args) {
        $name = $request->getParam('name');
        $username = $request->getParam('username');
        $email = $request->getParam('email');
        $phone = $request->getParam('phone');
        $password = $request->getParam('password');
        $type = $request->getParam('type');

        if (empty($name) || empty($username) || empty($email) || empty($password) || empty($type))
            return $this->response(new Item("name,username, email, password,type are required parameters",new SimpleTransformer()),$response,400);

        if ($type != "driver" || $type != "client")
            return $this->response(new Item("Invalid type. Can only be driver or client",new SimpleTransformer()),$response);

        $user = $this->redUser->getByName($name);
        if (!empty($client))
            return $this->response(new Item("Name already exists",new SimpleTransformer()),$response);

        $user = $this->redUser->getByEmail($email);
        if (!empty($client))
            return $this->response(new Item("Email already exists",new SimpleTransformer()),$response);

        $user = $this->redUser->getByUsername($username);
        if (!empty($client))
            return $this->response(new Item("Username already exists",new SimpleTransformer()),$response);

        $id = $this->redUser->add($name,$username,$email,$phone,$password,0,0,0,$type);

        if ($type == "driver")
            $this->redDriverDetails->add($id,0,"",4);

        return $this->response(new Item("User Registered",new SimpleTransformer($id)),$response);
    }

    public function login(Request $request,Response $response, array $args) {
        $username = $request->getParam('username');
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if ((empty($username) && empty($password) ) || (empty($email) && empty($password)))
            return $this->response(new Item("username, email, password are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getByUsernameAndPassword($username, $password, $email);
        if (!empty($user))
                return $this->response(new Item("client", new UserTransformer($user)), $response);

        return $this->response(new Item("Invalid credentials or invalid type",new SimpleTransformer()),$response,400);
    }

    public function updateLocation(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $longtitude = $request->getParam('longtitude');
        $latitude = $request->getParam('latitude');

        if (empty($id) || empty($longtitude) || empty($latitude))
            return $this->response(new Item("id,longtitude, latitude, type are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getById($id);
        if (empty($user))
            return $this->response(new Item("Invalid user id",new SimpleTransformer()),$response,400);
        else {
            $this->redUser->updateLocation($id, $latitude, $longtitude);
            return $this->response(new Item("Location Updated", new SimpleTransformer()), $response);
        }

     }

    public function setOnline(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $isOnline = $request->getParam('isOnline') == 1;

        if (empty($id) || empty($isOnline))
            return $this->response(new Item("id,isOnline, type are required parameters",new SimpleTransformer()),$response,400);

        $user = $this->redUser->getById($id);
        if (empty($user))
            return $this->response(new Item("Invalid user id",new SimpleTransformer()),$response,400);
        else {
            $this->redUser->updateOnline($id,$isOnline);
            return $this->response(new Item("Online status Updated",new SimpleTransformer()),$response);
        }
    }


    public function allDrivers(Request $request,Response $response, array $args) {
        $drivers = $this->redUser->getAllDrivers();
        foreach($drivers as $driver) {
            $detail = $this->redDriverDetails->getById($driver->id);
            if (!empty($detail))
                $driver->details = $detail;
        }
        return $this->response(new Collection($drivers,new DriverTransformer()),$response);
    }

}