<?php
/**
 *
 */
require_once(CHOCOLATE_FACTORY_LIB . '/Mysql/Db.php');

class ChocolateFactory_Mysql_Query extends ChocolateFactory_Mysql_Db {

    protected $_table;

    /**
     * @param mixed $table
     */
    public function setTable($table)
    {
        $this->_table = $table;
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * Basic pdo exec
     *
     * @param $sql
     * @return int
     */
    public function exec($sql) {
        try {
            $result = $this::$_pdo->exec($sql);
        } catch(PDOException $e) {
            die($e->getMessage() . " " . $sql) ;
        }
        return $result;
    }

    /**
     * @param $args
     * @param bool $verbose
     * @internal param $table
     * @return int
     */
    public function insert($args, $verbose = false) {

        $columns = implode(",", array_keys($args));
        $refs = array();
        foreach($args as $key => $value) {
            $refs[] = ":" . $key;
        }
        $refs = implode(",", $refs);
        $sql =
            "INSERT INTO  `" . $this->getTable() . "` (
            {$columns}
         )
         VALUES (
            {$refs}
        );";
        $prep = $this::$_pdo->prepare($sql);
        if ( $verbose) echo $sql;
        try {
            $result = $prep->execute($args);
        } catch(PDOException $e) {
            die($e->getMessage() . " " . $sql) ;
        }
        return $result;
    }

    /**
     * Dynamic database row fetching function
     */
    public function fetch($arg) {

        $dbh = self::getInstance();
        $sth = $dbh->prepare("SELECT * FROM $this->model LIMIT $arg");
        $sth->execute();
        return $sth->fetchAll();
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





