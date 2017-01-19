<?php
namespace Anomynousdb\Mysqli;

use Anomynousdb\Random\Random;


/**
 * Class Mysqli
 * @package Anomynousdb\Mysqli
 */
class Mysqli {

    /**
     * number of rows used in bulk
     */
    const BULK_SIZE = 10;

    const MODEL_NAMESPACE = "\\Anomynousdb\\Mysqli\\Model\\";
    const RANDOM_NAMESPACE = "\\Anomynousdb\\Random\\";

    /**
     * @var int
     */
    public $rowCounter = 0;

    /**
     * @var string $tableName
     */
    protected $tableName;

    /**
     * @var array $tableNames
     */
    protected $configTables = array();

    /**
     * @var \dbObject $configTable ;
     */
    protected $tableObject;

    /**
     * load the mysqli module
     *
     * @see Config
     *
     * @param array $config
     *
     * @return Mysqli
     */
    public static function init(array $config = NULL) {
        $mysqli = new self();
        $mysqli
            ->loadEnv()
            ->loadDb();
        Config::init($config);

        return $mysqli;
    }

    /**
     * mule function
     */
    public function run() {
        /** @var \dbObject $tableObject */
        while ($tableObject = $this->initNextTableObject()) {
            /**
             */
            while ($result = $tableObject::get(Array($this->rowCounter, $this->rowCounter + self::BULK_SIZE))) {

                // strip the array wrapper
                if (!is_array($result)) {
                    throw new \Exception("No result for " . get_class($tableObject));
                }
                foreach ($result as $row) {
                    $this->updateRowObject($row);
                }
                $this->rowCounter += self::BULK_SIZE;
            }
        }

        return TRUE;
    }

    /**
     * @param $object \dbObject
     *
     * @return \dbObject
     * @throws \Exception
     */
    public function updateRowObject(\dbObject $object) {

        // get the config
        $tableConfig = Config::getInstance()->getTableConfig($object);

        // hydrate the object
        $hydrateConfig = $this->matchHydrateConfig($tableConfig, $object);
        if ($hydrateConfig) {
            $object = $this->hydrateObject($hydrateConfig, $object);
            if (!$object->save()) {
                die(__FILE__ . " on " . __LINE__ . " \n\n Error saving object {$tableConfig['dbTable']}. Data: " . $object->getLastError());
            }
        }
        return $object;
    }

    /**
     * Returns the dbReplace config for hydration
     *
     * @param array $tableConfig assumes full config with objectName
     * @param       $object
     *
     * @return array|bool
     * @throws \Exception
     */
    public function matchHydrateConfig(array $tableConfig, $object) {
        $tableName = key($tableConfig);
        $tableConfig = current($tableConfig);

        if (!isset($tableConfig[Config::DB_MATCH_REPLACE]) && !isset($tableConfig[Config::DB_REPLACE])) {
            throw new \Exception(" " . Config::DB_REPLACE . " or " . Config::DB_MATCH_REPLACE . " missing for table $tableName");
        }

        if (isset($tableConfig[Config::DB_MATCH_REPLACE])) {
            $matchedConfig = $this->matchObject($tableConfig, $object);
            if (!$matchedConfig) {
                return false;
            }
        } else {
            $matchedConfig = $tableConfig[Config::DB_REPLACE];
        }

        return $matchedConfig;
    }

    /**
     * @param array     $tableConfig
     * @param \dbObject $object
     *
     * @return array|bool
     * @throws \Exception
     */
    public function matchObject(array $tableConfig, \dbObject $object) {
        $hydrateConfig = array();

        $object->data;
        if (empty($object->data)) {
            throw new \Exception("Nothing to match on " . get_class($object));
        }
        $columns = array_keys($object->data);


        // find the right column
        foreach ($tableConfig[Config::DB_MATCH_REPLACE] as $key => $values) {
            if (!in_array($key, $columns)) {
                continue;
            }

            // see if we have a match
            $foundValue = $object->$key;
            $matches = array_keys($values);
            if (!in_array($foundValue, $matches)) {
                continue;
            }
            $hydrateConfig += $values[$foundValue];
        }

        if (empty($hydrateConfig)) {
            return FALSE;
        }

        return $hydrateConfig;
    }

    /**
     * @param array     $replaceConfig part of the dbReplace
     * @param \dbObject $object
     *
     * @return \dbObject
     * @throws \Exception
     */
    public function hydrateObject(array $replaceConfig, \dbObject $object) {
        $tableName = get_class($object);
        foreach ($replaceConfig as $column => $function) {
            switch (gettype($function)) {
                case "array" :
                    $className = self::RANDOM_NAMESPACE . $function[0];

                    /**
                     * @note make singleton access for memory
                     *
                     * @var Random $functionObject
                     */
                    $functionObject = new $className();
                    try {
                        $value = call_user_func(array($functionObject, $function[1]));
                    } catch (\Exception $exception) {
                        die("In table $tableName on column $column cant set {$function[0]}.{$function[1]}");
                    }
                    $object->{$column} = $value;
                    break;
                case "string":
                    if ($function == Config::VALUE_NULL) {
                        $object->{$column} = NULL;
                    }
                    break;
            }
        }

        return $object;
    }

    /**
     * Load enviorment config for db credentials
     */
    protected function loadEnv() {
        $dotenv = new \Dotenv\Dotenv(getcwd());

        /** Disable env file loading in Gitlab CI automatic testing */
        if (!getenv('GITLAB_CI')) {
            $dotenv->load();
        }
        $dotenv->required([
            'DB_HOST',
            'DB_NAME',
            'DB_USER',
            'DB_PASS',
        ]);

        return $this;
    }

    /**
     * Load the Joshcam mysqli database wrapper
     */
    protected function loadDb() {
        $db = new \MysqliDb (
            getenv('DB_HOST'),
            getenv('DB_USER'),
            getenv('DB_PASS'),
            getenv('DB_NAME')
        );

        if (!$db) {
            throw new \Exception("No connection to database, check your credentials in .env");
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getConfigTables() {
        $configTables = array_keys(Config::getInstance()->getConfig());
        if (!$this->configTables) {
            $this->configTables = $configTables;
        }

        return $this->configTables;
    }

    /**
     * Move iteration and get table name. Returns false at end of tables
     *
     * @return Model|false
     */
    public function initNextTableObject() {
        if (empty($this->tableObject)) {
            $this->getConfigTables();
            $this->tableName = current($this->configTables);
        } else {
            $this->tableName = next($this->configTables);
        }

        if (!$this->tableName) {
            return FALSE;
        }
        $config = Config::getInstance()->getTableConfig($this->tableName);
        $this->tableObject = Model::init($config);

        return $this->tableObject;
    }
}