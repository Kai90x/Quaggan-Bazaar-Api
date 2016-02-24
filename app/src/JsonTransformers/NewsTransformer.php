<?php
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 2/8/2016
 * Time: 3:36 PM
 */

namespace KaiApp\JsonTransformers;


use League\Fractal\TransformerAbstract;

class NewsTransformer extends TransformerAbstract
{

    public function transform($news)
    {
        return [
            'title' => $news->title,
            'link' => $news['link'],
            'creator' => $news['creator'],
            'publish_date' => $news['publish_date'],
            'description' => $news['description'],
            'content' => $news['content'],
        ];
    }

}