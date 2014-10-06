<?php


class Query {

    /**
     * @var $instance Query
     */
    private static $instance;

    /**
     * @var string
     */

    protected $_table = 'table';

    /**
     * @var $_pdo PDO Object
     */
    protected $_pdo;

    /**
     * mysql:dbname=<databasename>;host=localhost
     * 
     * @return string dsn config
     * 
     */
    public static function getDsn()
    {
        $jsonConfig = JsonConfig::getInstance();
        return $jsonConfig->getSysConf('core/query/dsn');
    }

    /**
     * ex. root
     *
     * @return string user config
     *
     */
    public static function getUser()
    {
        $jsonConfig = JsonConfig::getInstance();
        return $jsonConfig->getSysConf('core/query/user');
    }

    /**
     * ex. password
     *
     * @return string user config
     *
     */
    public static function getPassword()
    {
        $jsonConfig = JsonConfig::getInstance();
        return $jsonConfig->getSysConf('core/query/password');
    }
    
    /**
     * Initialize the PDO wrapper
     */
    private function __construct()
    {
        try {
            $this->_pdo =  new PDO(Query::getDsn(), Query::getUser(), Query::getPassword());
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage() . " \n\n";
            die();
        }
        $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->_instance = $this;
    }

    private function __clone() {}

    public static function getInstance() {

        if (!Query::$instance instanceof self) {
            Query::$instance = new Query();
        }
        return Query::$instance;
    }

    public function truncate($table) {

        $sql = " TRUNCATE TABLE $table";
        return $this->_pdo->exec($sql);
    }

    public function setTable($table) {
        $this->_table = $table;
        return $this;
    }

    public function getTable() {
     if($this->_table == null) throw new Exception('Table must be set first!');
        return $this->_table;
    }


    /**
     * @param $args
     * @param bool $verbose
     * @internal param $table
     * @return int
     */
    public function insert($args, $verbose = false) {
        $columns = "`" .implode("`,`", array_keys($args)) . "`";
        $values = implode(",", array_values($args));

        $sql =
            "INSERT INTO  `" . $this->getTable() . "` (
            {$columns}
         )
         VALUES (
            {$values}
        );";
        if ( $verbose) echo $sql;
        try {
            $result = $this->_pdo->exec($sql);
        } catch(PDOException $e) {
            die($e->getMessage() . " " . $sql) ;
        }
        return $result;
    }

    /**
     * Fetch all wrapper PDO
     *
     * @param $sql
     * @param bool $verbose
     * @return array
     */
    public function fetchAll($sql, $verbose = false) {
        if ( $verbose) echo $sql;
        try {
            $sth = $this->_pdo->prepare($sql);
            $sth->execute();
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            echo $e->getMessage() . " " . $sql ;
        }
        return $result;
    }

    /**
     * Fetch One wrapper Pdo
     *
     * @param $sql
     * @param bool $verbose
     * @return mixed
     */
    public function fetchOne($sql , $verbose  = false) {
        if ( $verbose) echo $sql;
        try {
            $sth = $this->_pdo->prepare($sql);
            $sth->execute();
            $result = $sth->fetch();
        } catch(PDOException $e) {
            echo $e->getMessage() . " " . $sql ;
        }
        return $result;
    }
}





