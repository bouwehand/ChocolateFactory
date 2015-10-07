<?php

/**
 * Table.php
 *
 * <description>
 *
 * @category Youwe Development
 * @package  forex
 * @author   Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date     10/7/15
 *
 */
Class ChocolateFactory_Mysql_Table
{
    /**
     * Standard fields in every table, key and last edit
     * @var array
     */
    static protected $_default_fields = array(
        "id",
        "changed"
    );

    /**
     * @var array
     */
    static protected $_columnTypes = array(
        'int'       => 'int(11) unsigned',
        'string'    => 'varchar(255)',
        'float'     => 'float(19,8)',
        'timestamp' => 'DATETIME'
    );

    /**
     * @var string table name
     */
    protected $_name;

    /**
     * @var array
     */
    protected $_columns = array();

    /**
     * @var array
     */
    protected $_data = array();
    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param array $columns
     *              name => column name
     *              type => column type
     */
    public function setColumns(Array $columns)
    {
        $this->_columns = $columns;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->_columns;
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

    /**
     * @param $name
     * @internal param $table
     * @return int
     */
    public function truncate($name)
    {

        $sql = " TRUNCATE TABLE $name";
        $db = ChocolateFactory_Mysql_Db::init();
        $db->query->exec($sql);
        return $this;
    }

    /**
     * @param $name
     * @return \ChocolateFactory_Mysql_Table
     */
    public static function init($name)
    {
        $db = ChocolateFactory_Mysql_Db::init();
        $db->query->setTable($name);
        $result = $db->query->describe();
        $columns = array();
        foreach ($result as $entry) {
            if(! in_array($entry['Field'], self::$_default_fields)) {
                $columns[] = $entry;
            }
        }
        $table = new self();
        $table->setName($name);
        $table->setColumns($columns);
        return $table;
    }

    /**
     * @param string $name table name
     * @param array  $columns
     *                     type int(11) unsigned
     *                     varchar(20)
     *                     float (19,8)
     * @param array  $data table data
     * @return \ChocolateFactory_Mysql_Table
     */
    public static function createTable($name, Array $columns, Array $data = null)
    {
        // run sql
        $db = ChocolateFactory_Mysql_Db::init();
        $db->query->setTable($name);
        $sql = "
            DROP TABLE IF EXISTS `" . $name . "`;
            CREATE TABLE `" . $name . "` (
           `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
           `changed` TIMESTAMP, ";
        foreach ($columns as $column) {
            $sql .= "`" . $column['name'] . "` " . self::$_columnTypes[$column['type']] . " , ";
        }
        $sql .= "PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 ";
        $db->query->exec($sql);

        // create model
        $table = new self();
        $table->setName($name);
        $table->setColumns($columns);

        // insert all the data
        foreach($data as $row) {
            $db->query->insert($row);
        }
        $table->setData($data);
        return $table;
    }
}