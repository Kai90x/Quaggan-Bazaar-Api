<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 6/11/15
 * Time: 10:48 PM
 */
namespace KaiApp\Controller;

use JsonMapper;
use KaiApp\JsonTransformers\PriceTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedPrices;
use KaiApp\RedBO\RedPricesHistory;
use KaiApp\Serialization\Prices\Prices;
use KaiApp\Utils\GuildWars2Utils;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class PriceController extends BaseController
{
    private $redPrices;
    private $redPricesHistory;

    public function __construct(RedPrices $_redPrices,RedPricesHistory $_redPricesHistory) {
        $this->redPrices = $_redPrices;
        $this->redPricesHistory = $_redPricesHistory;
        parent::__construct();
    }

    public function sync(Request $request,Response $response, array $args) {
        GuildWars2Utils::syncWithGuildWars2(GuildWars2Utils::getPricesUrl(),$this->redPrices,new Prices(),array($this,"update"));
        return $this->response(new Item("All prices have been synced",new SimpleTransformer()),$response);
    }

    public function updateByIds(Request $request,Response $response, array $args) {
        $idsArr  = explode(",", $request->getParam("ids"));
        $prices = $this->redPrices->getAllUnsyncedPricesByIds($idsArr);
        $mapper = new JsonMapper();

        $concat_ids = "";
        foreach($prices as $price)
            $concat_ids .= $price["gw_item_id"].',';

        if (substr($concat_ids,-1) == ",")
            $concat_ids = substr($concat_ids, 0, -1);

        $jsonArr = json_decode(Request::get((GuildWars2Utils::getPricesUrl()."?ids=". $concat_ids))->send());
        foreach ($jsonArr as $json)
            $this->update($mapper->map($json,new Prices()));

        return $this->response(new Item("All prices have been synced",new SimpleTransformer()),$response);
    }

    private function update($price) {
        $redPrice = $this->redPrices->getByItemId($price->id);
        if (!empty($redPrice)) {
            $this->redPrices->update($redPrice->id,$price->id,$price->buys->unit_price,$price->buys->quantity,$price->sells->unit_price,$price->sells->quantity);
            $oldPrice = $this->redPricesHistory->getByItemId($price->id);
            if (!!empty($oldPrice) &&
                (abs(strtotime(date('Y-m-d H:i:s')) - strtotime($oldPrice->date_modified)) / 60) > 15  &&
                date('d.m.Y', strtotime($oldPrice->date_modified)) == date('d.m.Y')) {

                $newSellPrice = ( $oldPrice->sellprice + $price->sells->unit_price ) / 2;
                $newBuyPrice = ( $oldPrice->buyprice + $price->buys->unit_price ) / 2;
                $newSellQuantity = ( $oldPrice->sellquantity + $price->sells->quantity ) / 2;
                $newBuyQuantity = ( $oldPrice->buyquantity + $price->buys->quantity ) / 2;

                $this->redPricesHistory->update($oldPrice->id,$price->id,$newBuyPrice,$newBuyQuantity,$newSellPrice,$newSellQuantity);
            } else {
                $this->redPricesHistory->add($price->id,$price->buys->unit_price,$price->buys->quantity,$price->sells->unit_price,$price->sells->quantity);
            }
        }
    }


	 public function all(Request $request,Response $response, array $args)
    {
        $prices = $this->redPricesHistory->getAllByItemId($args['id']);
        return $this->response(new Item($prices,new PriceTransformer()),$response);
    }


}