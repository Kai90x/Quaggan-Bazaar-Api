<?php
namespace RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedQuery {

    private function getItemsQuery($name,$type,$subtype,$orderby,$batch_size,$batch_num, $count = false) {
        $where = $this->AddAndToWhere($name,$type,$subtype);
        $order = $this->GetOrderBy($orderby);
        $limit = "";
        $baseQuery = "";

        if ($count)
            $baseQuery = "SELECT COUNT(*) FROM item LEFT JOIN itemdetails ON itemdetails.item_id = item.id LEFT JOIN prices ON prices.date_updated = (SELECT MAX(date_updated) FROM prices WHERE prices.gw_item_id = item.gw_item_id) LEFT JOIN pricebuy ON prices.id = pricebuy.prices_id LEFT JOIN pricesell ON prices.id = pricesell.prices_id ";
        else
            $baseQuery = "SELECT * FROM item LEFT JOIN itemdetails ON itemdetails.item_id = item.id LEFT JOIN prices ON prices.date_updated = (SELECT MAX(date_updated) FROM prices WHERE prices.gw_item_id = item.gw_item_id) LEFT JOIN pricebuy ON prices.id = pricebuy.prices_id LEFT JOIN pricesell ON prices.id = pricesell.prices_id ";

        if ($count == false)
            $limit = " LIMIT ".(($batch_num-1)*$batch_size).", $batch_size ";

        return $baseQuery.$where.$order.$limit;
    }

    public function AddAndToWhere($name,$type,$subtype) {
        if (!empty($name) || !empty($type) || !empty($subtype))
            $where = " WHERE ";
        else
            return "";

        if (!empty($name))
            $where .= " item.name LIKE '%$name%' ";

        if (!empty($type)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " item.type = '$type' ";
        }

        if (!empty($subtype)) {
            if (!$where != " WHERE ") $where .= " AND ";
            $where .= " itemdetails.type = '$subtype' ";
        }

        return $where;
    }

    public function GetOrderBy($orderby) {
        if (empty($orderby))
            return "";

        if ($orderby == RedItem::ORDERBY_ID)
            return " ORDER BY item.id ";

        if ($orderby == RedItem::ORDERBY_RARITY)
            return " ORDER BY item.rarity ";

        if ($orderby == RedItem::ORDERBY_BUYPRICE)
            return " ORDER BY pricebuy.unit_price ";

        if ($orderby == RedItem::ORDERBY_SELLPRICE)
            return " ORDER BY pricesell.unit_price ";

    }

    public function getItems($name,$type,$subtype,$orderby,$batch_size,$batch_num) {
        $items =  Facade::getAll($this->getItemsQuery($name,$type,$subtype,$orderby,$batch_size,$batch_num));
        if(empty($items)) {
            return null;
        } else {
            return $items;
        }
    }

    public function getItemsCount($name,$type,$subtype,$orderby,$batch_size) {
        $items_num =  Facade::getAll($this->getItemsQuery($name,$type,$subtype,$orderby,null,null,true));
        $batches = ceil($items_num / $batch_size);

        if(empty($batches)) {
            return null;
        } else {
            return $batches;
        }
    }

}
