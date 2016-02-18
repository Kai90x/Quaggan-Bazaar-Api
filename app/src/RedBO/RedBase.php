<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 9:10 AM
 */

namespace KaiApp\RedBO;
use RedBeanPHP\Facade;

require_once("RedConnection.php");


abstract class RedBase
{
    /**
     * @property  type
     */
    protected $type;
    protected $dateCreated = "dateCreated";
    protected $dateModified = "dateModified";

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function addGwId($id) {
        return $this::add(array(
            "gw_".$this->type."_id" => $id
        ));
    }

    protected function add($beanArr,$hasDateModified = false) {
        $bean = $this->createBean($beanArr);
        $bean[$this->dateCreated] = Facade::isoDateTime();
        $bean[$this->dateModified] = $hasDateModified ? Facade::isoDateTime() : NULL;
        return Facade::store($bean);
    }

    protected function update($id,$beanArr) {
        $bean = $this->createBean($beanArr);
        $bean["id"] = $id;
        $bean[$this->dateModified] = Facade::isoDateTime();
        return Facade::store($bean);
    }

    protected function getByOne($attribute, $value)
    {
        return Facade::findOne($this->type, $this->toBeanColumn($attribute).' = ? ', array($value));
    }

    public function getByIn($attribute,$valueArr) {
        return Facade::findAll($this->type, $this->toBeanColumn($attribute).' IN ('.Facade::genSlots($valueArr).') ',($valueArr));
    }

    public function getByAll($attribute, $value) {
        return Facade::findAll($this->type,$this->toBeanColumn($attribute).' = ? ', array($value));
    }

    public function getByGwId($value) {
        return Facade::findAll($this->type,"gw_".$this->type."_id = ? ", array($value));
    }

    public function getAll($orderby = null,$asc = true) {
        if ($orderby != null) {
            $order = "Order by ".$this->toBeanColumn($orderby)." " . ($asc ? "ASC" : "DESC");
            $items = Facade::findAll($this->type,$order);
        } else
            $items = Facade::findAll($this->type);

        return $items;
    }

    protected function delete( $attribute, $value) {
        $items = Facade::find($this->type,$this->toBeanColumn($attribute).' = ? ', array( $value ));

        if (empty($items))
            return false;

        (is_array($items)) ? Facade::trashAll($items) : Facade::trash($items);

        return true;
    }

    public function wipe() {
        Facade::exec('DELETE FROM '. $this->type );
        return true;
    }

    public function trashAll() {
        $items = Facade::findAll($this->type);
        Facade::trashAll( $items );
        return true;
    }

    public function getBatchTotal($batch_size) {
        $items = Facade::count($this->type);
        return ceil($items / $batch_size);
    }

    protected function getByBatch($page,$batchSize,$columnName) {
        return Facade::findAll($this->type,"ORDER BY ".$columnName." DESC LIMIT ? , ? ",array((int)(($page-1)*$batchSize),(int)$batchSize));
    }

    protected function toBeanColumn($column) {
        return strtolower(preg_replace_callback('/[A-Z]/', function($matches){
            return $matches[0] = '_' . ucfirst($matches[0]);
        }, $column)) ;
    }

    private function createBean($beanArr) {
        $bean = Facade::dispense($this->type);
        foreach ($beanArr as $key => $value)
            $bean[$key] = $value;

        return $bean;
    }
}