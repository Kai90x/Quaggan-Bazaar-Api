<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use Utils\Common;
use RedBO\RedFactory;

class news extends BaseController
{

    const GUILDWAR2_NEWS = "https://www.guildwars2.com/en-gb/feed/?__utma=25938722.1099240920.1434879667.1434879667.1435372268.2&__utmb=25938722.3.9.1435372283695&__utmc=25938722&__utmx=-&__utmz=25938722.1435372268.2.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided)&__utmv=-&__utmk=229366751/";

    public function SyncNewsAction() {

        $xml = simplexml_load_string(file_get_contents($this::GUILDWAR2_NEWS));
        $channels = $xml->channel;
        foreach($channels as $channel) {

            $items = $channel->item;

            foreach($items as $item) {
                $news = RedFactory::GetRedNews()->FindNewsByTitle($item->title);
                if (empty($news)) {
                    $date = new \DateTime($item->pubDate);
                    $dc = $item->children("http://purl.org/dc/elements/1.1/");

                    RedFactory::GetRedNews()->AddNews((string)$item->title, (string)$item->link, $date, (string)$item->description, (string)$item->content, (string)$dc->creator);
                }
            }

        }

        echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,"News sync completed"));
	}

    public function GetLastestNewsAction()
    {
        $batch_size = $this->app->request()->post('batch_size');
        $batch_num = $this->app->request()->post('batch_num');

        if (empty($batch_size) || empty($batch_num)) {
            $batch_num = 1;
            $batch_size = 100;
        }

        $totalBatches = RedFactory::GetRedNews()->GetTotalNewsBatches($batch_size);

        $news = RedFactory::GetRedNews()->GetNewsByBatch($batch_num,$batch_size);
        if (empty($news)) {
            echo json_encode(Common::GenerateResponse(Common::STATUS_NOTFOUND,"No news found"));
        }

        $reponse = Common::GenerateResponse(Common::STATUS_SUCCESS,Common::ConvertBeanToArray($news,"News"));
        $reponse["BatchTotalNum"] = $totalBatches;
        $reponse["CurrentBatch"] = $batch_num;

        echo json_encode($reponse);
    }

}