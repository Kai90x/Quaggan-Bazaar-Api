<?php
namespace KaiApp\RedBO;
require_once("RedConnection.php");
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use KaiApp\Utils\GuildWars2Util;
use KaiApp\Utils\GuildWars2Utils;
use RedBeanPHP;
use RedBeanPHP\Facade;

class RedItem extends RedQuery {

    const ITEM = 'item';

    public function __construct()
    {
        parent::__construct(SELF::ITEM);
    }

    public function addGwId($recipeId) {
        return parent::add(array(
            "gwItemId" => $recipeId
        ));
    }

    public function add($id, $name, $icon, $description, $type, $rarity, $level, $vendor_value, $default_skin, $flags, $game_types, $restrictions) {
        return parent::add(array(
            "gwItemId" => $id,
            "name" => $name,
            "icon" => $icon,
            "description" => $description,
            "type" => $type,
            "rarity" => $rarity,
            "level" => $level,
            "vendor_value" => $vendor_value,
            "default_skin" => $default_skin,
            "flags" => $flags,
            "game_types" => $game_types,
            "restrictions" => $restrictions
        ));
    }

    public function update($updateid,$id, $name, $icon, $description, $type, $rarity, $level, $vendor_value, $default_skin, $flags, $game_types, $restrictions) {
        return parent::update($updateid,array(
            "gwItemId" => $id,
            "name" => $name,
            "icon" => $icon,
            "description" => $description,
            "type" => $type,
            "rarity" => $rarity,
            "level" => $level,
            "vendor_value" => $vendor_value,
            "default_skin" => $default_skin,
            "flags" => $flags,
            "game_types" => $game_types,
            "restrictions" => $restrictions
        ));
    }

    public function getSearchTotalBatch($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$batch_size,$order_by = SELF::ORDERBY_ID) {
        $joinItemDetails = $this->joinItemDetails($subtype);
        $joinPrice = $this->joinPrices($buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$order_by);
        $whereClause = $this->AddWhereClause($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity);
        $searchClause = $joinItemDetails.$joinPrice.$whereClause;

        $params = $this->getSearchParams(true,$name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,null,null);
        $items_num = Facade::count(SELF::ITEM,"$searchClause ",$params);
        return ceil($items_num / $batch_size);
    }

    public function search($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$batch_num,$batch_size,$order_by = 1, $descOrAsc = 0) {
        $joinItemDetails = $this->joinItemDetails($subtype);
        $joinPrice = $this->joinPrices($buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$order_by);
        $whereClause = $this->addWhereClause($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity);
        $orderClause = $this->orderBy($order_by,$descOrAsc);
        $searchClause = $joinItemDetails.$joinPrice.$whereClause.$orderClause;

        $params = $this->getSearchParams(false,$name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$batch_num,$batch_size);
        return Facade::find(SELF::ITEM," $searchClause LIMIT :batchNum , :batchSize ",$params );
    }

    private function getSearchParams($isCount,$name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity,$batch_num,$batch_size) {
        $params = array();

        if (!$isCount) {
            $params[":batchNum"] = (int)(($batch_num - 1) * $batch_size);
            $params[":batchSize"] = (int)$batch_size;
        }

        if (!empty($name)) $params[":name"] = "%$name%";
        if (!empty($type)) $params[":type"] = $type;
        if (!empty($subtype)) $params[":subtype"] = $subtype;
        if (!empty($buyPriceMin)) $params[":buyPriceMin"] = $buyPriceMin;
        if (!empty($buyPriceMax)) $params[":buyPriceMax"] = $buyPriceMax;
        if (!empty($sellPriceMin)) $params[":sellPriceMin"] = $sellPriceMin;
        if (!empty($sellPriceMax)) $params[":sellPriceMax"] = $sellPriceMax;
        if (!empty($rarity)) $params[":rarity"] = $rarity;
        if (!empty($levelmin)) $params[":levelmin"] = $levelmin;
        if (!empty($levelmax)) $params[":levelmax"] = $levelmax;

        return $params;
    }

    public function getByGwIdsBatchTotal($idsArr,$batch_size) {
        $items_num = Facade::count(SELF::ITEM,"gw_item_id IN ( ".Facade::genSlots($idsArr)." ) ",$idsArr);
        return ceil($items_num / $batch_size);
    }

    public function getByGwId($id) {
        return parent::getByOne("gwItemId",$id);
    }
    public function getByGwIds($idsArr,$batch_num,$batch_size) {

        if (!is_numeric($batch_size) || !is_numeric($batch_num))
            return null;

        $batchNumParam = (int)(($batch_num-1)*$batch_size);
        return Facade::findAll(SELF::ITEM,"gw_item_id IN ( ".Facade::genSlots($idsArr)." ) ORDER BY id ASC LIMIT $batchNumParam , $batch_size ",$idsArr);
    }

    protected function addWhereClause($name,$levelmin,$levelmax,$type,$subtype,$buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$rarity) {
        $where = "";

        if (!empty($name) || !empty($type) || !empty($subtype) || !empty($buyPriceMin) || !empty($buyPriceMax)
            || !empty($sellPriceMin) || !empty($sellPriceMax) || !empty($rarity) || !empty($levelmin) || !empty($levelmax) )
            $where = " WHERE ";
        else
            return $where;

        if (!empty($name))
            $where .= " item.name LIKE :name ";

        if (!empty($type)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " item.type = :type ";
        }

        if (!empty($levelmin)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " item.level >= :levelmin ";
        }

        if (!empty($levelmax)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " item.level <= :levelmax ";
        }

        if (!empty($rarity)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " item.rarity = :rarity ";
        }

        if (!empty($subtype)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " itemdetails.type = :subtype ";
        }

        if (!empty($buyPriceMin)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " guildprices.buyprice >= :buyPriceMin ";
        }

        if (!empty($buyPriceMax)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " guildprices.buyprice <= :buyPriceMax ";
        }

        if (!empty($sellPriceMin)) {
            if (!$where != " WHERE ") $where .= " AND ";
            $where .= " guildprices.sellprice >= :sellPriceMin ";
        }

        if (!empty($sellPriceMax)) {
            if ($where != " WHERE ") $where .= " AND ";
            $where .= " guildprices.sellprice <= :sellPriceMax ";
        }

        return $where;
    }

    protected function joinItemDetails($subtype) {
        return (empty($subtype)) ? "" :" INNER JOIN itemdetails ON itemdetails.item_id = item.id ";
    }

    protected function joinPrices($buyPriceMin,$buyPriceMax,$sellPriceMin,$sellPriceMax,$order) {
        return (empty($buyPriceMin) && empty($buyPriceMax) && empty($sellPriceMin) && empty($sellPriceMax) && $order != GuildWars2Utils::ORDERBY_BUYPRICE
            && $order != GuildWars2Utils::ORDERBY_SELLPRICE && $order != GuildWars2Utils::ORDERBY_DATEUPDATED) ? ""
        : " INNER JOIN prices ON prices.gw_prices_id = item.gw_item_id ";
    }

    protected function orderBy($order, $DescOrAsc) {
        switch($order) {
            case GuildWars2Utils::ORDERBY_DEFAULT:
                $orderClause = " ORDER BY item.id ";
                break;
            case GuildWars2Utils::ORDERBY_BUYPRICE:
                $orderClause = " ORDER BY guildprices.buyprice ";
                break;
            case GuildWars2Utils::ORDERBY_SELLPRICE:
                $orderClause = " ORDER BY guildprices.sellprice ";
                break;
            case GuildWars2Utils::ORDERBY_DATEUPDATED:
                $orderClause = " ORDER BY guildprices.date_updated ";
                break;
            default:
                $orderClause = "";
                break;
        }

        if (!empty($orderClause)) {
            $orderClause .= ($DescOrAsc == GuildWars2Utils::ORDER_DESC) ?  " DESC " : " ASC ";
        }

        return $orderClause;
    }

}