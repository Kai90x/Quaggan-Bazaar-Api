<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/13/2016
 * Time: 2:03 PM
 */

namespace KaiApp\Controller;


use JsonMapper;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedAchievements;
use KaiApp\RedBO\RedAchievementsBit;
use KaiApp\RedBO\RedAchievementsTier;
use KaiApp\Serialization\Achievements\Achievements;
use KaiApp\Utils;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class AchievementController extends BaseController
{
    private $redAchievements;
    private $redAchievementsTier;
    private $redAchievementsBit;

    public function __construct(RedAchievements $_redAchievements,RedAchievementsTier $_redAchievementsTier,
                                RedAchievementsBit $_redAchievementsBit) {
        $this->redAchievements = $_redAchievements;
        $this->redAchievementsTier = $_redAchievementsTier;
        $this->redAchievementsBit = $_redAchievementsBit;
        parent::__construct();
    }

    public function sync(Request $request,Response $response, array $args) {
        $mapper = new JsonMapper();

        $recipeArr = Utils\GuildWars2Utils::getIds(Utils\GuildWars2Utils::getAchievementsUrl());
        $unsyncedAchievementsArr = array();

        $x = 0;
        foreach($recipeArr as $value) {
            $achievement = $this->redAchievements->getByAchievementsId($value);
            if (empty($recipe)) {
                $this->redAchievements->addId($value);
                $unsyncedAchievementsArr[$x] = $value;
                $x++;
            } else {
                //If found, check last time recipe was synced
                if (empty($achievement->date_modified) || (strtotime("+2 day", strtotime($achievement->date_modified)) < time())) {
                    //put in Update item array
                    $unsyncedAchievementsArr[$x] = $value;
                    $x++;
                }
            }
        }


        $i = 0;
        $url_achievement_fetch = Utils\GuildWars2Utils::getAchievementsUrl()."?ids=";
        $concat_ids = "";
        foreach($unsyncedAchievementsArr as $value) {
            $concat_ids .= $value;
            if ($i != 199) {
                $concat_ids .= ",";
                $i++;
            } else {
                $jsonArr = json_decode(\Httpful\Request::get($url_achievement_fetch.$concat_ids)->send());
                foreach ($jsonArr as $json) {
                    $achievementJsonObj = $mapper->map($json, new Achievements());
                    $this->update($achievementJsonObj);
                }

                $concat_ids = "";
                $i = 0;
            }
        }

        if ($i > 0) {
            if (substr($concat_ids,-1) == ",")
                $concat_ids = substr($concat_ids, 0, -1);
            $jsonArr = json_decode(\Httpful\Request::get($url_achievement_fetch.$concat_ids)->send());
            foreach($jsonArr as $json) {
                $achievementJsonObj = $mapper->map($json, new Achievements());
                $this->update($achievementJsonObj);
            }
        }

        return $this->response(new Item("All achievements have been synced",new SimpleTransformer()),$response);
    }


    private function update($achievementJsonObj) {
        if ($achievementJsonObj != null) {
            $redachievement = $this->redAchievements->getByAchievementsId($achievementJsonObj->id);

            $achievement_id = $this->redAchievements->update($redachievement->id,$achievementJsonObj->name,
                $achievementJsonObj->description,$achievementJsonObj->requirement,$achievementJsonObj->type,
                $achievementJsonObj->flags);

            $this->redAchievementsBit->deleteByAchievementId($achievement_id);
            $this->redAchievementsTier->deleteByAchievementId($achievement_id);

            if (!empty($achievementJsonObj->bits)) {
                foreach($achievementJsonObj->bits as $bit)
                    $this->redAchievementsBit->add($achievement_id,$bit->type,$bit->text);
            }

            if (!empty($achievementJsonObj->tiers)) {
                foreach($achievementJsonObj->tiers as $tier)
                    $this->redAchievementsTier->add($achievement_id,$tier->count,$tier->points);
            }
        }
    }
}