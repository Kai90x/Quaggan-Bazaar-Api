<?php
namespace KaiApp\RedBO;
/**
 * Created by PhpStorm.
 * User: Kai
 * Date: 4/26/2015
 * Time: 7:41 PM
 */
use RedBeanPHP;

class RedQuery extends RedBase{

    protected function addOrderBy($tablename,$column) {
        if (empty($tablename) || empty($column) )
            return "";

        return " ORDER BY ".$tablename.".".$column." ";
    }

    protected function addLimit($batch_num,$batch_size) {
        if (empty($batch_num) || empty($batch_size) )
            return "";

        return " LIMIT ".(($batch_num-1)*$batch_size).", $batch_size ";
    }

    protected function addWhereClause($tablename,array $params) {
        if (empty($params))
            return "";

        $where = " WHERE ";
        foreach($params as $param) {
            if ($where != " WHERE ")
                $where .= " AND ";

            $where .= $tablename.".".$param['column']." = '".$param['value']."' ";
        }

        return $where;
    }

    protected function getParamArray($columnname,$value) {
        return array("column" => $columnname,"value" => $value);
    }

}
