<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 3/13/15
 * Time: 11:49 AM
 */
class Ing_Models_Ing
{
    protected $_tableName = 'ING';

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
        $this->_query = Query::getInstance();
        $this->_query->setTable($this->getTableName());

        $sql = "SELECT * FROM `". $this->getTableName() . "` WHERE id = $step";
        return $this->_query->fetchAll($sql);
    }

    public function getGraph()
    {
        $account = 0 - 1219;
        $i = 786;
        while ($i > 2) {
            $step = $this->getStep($i);
            if ($step[0]["Af Bij"] == 'Af') {
                $account -= $step[0]["Bedrag (EUR)"];
            }
            if ($step[0]["Af Bij"] == 'Bij') {
                $account += $step[0]["Bedrag (EUR)"];
            }
            $graph[$step[0]["Datum"]] = $account;
            $i--;
        }
        return $graph;
    }
}