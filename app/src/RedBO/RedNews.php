<?php
namespace KaiApp\RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use Utils\Common;
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedNews {

	const NEWS = 'news';

    public function AddNews($title,$link,$publishDate,$description,$content,$creator) {
        $news = Facade::dispense(SELF::NEWS);
        $news->title = Common::StripHTMLCharacter($title);
        $news->link = $link;
        $news->creator = $creator;
        $news->publishDate = $publishDate;
        $news->description = Common::StripHTMLCharacter($description);
        $news->content = Common::StripHTMLCharacter($content);
        $news->creation_date = Facade::isoDateTime();

        return Facade::store($news);
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


    public function DeleteNews($id) {
        $news = Facade::find(SELF::NEWS,' id = ? ', array( $id ));
		
		if (empty($news)) {
			return false;
		} else {
            foreach($news as $new)
                Facade::trash($new);
			return true;
		}
	}
	
	public function DeleteAll() {
		  Facade::wipe( SELF::NEWS );
		  return true;
	}
	
}