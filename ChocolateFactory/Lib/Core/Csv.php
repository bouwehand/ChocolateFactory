<?php
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
class ChocolateFactory_Core_Csv
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
    public function setHead(Array $head)
    {
        foreach($head as $name) {
            $newHead[] = $this->normalize($name);
        }
        $this->_head = $newHead;
    }

    /**
     * @return array
     */
    public function getHead()
    {
        return $this->_head;
    }

    /**
     * @param $string
     * @return mixed
     */
    public function normalize($string)
    {
        return preg_replace("#[^A-Za-z1-9]#","", $string);
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

    public function setColumn($name, Array $column)
    {
        $data = $this->getData();
        foreach ($column as $i => $value) {
            $data[$i][$name] = $value;
        }
        $this->setData($data);
        return $this;
    }

    /**
     * Drop a column from the csv
     * @param $name
     * @return $this
     */
    public function dropColumn($name)
    {
        $data = array();
        foreach ($this->getData() as $i => $row)
        {
            unset($row[$name]);
            $data[$i] = $row;
        }
        $this->setData($data);
        unset($this->_head[$name]);
        return $this;
    }

    /**
     * Write a date column in correct format
     *
     * @param $name
     * @return $this
     */
    public function formatColumnDate($name)
    {
        $column = array();
        foreach ($this->getColumn($name) as $datum) {
            $column[] =  date('Y-m-d', strtotime($datum));
        }
        $this->setColumn($name, $column);
        return $this;
    }

    public function formatColumnFloat($name)
    {
        $column = array();
        foreach ($this->getColumn($name) as $value) {
            $column[] =   (float) str_replace(',','.',$value);
        }
        $this->setColumn($name, $column);
        return $this;
    }

    /**
     * Reverse the csv
     */
    public function reverse()
    {
        $data = $this->getData();
        $this->setData(array_reverse($data));
        return $this;
    }

    /**
     * @param $csvFilePath
     * @return ChocolateFactory_Core_Csv
     * @throws Exception
     */
    public static function init($csvFilePath)
    {
        $className = get_called_class();
        $csvClass = new $className();

        if (!file_exists($csvFilePath)) {
            throw new Exception('no csv file: ' . $csvFilePath);
        }
        $csv = array_map('str_getcsv', file($csvFilePath));
        $keys = array_shift($csv);
        $csvClass->setHead($keys);
        $head = $csvClass->getHead();
        foreach ($csv as $i => $data) {
            if(! ($csv[$i] = array_combine($head, $data)) ) {
                throw new Exception("CSV row length is inconsistent on line " . $i);
            };
        }
        $csvClass->setData($csv);
        return $csvClass;
    }
}