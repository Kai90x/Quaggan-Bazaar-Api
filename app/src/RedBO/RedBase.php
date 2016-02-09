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

    public function __construct($type)
    {
        $this->type = $type;
    }

    protected function add($beanArr) {
        $bean = $this->createBean($beanArr);
        $bean["dateCreated"] = Facade::isoDateTime();
        $bean["dateModified"] = Facade::isoDateTime();
        return Facade::store($bean);
    }

    protected function update($id,$beanArr) {
        $bean = $this->createBean($beanArr);
        $bean["id"] = $id;
        $bean["dateModified"] = Facade::isoDateTime();
        return Facade::store($bean);
    }

    protected function getOne($attribute, $id)
    {
        $item = Facade::findOne($this->type, $attribute.' = ? ', array($id));
        return empty($item) ? null : $item;
    }

    public function getAll() {
        $items = Facade::findAll($this->type);
        return empty($items) ? null : $items;
    }

    protected function delete( $attribute, $id) {
        $items = Facade::find($this->type,$attribute.' = ? ', array( $id ));

        if (empty($items))
            return false;

        (is_array($items)) ? Facade::trashAll($items) : Facade::trash($items);

        return true;
    }

    public function wipe() {
        Facade::wipe( $this->type );
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

    protected function getByBatch($batchNum,$batchSize,$columnName) {
        $items = Facade::findAll($this->type,"ORDER BY ".$columnName." DESC LIMIT ? , ? ",array((int)(($batchNum-1)*$batchSize),(int)$batchSize));
        return (empty($items)) ? null : $items;
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