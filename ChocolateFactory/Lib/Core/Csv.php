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
    protected $_data;

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
        $csvClass->setData($csv);
        return $csvClass;
    }
}