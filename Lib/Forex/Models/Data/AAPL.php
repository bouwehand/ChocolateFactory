<?php
/**
 * Created by PhpStorm.
 * User: vanax
 * Date: 4/19/14
 * Time: 5:26 PM
 */
require_once(APP_LIB . '/Forex/Models/Data.php');
class AAPL extends Data {

    /**
     *
     */
    public function __construct() {
        $this->setTableName('AAPL_historical');
    }

    /**
     * @param $first
     * @param $last
     * @return mixed
     */
    public function getMax($first, $last) {
        $this->_query = Query::getInstance();
        $this->_query->setTable($this->getTableName());

        $sql =
            "SELECT MAX(high) as max FROM `". $this->_query->getTable()
            . "` WHERE id >= " . $first . " AND id <= " .  $last;

        $result = $this->_query->fetchOne($sql);
        return $result['max'];
    }

    /**
     * @param $first
     * @param $last
     * @return mixed
     */
    public function getMin($first, $last) {
        $this->_query = Query::getInstance();
        $this->_query->setTable($this->getTableName());

        $sql =
            "SELECT MIN(high) as min FROM `". $this->_query->getTable()
            . "` WHERE id >= " . $first . " AND id <= " .  $last;

        $result = $this->_query->fetchOne($sql);
        return $result['min'];
    }

    /**
     * @param $first
     * @param $last
     * @return mixed
     */
    public function getInterval($first, $last) {
        $this->_query = Query::getInstance();
        $this->_query->setTable($this->getTableName());

        $sql =
            "SELECT * FROM `". $this->_query->getTable()
            . "` WHERE id >= " . $first . " AND id <= " .  $last;

        $result = $this->_query->fetchAll($sql);
        return $result;
    }

}
