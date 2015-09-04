<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 6/26/15
 * Time: 9:06 PM
 */
class CSV
{

    /**
     * @var array
     */
    protected $_head = array();

    /**
     * @var array
     */
    protected $_data = array();

    protected $_length;

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

    public static function load($name)
    {

        if (empty($name)) {
            throw new Exception('No argument loaded');
        }

        $path = CHOCOLATE_FACTORY_DOC .'/' . $name . '.csv';
        if (!file($path)) {
            throw new Exception("File $path  is missing");
        }

        /** @var CSV $csv */
        $csv = new self();
        $csvData = array_map('str_getcsv', file($path));
        $head = array_shift($csvData);
//        foreach($csvData as $i => $v) {
//            $csvData[$i] = array_combine($head, $csvData[$i]);
//        }
        $csv->setHead($head);
        $csv->setData($csvData);
        return $csv;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }



}