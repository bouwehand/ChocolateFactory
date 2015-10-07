<?php
/**
 * Db.php
 *
 * <description>
 *
 * @category Youwe Development
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 10/7/15
 *
 */
class ChocolateFactory_Mysql_Db
{

    /**
     * @var $instance ChocolateFactory_Mysql_Query
     */
    private static $instance;

    /**
     * @var $_pdo PDO Object
     */
    protected static $_pdo;

    /**
     * @var ChocolateFactory_Mysql_Query
     */
    public $query;

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

    private function __clone() {}

    /**
     * @return ChocolateFactory_Mysql_Db
     */
    public static function init() {

        if (!self::$instance instanceof self) {
            $db = new self();
            try {
                $db::$_pdo =  new PDO(self::getDsn(), self::getUser(), self::getPassword());
            } catch (PDOException $e) {
                ChocolateFactory_Core_Logger::error('Connection failed: ' . $e->getMessage() . " \n\n");
            }
            $db::$_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->query = new ChocolateFactory_Mysql_Query();
            self::$instance = $db;
        }
        return self::$instance;
    }

}