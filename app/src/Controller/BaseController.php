<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 10:53 AM
 */

namespace KaiApp\Controller;

use Jgut\Slim\Controller\Base as JGutBaseController;
use League\Fractal\Manager;
use League\Fractal\Serializer\DataArraySerializer;
use Slim\Http\Response;

class BaseController extends JGutBaseController
{
    protected $fractal;

    public function __construct()
    {
        $this->fractal = new Manager();
        $this->fractal->setSerializer(new DataArraySerializer());
    }

    protected function response($object,Response $response,$status = 200) {
        $response = $response->write($this->fractal->createData($object)->toJson())
            ->withStatus($status)
            ->withHeader("Content-Type","application/json;charset=utf-8");
        return $response;
    }

    public function getMissingParams($params) {
        $missingParams = array();
        foreach($params as $key => $value) {
            if (empty($value))
                array_push($missingParams,$key);
        }

        return $missingParams;
    }

}