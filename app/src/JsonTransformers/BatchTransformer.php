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
    private $batchSize;
    private $currentBatch;
    private $totalBatches;
    private  $itemtransformer;

    protected $defaultIncludes = [
        'details'
    ];

    public function __construct(TransformerAbstract $_itemtransformer, $_batchSize,$_currentBatch,$_totalBatches) {
        $this->batchSize = $_batchSize;
        $this->currentBatch = $_currentBatch;
        $this->totalBatches = $_totalBatches;
        $this->itemtransformer = $_itemtransformer;
    }

    public function transform($batch)
    {
        return [
            "Batch" => [
                'size' => count($batch),
                'current' => $this->currentBatch,
                'total' => $this->totalBatches,
            ],
        ];
    }

    public function includeDetails($batch)
    {
        return $this->collection( $batch, $this->itemtransformer);
    }
}