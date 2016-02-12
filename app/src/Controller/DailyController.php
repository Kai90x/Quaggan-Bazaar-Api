<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 8/15/15
 * Time: 6:35 PM
 */

namespace KaiApp\Controller;

use RedBeanPHP\RedDaily;
use Slim\Http\Request;
use Slim\Http\Response;

class DailyController extends BaseController
{
    private $redDailies;

    public function __construct(RedDaily $_redDailies) {
        $this->redDailies = $_redDailies;
        parent::__construct();
    }

    public function get(Request $request,Response $response, array $args)
    {
        //TO-DO: Re-implement
    }

}