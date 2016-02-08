<?php
namespace KaiApp\RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use KaiApp\Utils\SanitizationUtils;
use Utils\Common;
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedNews extends RedBase {

    const NEWS = 'news';

    public function __construct()
    {
        parent::__construct(SELF::NEWS);
    }

    public function add($title,$link,$publishDate,$description,$content,$creator) {
        $news = array("title" => SanitizationUtils::StripHTMLCharacter($title),
            "link" => $link,
            "creator" => $creator,
            "publishDate" => $publishDate,
            "description" => SanitizationUtils::StripHTMLCharacter($description),
            "content" => SanitizationUtils::StripHTMLCharacter($content));
        return parent::add($news);
    }

    public function getByTitle($gw_item_id)
    {
        return parent::getOne(parent::toBeanColumn("gwItemId"),$gw_item_id);
    }

    public function delete($id) {
        return parent::delete("id",$id);
    }

    public function FindNewsByTitle($title) {
        $news = Facade::findOne(SELF::NEWS,"title = ?",array(Common::StripHTMLCharacter($title)));

        if(empty($news)) {
            return null;
        } else {
            return $news;
        }
    }

    public function GetTotalNewsBatches($batch_size) {
        $news_num = Facade::count(SELF::NEWS,"Order by publish_date DESC");
        $batches = ceil($news_num / $batch_size);

        if(empty($batches)) {
            return null;
        } else {
            return $batches;
        }
    }

    public function GetNewsByBatch($batch_num,$batch_size) {
        $news = Facade::find(SELF::NEWS,"Order by publish_date DESC LIMIT ? , ? ",array((int)(($batch_num-1)*$batch_size),(int)$batch_size));
        if (empty($news))
            return null;

        return $news;
    }


}