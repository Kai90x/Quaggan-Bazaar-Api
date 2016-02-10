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
use RedBeanPHP;

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

    public function getByTitle($title)
    {
        return parent::getByOne(parent::toBeanColumn("title"), SanitizationUtils::StripHTMLCharacter($title));
    }

    public function delete($id) {
        return parent::delete("id",$id);
    }

    public function getBatchTotal($batchSize) {
        return parent::getBatchTotal($batchSize);
    }

    public function getByBatch($batchNum,$batchSize) {
       return parent::getByBatch($batchNum,$batchSize,parent::toBeanColumn("publishDate"));
    }

}