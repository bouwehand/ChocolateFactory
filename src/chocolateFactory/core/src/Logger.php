<?php

namespace ChocolateFactory\Core;

use ChocolateFactory\MVC\JsonConfig;
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/6/14
 * Time: 1:31 PM
 */
class Logger {
    static function error($message) {
        $jsonConfig = JsonConfig::getInstance();
        $errolog = ROOT . $jsonConfig->getConf('core/logger/errorlog');
        file_put_contents( $errolog, $message);
    }
}