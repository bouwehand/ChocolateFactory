<?php
/**
 * Chocolate Factory Framework
 *
 * Because i wanna be in the chocolate factory with Willy Fuckin Wonka
 * Dancing with the Oempa loempa's like this
 *
 * @autor       Bastiaan Jeroen Ouwehand
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

define('APP_LIB', ROOT . '/Lib');

require_once(CHOCOLATE_FACTORY .'/ChocolateFactory.php');
$chocolateFactory = new ChocolateFactory();
try {
    $chocolateFactory->run();
} catch (Exception $e) {
    throw $e;
}