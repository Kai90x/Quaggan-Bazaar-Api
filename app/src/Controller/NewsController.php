<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use KaiApp\JsonTransformers\BatchTransformer;
use KaiApp\JsonTransformers\NewsTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedNews;
use League\Fractal\Resource\Item;
use Slim\Http\Request;

class NewsController extends BaseController
{
    const GUILDWAR2_NEWS = "https://www.guildwars2.com/en-gb/feed/?__utma=25938722.1099240920.1434879667.1434879667.1435372268.2&__utmb=25938722.3.9.1435372283695&__utmc=25938722&__utmx=-&__utmz=25938722.1435372268.2.2.utmcsr=google|utmccn=(organic)|utmcmd=organic|utmctr=(not%20provided)&__utmv=-&__utmk=229366751/";
    private $redNews;

    public function __construct(RedNews $_redNews) {
        $this->redNews = $_redNews;
        parent::__construct();
    }

    public function sync($request, $response, array $args) {
        try {
            $xml = simplexml_load_string(file_get_contents($this::GUILDWAR2_NEWS));
            $channels = $xml->channel;

            foreach($channels as $channel) {
                $items = $channel->item;
                foreach($items as $item) {
                    $news = $this->redNews->getByTitle($item->title);
                    if (empty($news)) {
                        $date = new \DateTime($item->pubDate);
                        $dc = $item->children("http://purl.org/dc/elements/1.1/");

                        $this->redNews->add((string)$item->title, (string)$item->link, $date, (string)$item->description, (string)$item->content, (string)$dc->creator);
                    }
                }

            }

            return $this->response(new Item("News has been synced", new SimpleTransformer()),$response);
        } catch(\Exception $e) {
            return $this->response(new Item("An error has occurred", new SimpleTransformer()),$response,500);
        }
	}

    public function get(Request $request, $response, array $args)
    {
        $batchSize = $request->getParam('batchSize',100);
        $currentBatch = $request->getParam('currentBatch',1);

        $totalBatches = $this->redNews->getBatchTotal($batchSize);
        $news = $this->redNews->getByBatch($currentBatch,$batchSize);

        return empty($news) ? $this->response("No news found",$response,404)
            : $this->response(new Item($news,new BatchTransformer(new NewsTransformer(),$batchSize,$currentBatch,$totalBatches)),$response);
    }

}