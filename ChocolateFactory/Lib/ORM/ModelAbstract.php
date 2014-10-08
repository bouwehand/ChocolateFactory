<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/7/14
 * Time: 5:04 PM
 */ 
class ORM_Model_Abstract
{
    protected $_data;

    /**
     * @param array $data
     * @return $this
     */
    public function setData(Array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @param  $key
     * @param  $value
     * @return void
     */
    public function setValue($key, $value) {
        $method = "set" . ucfirst($key);
        if (method_exists($this, $method)) {
            $this->$method($value);
        }
        return $this;
    }

    public function __call($functionName, $arguments) {

        $functionType = substr($functionName, 0,3);
        $name = strtolower(substr($functionName, 3));
        switch($functionType) {
            case "set" :
               
                $this->_data->$name = current($arguments);;
                break;
            case "get" :
                return $this->_data->$name;
                break;
        }
        return $this;
    }
}