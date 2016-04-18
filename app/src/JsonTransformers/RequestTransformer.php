<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/12/16
 * Time: 8:06 AM
 */

namespace KaiApp\JsonTransformers;


use KaiApp\RedBO\RedUser;
use KaiApp\RedBO\RedDriver;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class RequestTransformer extends TransformerAbstract
{
    private $redClient;
    private $redDriver;

    public function __construct(RedUser $_redClient, RedDriver $_redDriver) {
        $this->redClient = $_redClient;
        $this->redDriver = $_redDriver;
    }


    public function transform($request)
    {
        return [
            "client" => new Item($this->redClient->getById($request->client_id),new ClientTransformer()),
            "driver" => new Item($this->redClient->getById($request->driver_id), new DriverTransformer()),
            "droplocation" => $request->droplocation,
            "hasAccepted" => $request->has_accepted,
            "hasEnded" => $request->has_ended,
        ];
    }


}