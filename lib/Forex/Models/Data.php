<?php
/**
 * Created by PhpStorm.
 * User: vanax
 * Date: 4/19/14
 * Time: 5:26 PM
 */
class Data {

    /**
     *
     */
    const FIRST_STEP_NUM = 100;

    /**
     * 3894
     */
    const MAX_STEP_NUM = 400;

    /**
     * @var array Last step in game
     */
    protected  $_lastStep = array();

    /**
     * @var Query object
     */
    protected $_query;

    /**
     *
     */
    protected  $_tableName;

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
    }

    /**
     * @return mixed
     */
    public function getTableName()
    {
        return $this->_tableName;
    }



    public function __construct () {
        return $this;
    }

    /**
     *
     *
     * @param $step
     * @param bool $verbose
     * @internal param $args
     * @internal param $table
     * @return int
     */
    public function getStep($step, $verbose = false) {
        $this->_query = ChocolateFactory_Core_Query::getInstance();
        $this->_query->setTable($this->getTableName());

        $sql = "SELECT * FROM `". $this->getTableName() . "` WHERE id = $step";
        return $this->_query->fetchAll($sql);
    }

    /**
     * Return the rate of a currency for a given step
     *
     * @param $currency
     * @param $step
     * @return int rate of the currency on step
     */
    public function getStepForCurrency($currency, $step) {
        $this->_query = Query::getInstance();
        $this->_query->setTable($this->getTableName());

        $sql = "SELECT `" . $currency . "` FROM `". $this->_query->getTable() . "` WHERE `id` = " . $step;

        $array = $this->_query->fetchOne($sql);
        return $array[$currency];
    }

    /**
     * Get a trend from the datahandler
     *
     * @param $currencyCode string  currencyCode
     * @param $step         int     step number
     * @param $number       int     length of the trend
     * @param null $offset  int     offset into the past
     * @return array
     */
    public function getTrend($currencyCode, $step, $number, $offset = null) {
        $this->_query = Query::getInstance();
        $this->_query->setTable($this->getTableName());

        $sql =
            "SELECT id, " . $currencyCode . " FROM `". $this->_query->getTable()
            . "` WHERE id >= " . (($step - $number) - $offset) . " AND id <= " .  ($step - $offset);


        $result = $this->_query->fetchAll($sql);
        foreach($result as $row) {
            $return[$row['id']] = $row[$currencyCode];
        }
        ksort($return);
        return $return;
    }

    public function getTotalSteps() {
        return $this::MAX_STEP_NUM;

    }

    /**
     * @return array
     */
    public function getLastStep() {

        return $this->_lastStep;
    }

    /**
     * @param $step
     */
    public function setLaststep($step) {

        $this->_lastStep = $step;

    }

    /**
     * @return mixed
     */
    public function getMAXSTEPNUM()
    {
        return $this::MAX_STEP_NUM;
    }

    /**
     * @param mixed $MAX_STEP_NUM
     */
    public function setMAXSTEPNUM($MAX_STEP_NUM)
    {
        $this->MAX_STEP_NUM = $MAX_STEP_NUM;
    }
}
