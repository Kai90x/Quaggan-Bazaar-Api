<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 2/6/16
 * Time: 9:10 AM
 */

namespace RedBO;
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
        $bean = Facade::dispense($this->type);

        foreach ($beanArr as $key => $value)
            $bean[$key] = $value;

        $bean["dateCreated"] = Facade::isoDateTime();
        return Facade::store($bean);
    }

    protected function getOne($attribute, $id)
    {
        $item = Facade::findOne($this->type, ' ? = ? ', array($attribute, $id));
        return empty($item) ? null : $item;
    }

    public function getAll() {
        $items = Facade::findAll($this->type);
        return empty($items) ? null : $items;
    }

    protected function delete( $attribute, $id) {
        $items = Facade::find($this->type,' ? = ? ', array( $attribute, $id ));

        if (empty($items))
            return false;

        if (is_array($items)) {
            foreach ($items as $item)
                Facade::trash($item);
        } else
            Facade::trash($items);

        return true;
    }

    public function wipe() {
        Facade::wipe( $this->type );
        return true;
    }

    protected function toBeanColumn($column) {
        return preg_replace_callback('/[A-Z]/', function($matches){
            return $matches[0] = '_' . ucfirst($matches[0]);
        }, $column);
    }
}