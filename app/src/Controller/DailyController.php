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
use KaiApp\RedBO\RedAchievements;
use KaiApp\RedBO\RedDaily;
use KaiApp\Serialization\Dailies\RootObject;
use KaiApp\Utils\GuildWars2Utils;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class DailyController extends BaseController
{
    private $redDailies;
    private $redAchievements;

    public function __construct(RedDaily $_redDailies,RedAchievements $_redAchievements) {
        $this->redDailies = $_redDailies;
        $this->redAchievements = $_redAchievements;
        parent::__construct();
    }

    public function get(Request $request,Response $response, array $args)
    {
        $latestDaily = $this->redDailies->getLatest();

        try {
            if (empty($latestDaily) || date('d.m.Y', strtotime($latestDaily->date_created)) != date('d.m.Y') ) {
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

        $latestDaily->pve_achievement = $this->redAchievements->getByAchievementId($latestDaily->pve_id);
        $latestDaily->pvp_achievement = $this->redAchievements->getByAchievementId($latestDaily->pvp_id);
        $latestDaily->wvw_achievement = $this->redAchievements->getByAchievementId($latestDaily->wvw_id);

        return $this->response(new Item($latestDaily, new DailiesTransformer()),$response);
    }

}