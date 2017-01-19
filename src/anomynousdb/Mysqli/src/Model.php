<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 28-12-2016
 * Time: 15:12
 */

namespace Anomynousdb\Mysqli;

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use SebastianBergmann\CodeCoverage\Report\PHP;

/**
 * @todo Create an other class ModelFactory and do the whole factory pattern shebang
 *
 * Class Model
 * @package Anomynousdb\Mysqli
 */
class Model extends \dbObject {

    const NAME_SPACE = "\\Anomynousdb\\Mysqli\\Model";

    protected static $instance;
    protected $tableName;
    protected $dbTable;
    protected $dbFields;
    protected $relations;

    /**
     * @var self $relationObject relation model
     */
    protected static $relationObject;

    /**
     * @param array $data Data to preload on object creation
     * @param null  $config
     *
     * @throws \Exception
     */
    public function __construct($data = NULL, $config = NULL) {

        // set the config
        if (!$config) {
            return self::getInstance();
        }

    }

    /**
     * @return Model
     * @throws \Exception
     */
    public static function getInstance() {
        if (empty(self::$instance)) {

        }

        return self::$instance;
    }

    /**
     * load the mysqli module
     *
     * @param array|null $config pass custom config to mysqli
     * example:
     *
     *  $testConfig = array(
     *      "EtCron"          => array(
     *          'dbTable'  => 'et_cron',
     *          'dbFields' => array(
     *              "name" => array("Device", "name"),
     *          ),
     *      ),
     *  );
     *
     * @return Model
     * @throws \Exception
     */
    public static function init($config) {

        $tableName = preg_replace("/[^-a-z0-9_]+/i", '', key($config));
        $config = current($config);

        /**
         * I actually dont like eval. But the same method is used in dbObject::table
         * and if i don't use it i enter a world of bugs
         */
        $code = "class $tableName extends dbObject {" . PHP_EOL
            . '   protected $dbTable = \'' . $config['dbTable'] . "';" . PHP_EOL
            . '   protected $dbFields = ' . var_export($config['dbFields'], TRUE) . ";" . PHP_EOL;
        if (isset($config['relations']) && is_array($config['relations'])) {
            $code .= '   public $relations = ' . var_export($config['relations'], TRUE) . ";" . PHP_EOL;
        };
        $code .= '   public static $relationObject = null;' . PHP_EOL
            . '};' . PHP_EOL;

        if (!class_exists($tableName)) {
            eval ($code);
        }
        $table = new $tableName();

        if (isset($config['relations'])) {
             self::getRelationObjects($table);
        }

        self::$instance = $table;

        return $table;
    }



    /**
     * @param \dbObject $mainObject
     *
     * @return dbObject
     */
    public static function getRelationObjects($mainObject) {

        // check if we can get an relation from mem
        if (!empty($mainObject::$relationObject)) {
            return $mainObject::$relationObject;
        }

        foreach ($mainObject->relations as $dbTable => $relation) {
            $relationObject = self::getNextRelation($dbTable, $relation);
            $relationObject::byId($mainObject->{$mainObject->primaryKey});
        }
        return $relationObject;
    }

    /**
     * @param array $relation
     *
     * @return \dbObject
     */
    private static function getNextRelation($dbTable, array $relation)
    {
        $relationClassName = $relation[1];
        $relationPrimaryKey = $relation[2];

        if (!class_exists($relationClassName)) {
            $relationObject = $relationClassName::init(array(
                $relationClassName => array(
                    "dbTable"  => $dbTable,
                    "dbFields" => array(
                        // is always the index so assuming int
                        $relationPrimaryKey => "int",
                    ),
                ),
            ));
        } else {
            $relationObject = new $relationClassName();
        }
        return $relationObject;
    }
}