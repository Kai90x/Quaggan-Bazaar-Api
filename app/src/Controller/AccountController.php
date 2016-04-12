<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use KaiApp\JsonTransformers\DriverTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedClient;
use KaiApp\RedBO\RedDriver;
use KaiApp\Utils;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class AccountController extends BaseController
{
    private $redClient;
    private $redDriver;

    public function __construct(RedClient $_redClient,RedDriver $_redDriver) {
        $this->redClient = $_redClient;
        $this->redDriver = $_redDriver;
        parent::__construct();
    }

    public function registerClient(Request $request,Response $response, array $args) {
        $name = $request->getParam('name');
        $username = $request->getParam('username');
        $email = $request->getParam('email');
        $phone = $request->getParam('phone');
        $password = $request->getParam('password');

        if (empty($name) || empty($username) || empty($email) || empty($password))
            return $this->response(new Item("name,username, email, password are required parameters",new SimpleTransformer()),$response,400);

        $client = $this->redClient->getByName($name);
        if (!empty($client))
            return $this->response(new Item("Name already exists",new SimpleTransformer()),$response);

        $client = $this->redClient->getByEmail($email);
        if (!empty($client))
            return $this->response(new Item("Email already exists",new SimpleTransformer()),$response);

        $client = $this->redClient->getByUsername($username);
        if (!empty($client))
            return $this->response(new Item("Username already exists",new SimpleTransformer()),$response);

        $id = $this->redClient->add($name,$username,$email,$phone,$password,0,0,0);
        return $this->response(new Item("Client Registered",new SimpleTransformer($id)),$response);
    }

    public function registerDriver(Request $request,Response $response, array $args) {
        $name = $request->getParam('name');
        $username = $request->getParam('username');
        $email = $request->getParam('email');
        $phone = $request->getParam('phone');
        $region = $request->getParam('region');
        $password = $request->getParam('password');

        if (empty($name) || empty($username) || empty($email) || empty($password))
            return $this->response(new Item("name,username, email,region, password are required parameters",new SimpleTransformer()),$response,400);

        $client = $this->redDriver->getByName($name);
        if (!empty($client))
            return $this->response(new Item("Name already exists",new SimpleTransformer()),$response);

        $client = $this->redDriver->getByEmail($email);
        if (!empty($client))
            return $this->response(new Item("Email already exists",new SimpleTransformer()),$response);

        $client = $this->redDriver->getByUsername($username);
        if (!empty($client))
            return $this->response(new Item("Username already exists",new SimpleTransformer()),$response);

        $id = $this->redDriver->add($name,$username,$email,$phone,$password,0,$region,0,0,0);
        return $this->response(new Item("Driver Registered",new SimpleTransformer($id)),$response);
    }


    public function login(Request $request,Response $response, array $args) {
        $username = $request->getParam('username');
        $email = $request->getParam('email');
        $password = $request->getParam('password');
        $type = $request->getParam('type');

        if ((empty($username) && empty($password) ) || (empty($email) && empty($password)) || empty($type))
            return $this->response(new Item("username, email, password, type are required parameters",new SimpleTransformer()),$response,400);

        if ($type == "client") {
            $client = $this->redClient->getByUsernameAndPassword($username, $password, $email);
            if (!empty($client))
                return $this->response(new Item("client", new SimpleTransformer($client->id)), $response);
        } else if ($type == "driver") {
            $driver = $this->redDriver->getByUsernameAndPassword($username, $password, $email);
            if (!empty($driver))
                return $this->response(new Item("driver", new SimpleTransformer($driver->id)), $response);
        }

        return $this->response(new Item("Invalid credentials or invalid type",new SimpleTransformer()),$response,400);
    }

    public function updateLocation(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $type = $request->getParam('type');
        $longtitude = $request->getParam('longtitude');
        $latitude = $request->getParam('latitude');

        if (empty($id) || empty($longtitude) || empty($latitude)|| empty($type))
            return $this->response(new Item("id,longtitude, latitude, type are required parameters",new SimpleTransformer()),$response,400);

        if ($type == "client") {

            $client = $this->redClient->getById($id);
            if (empty($client))
                return $this->response(new Item("Invalid client id",new SimpleTransformer()),$response,400);

            $this->redClient->updateLocation($id,$latitude,$longtitude);
            return $this->response(new Item("Location Updated",new SimpleTransformer()),$response);
        } else if ($type == "driver") {

            $driver = $this->redDriver->getById($id);
            if (empty($driver))
                return $this->response(new Item("Invalid driver id",new SimpleTransformer()),$response,400);

            $this->redDriver->updateLocation($id,$latitude,$longtitude);
            return $this->response(new Item("Location Updated",new SimpleTransformer()),$response);
        }

        return $this->response(new Item("Invalid type provided. Can only be driver or client",new SimpleTransformer()),$response,400);
    }

    public function setOnline(Request $request,Response $response, array $args) {
        $id = $request->getParam('id');
        $type = $request->getParam('type');
        $isOnline = $request->getParam('isOnline') == 1;

        if (empty($id) || empty($type) || empty($isOnline))
            return $this->response(new Item("id,isOnline, type are required parameters",new SimpleTransformer()),$response,400);

        if ($type == "client") {

            $client = $this->redClient->getById($id);
            if (empty($client))
                return $this->response(new Item("Invalid client id",new SimpleTransformer()),$response,400);

            $this->redClient->updateOnline($id,$isOnline);
            return $this->response(new Item("Online status Updated",new SimpleTransformer()),$response);
        } else if ($type == "driver") {

            $driver = $this->redDriver->getById($id);
            if (empty($driver))
                return $this->response(new Item("Invalid driver id",new SimpleTransformer()),$response,400);

            $this->redDriver->updateOnline($id,$isOnline);
            return $this->response(new Item("Location Updated",new SimpleTransformer()),$response);
        }

        return $this->response(new Item("Invalid type provided. Can only be driver or client",new SimpleTransformer()),$response,400);
    }


    public function allDrivers(Request $request,Response $response, array $args) {
        return $this->response(new Collection($this->redDriver->getAll(),new DriverTransformer()),$response);
    }

}