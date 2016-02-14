<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/8/2016
 * Time: 4:06 PM
 */

namespace KaiApp\JsonTransformers;

use League\Fractal\TransformerAbstract;

class BatchTransformer extends TransformerAbstract
{
    private $page;
    private $totalBatches;
    private  $itemtransformer;

    protected $defaultIncludes = [
        'details'
    ];

    public function __construct(TransformerAbstract $_itemtransformer,$_page,$_totalBatches) {
        $this->page = $_page;
        $this->totalBatches = $_totalBatches;
        $this->itemtransformer = $_itemtransformer;
    }

    public function transform($batch)
    {
        return [
            "Batch" => [
                'size' => count($batch),
                'current' => $this->page,
                'total' => $this->totalBatches,
            ],
        ];
    }

    public function includeDetails($batch)
    {
        return $this->collection( $batch, $this->itemtransformer);
    }
}