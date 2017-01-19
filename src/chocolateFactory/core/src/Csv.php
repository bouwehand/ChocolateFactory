<?php

namespace ChocolateFactory\Core;

/**
 * Csv.php
 *
 * <description>
 *
 * @category Youwe Development
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 9/3/15
 *
 */
class Csv
{

    /**
     * @var array
     */
    protected $_head = array();

    /**
     * @var data
     */
    protected $_data = array();

    /**
     * @var Length of the csv file
     */
    protected $_length;

    /**
     * @param array $head
     */
    public function setHead($head)
    {
        $this->_head = $head;
    }

    /**
     * @return array
     */
    public function getHead()
    {
        return $this->_head;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        if (!$this->_length) {
            $this->_length = count($this->getData());
        }
        return $this->_length;
    }

    /**
     * Return a specific column of the csv.
     *
     * @param $name
     * @return array
     */
    public function getColumn($name)
    {
        $column = array();
        foreach ($this->getData() as $row) {
            $column[] = $row[$name];
        }
        return $column;
    }

    public function dropColumn($name)
    {
        $data = array();
        foreach( $this->getData() as $i => $row)
        {
            unset($row[$name]);
            $data[$i] = $row;
        }
        $this->setData($data);
        return $this;
    }

    /**
     * @param $csvFilePath
     * @return ChocolateFactory_Core_Csv
     * @throws Exception
     */
    public static function init($csvFilePath)
    {
        $csvClass = new self();
        if (!file_exists($csvFilePath)) {
            throw new Exception('no csv file: ' . $csvFilePath);
        }
        $csv = array_map('str_getcsv', file($csvFilePath));
        $keys = array_shift($csv);
        foreach ($csv as $key => $data) {
            if(! ($csv[$key] = array_combine($keys, $data)) ) {
                throw new Exception("CSV row length is inconsistent on line " . $key);
            };
        }
        $csvClass->setHead($keys);
        $csvClass->setData($csv);
        return $csvClass;
    }
}