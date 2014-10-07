<?php


class Query {

    /**
     * @var $instance Query
     */
    private static $instance;

    /**
     * @var string
     */
    protected $_table;
    protected $_select = 'SELECT ';
    protected $_from;
    protected $_fields;
    protected $_where;
    protected $_join;

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
            Logger::error('Connection failed: ' . $e->getMessage() . " \n\n");
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
     * build the sub query string select
     * 
     * @param string $fields
     * @return $this
     */
    public function select($fields = '*') {

        if(is_array($fields))
        {

            $fieldString ="";
            foreach($fields as $key)
            {
                $fieldString .= "`$key`,";
            }
            $fieldString=substr($fieldString,0,-1);
            $this->_fields = $fieldString;
        }
        else
        {
            $this->_fields = $fields;
        }

        return $this;
    }

    /**
     * Substring sql from 
     * 
     * @param null $table
     * @param null $alias
     * @return $this
     */
    public function from($table = null, $alias=null){
        if($table == null) {
            $table = $this->getTable();
        // set last table
        } else {
            $this->setTable($table);
        } 
        
        $this->_from = " FROM `$table` AS ".(is_null($alias) ? $table: $alias);
        return $this;
    }

    public function join($table, $joinCond=null,  $type='left')
    {
        $joinTitle = $this->_padString( strtoupper($type) .' '.'JOIN');
        $statement =  $this->_padString($joinTitle) .$table .' ON'.$this->_padString($joinCond);
        if(is_null($this->_join)){
            $this->_join =  $statement ;
        }
        else {
            $this->_join .= $statement ;
        }
        return $this;
    }

    /**
     * @param null $conditions
     * @return $this
     */
    public function where($conditions =NULL)
    {
        if(is_null($conditions))
        {
            return $this;
        }

        $where = $this->_padString("WHERE ( ");

        $count = 0;
        if(is_array($conditions))
        {

            foreach($conditions as $key =>$value){
                $count++;
                if($count >1)
                {
                    $where .=  $this->_padString("AND");
                }

                if($char = $this->_searchSpecialchar($key))
                {
                    $where .= "$key '$value'";
                }
                else{
                    $where .= "$key= '$value'";
                }
            }
        }
        else
        {
            $where .= $conditions;
        }
        $where .=  $this->_padString(")");;

        $this->_where = $where;
        return $this;

    }

    /**
     * @param $input
     * @param int $pad_length
     * @param null $type
     * @return string
     */
    private function _padString($input,$pad_length=2,$type=null)
    {
        $pad_length = strlen($input) + $pad_length;

        switch ($type)
        {
            case 'left':
                $string = str_pad($input,$pad_length," ",STR_PAD_LEFT);
                break;

            case 'right':
                $string = str_pad($input,$pad_length," ",STR_PAD_RIGHT);
                break;

            default:
                $string = str_pad($input,$pad_length," ",STR_PAD_BOTH);
                break;
        }

        return $string;

    }

    private function _searchSpecialchar($string)
    {
        if(preg_match("/[<>>=<=!=]{2}|[><]{1}|[likeLIKE]{4}|[!IN!in]{3}|[INin]{2}$/",trim($string),$match))
        {
            return $match[0];
        }
        else {
            return false;
        }

    }

    public function buildSql(){

        $sql   = $this->_select;
        $sql  .= $this->_fields;

        if(is_null($this->_table))
        {
            throw new Exception('Table is missing');
        }
        
        if(!is_null($this->_from)) {
            $sql .= $this->_from;
        } else {
            $sql .= $this->from($this->getTable());
        }

        if(!is_null($this->_join)){
            $sql .=$this->_join;
        }

        if(!is_null($this->_where)){
            $sql  .= $this->_where;
        }

//        if(!is_null($this->groupBy)){
//            $sql  .= $this->groupBy;
//        }
//
//        if(!is_null($this->orderBy)){
//            $sql  .= $this->orderBy;
//        }
//        if(!is_null($this->limit)){
//            $sql  .= $this->limit;
//        }
//        // $sql;
//        $this->_destruct();

        return $sql;
    }

    /**
     * Main function
     * 
     * @param null $sql
     * @param bool $verbose
     * @return PDOStatement
     */
    protected function _query($sql = null, $verbose =false) {
        
        if(is_null($sql)) {
            $sql = $this->buildSql();
        }
        
        if ($verbose) echo $sql;
        try {
            $sth = $this->_pdo->prepare($sql);
            $sth->execute();
        } catch(PDOException $e) {
            echo $e->getMessage() . " " . $sql ;
        }
        return $sth;
    }

    /**
     * Fetch all wrapper PDO
     *
     * @param $sql
     * @param bool $verbose
     * @return array
     */
    public function fetchAll($sql = null, $verbose = false) {
        $sth = $this->_query($sql, $verbose);
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Fetch One wrapper Pdo
     *
     * @param $sql
     * @param bool $verbose
     * @return mixed
     */
    public function fetchOne($sql = null, $verbose  = false) {
        $sth = $this->_query($sql, $verbose);
        $result = $sth->fetch();
        return $result;
    }
}