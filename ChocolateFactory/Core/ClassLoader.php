<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/8/14
 * Time: 1:35 PM
 */ 
class ClassLoader
{
    /**
     * List populated by the autoloader
     * 
     * @var array
     */
    protected static $_classList = array();

    /**
     * Main autoloader
     * 
     * first load the framework
     * than the apps
     */
    public static function autoload()
    {
        //load class library
        self::_loadClasses(CHOCOLATE_FACTORY_LIB);
        self::_loadClasses(APP_LIB);
    }

    /**
     * LoadClasses
     *
     * loads all the classes in a given directory
     * Dirs that start with a capital are autoloaded
     *
     *  /Test/test
     *
     * Classes that extend should be placed in deeper folders
     *
     * @param  $dirAddress
     * @param array $classList
     * @return true;
     */
    protected static function _loadClasses($dirAddress, $classList = array()) {

        self::_loadClassesRecusive($dirAddress, $classList);

        // load in reverse order so extended classes are load first 
        $classList = array_reverse(self::$_classList);

        foreach($classList as $dirAddress) {
            require_once $dirAddress;
        }

        self::$_classList = array();

        return true;
    }

    protected static function _loadClassesRecusive($dirAddress, $classList = array()){
        if ($handle = opendir($dirAddress)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." ) {

                    if(preg_match("/^[A-Z][a-zA-Z0-9]+$/", $entry) && is_dir($dirAddress . '/' . $entry)) {
                        self::_loadClassesRecusive($dirAddress. '/' . $entry, $classList);
                    }

                    if(preg_match("/^[A-Z][a-zA-Z0-9]+\.php$/", $entry)){
                        self::$_classList[] = $dirAddress. '/' . $entry;
                    }

                }
            }
            closedir($handle);
        }
        return $classList;
    }
}