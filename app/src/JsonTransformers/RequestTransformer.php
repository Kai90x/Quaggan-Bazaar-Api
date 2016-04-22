<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 8:06 AM
 */

namespace KaiApp\JsonTransformers;


use KaiApp\RedBO\RedUser;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class RequestTransformer extends TransformerAbstract
{
    private $redUser;

    public function __construct(RedUser $_redUser) {
        $this->redUser = $_redUser;
    }


    public function transform($request)
    {
        $json =  [
            "id" => $request->id,
            "price" => $request->price,
            "droplocation" => $request->droplocation,
            "hasAccepted" => $request->has_accepted,
            "hasEnded" => $request->has_ended,
            "hasCancelled" => $request->has_cancelled,
            "dateCreated" => $request->date_created,
        ];

        $client =  $this->redUser->getById($request->client_id);
        if (!empty($client)) {
            $json["client"] = array();
            $json["client"]["id"] = $client->id;
            $json["client"]["username"] = $client->username;
        }

        $driver =  $this->redUser->getById($request->driver_id);
        if (!empty($driver)) {
            $json["driver"] = array();
            $json["driver"]["id"] = $driver->id;
            $json["driver"]["username"] = $driver->username;
        }

        return $json;
    }


}