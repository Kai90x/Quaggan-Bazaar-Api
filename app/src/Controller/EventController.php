<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use Utils\Common;

class event extends BaseController
{

    public function GetEventTimerAction()
    {
        $eventJson =  (file_get_contents( Common::GetEventTimerUrl()));
        echo str_replace("/_","_",$eventJson);
    }

}