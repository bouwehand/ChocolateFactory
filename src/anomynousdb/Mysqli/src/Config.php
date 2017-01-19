<?php
namespace Anomynousdb\Mysqli;
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 21-12-2016
 * Time: 16:16
 */

/**
 * @var array $config configuration for functions that are used for the database
 */
class Config {


    const DB_REPLACE        = 'dbReplace';
    const DB_MATCH_REPLACE  = 'dbMatchReplace';

    const VALUE_NULL = 'USE_NULL';


    protected static $instance;

    /**
     * @var array
     */
    protected $config  = array();

    /**
     * @var array $configTables;
     */
    protected $configTables;

    /**
     * @var
     */
    protected $currentTableName;

    /**
     * @param array|null    $config pass custom config to mysqli
     * example:
     *  $testConfig = array(
     *      "EtCron"          => array(
     *          'dbTable'  => 'et_cron',
     *          'dbFields' => array(
     *              "name" => array("Device", "name"),
     *          ),
     *      ),
     *  );
     *
     * @return Config
     */
    public static function init(array $config = null)
    {
        // if the init is called again, restart the instance
        self::$instance  = null;
        return new self($config);
    }

    /**
     * Config constructor.
     *
     * @param array $config
     */
    private function __construct(array $config = null)
    {
        if(empty(self::$instance) || !empty($config)) {
             $this->config = $config;
             self::$instance = $this;
        }
        return self::$instance;
    }

    /**
     * @return Config
     */
    public static  function getInstance()
    {
        return self::$instance;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        if(empty($this->config)) {
            $this->config = include ("config.array.php");
        }
        return $this->config;
    }

    /**
     * @param \dbObject|string $table table or table name for config
     *
     * @return array
     * @throws \Exception
     */
    public function getTableConfig($table)
    {
        if (is_object($table)) {
            $table = get_class($table);
        }
        $config = $this->getConfig()[$table];
        if (!is_array($config)) {
            throw new \Exception("Table $table not found in config!");
        }

        if (!isset($config['dbTable']) || !is_array($config['dbFields'])) {
            throw new \Exception('dbTable and dbFields must be set in config!');
        }
        return array($table => $config);
    }
}

