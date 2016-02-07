<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 3:11 PM
 */

namespace KaiApp\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

class test extends BaseController
{
    public function dispatch(Request $request, Response $response, $args)
    {
        return $this->simpleResponse("THIS WORKS??",$response,200);
    }
}