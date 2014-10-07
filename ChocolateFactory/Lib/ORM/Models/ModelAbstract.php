<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/7/14
 * Time: 5:04 PM
 */ 
class ORM_Model_Abstract
{
    /**
     * @param  $key
     * @param  $value
     * @return void
     */
    public function setValue($key, $value) {
        $method = "set" . $key;
        if (method_exists($this, $method)) {
            $this->$method($value);
        } else if(method_exists($this, "set" . preg_replace("/^o_/","",$key))) {
  
            $this->$method($value);
        }
        return $this;
    }
}