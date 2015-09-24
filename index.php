<?php
/**
 * Chocolate Factory Framework
 *
 * Because i wanna be in the chocolate factory with Willy Fuckin Wonka
 * Dancing with the Oompa-Loompas like this
 *
 * @autor       Bastiaan Jeroen Ouwehand <b.j.ouwehand@gmail.com>
 * @version     camsite
 *
 * Realy simple Framework. I develop while programing. Each time I think, would it not be cool if... and then i add
 *
 * I say never be complete
 * evolve
 * and let the chips fall where they may
 */

/**
 * Error reporting
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);
/**
 * Include the Chocolate Factory and run
 */
define('ROOT', getcwd());
define('CHOCOLATE_FACTORY', ROOT . '/ChocolateFactory');
define('CHOCOLATE_FACTORY_CORE' , CHOCOLATE_FACTORY . '/Core');
define('CHOCOLATE_FACTORY_LIB', CHOCOLATE_FACTORY . '/Lib');

define('CHOCOLATE_FACTORY_DOC', ROOT . '/doc');
define('APP_LIB', ROOT . '/Lib');
define('APP_CONTROLLER', ROOT. '/Controller');
define('VENDOR_LIB', ROOT . '/vendor');

require VENDOR_LIB . '/autoload.php';
require_once(CHOCOLATE_FACTORY .'/ChocolateFactory.php');
$chocolateFactory = new ChocolateFactory();
try {
    $chocolateFactory->run();
} catch (Exception $e) {
    ChocolateFactory_Core_Logger::error($e->getMessage());
    die($e->getMessage());
}