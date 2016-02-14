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
        Utils\GuildWars2Utils::syncWithGuildWars2(Utils\GuildWars2Utils::getAchievementsUrl(),$this->redAchievements,new Achievements(),array($this,"update"));

        return $this->response(new Item("All achievements have been synced",new SimpleTransformer()),$response);
    }


    public function update($achievementJsonObj) {
        if ($achievementJsonObj != null) {
            $redachievement = $this->redAchievements->getByAchievementId($achievementJsonObj->id);

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