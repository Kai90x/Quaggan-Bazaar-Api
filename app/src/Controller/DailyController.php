<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 8/15/15
 * Time: 6:35 PM
 */

namespace KaiApp\Controller;

use JsonMapper;
use KaiApp\JsonTransformers\DailiesTransformer;
use KaiApp\RedBO\RedDaily;
use KaiApp\Serialization\Dailies\RootObject;
use KaiApp\Utils\GuildWars2Utils;
use League\Fractal\Resource\Item;
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
        $latestDaily = $this->redDailies->getLatest();

        try {
            if (empty($latestDaily) || date('d.m.Y', strtotime($latestDaily->date_created) != date('d.m.Y') ) /*date check to add*/) {
                $jsonResponse = \Httpful\Request::get(GuildWars2Utils::getDailiesUrl())->send();
                $mapper = new JsonMapper();

                $dailiesJsonObj = $mapper->map(json_decode($jsonResponse), new RootObject());
                $this->redDailies->add(
                    $dailiesJsonObj->pve[0]->id,
                    $dailiesJsonObj->pvp[0]->id,
                    $dailiesJsonObj->wvw[0]->id,
                    $dailiesJsonObj->pve[0]->level->min,
                    $dailiesJsonObj->pve[0]->level->max,
                    $dailiesJsonObj->pvp[0]->level->min,
                    $dailiesJsonObj->pvp[0]->level->max,
                    $dailiesJsonObj->wvw[0]->level->min,
                    $dailiesJsonObj->wvw[0]->level->max,
                    serialize($dailiesJsonObj->special)
                );

                $latestDaily = $this->redDailies->getLatest();
            }
        } catch (\Exception $e) {
            $this->log(__FILE__,get_class($this),__FUNCTION__,$e);
        }

        return $this->response(new Item($latestDaily, new DailiesTransformer()),$response);
    }

}