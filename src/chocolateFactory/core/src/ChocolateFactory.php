<?php

namespace ChocolateFactory\Core;

use ChocolateFactory\MVC\JsonConfig;
use ChocolateFactory\MVC\Request;
use ChocolateFactory\MVC\Controller;

/**
 * Chocolate factory framework
 *
 * By Bas Ouwehand
 *
 * Really simple MVC framework. I develop while programing. Each time I think, would it not be cool if... and then i add
 *
 */
class ChocolateFactory {

    /**
     * Mule function of the framework
     */
    public function run() {

        $jsonConfig = JsonConfig::getInstance();

 
        // Run the mvc web framework if we are in the browser
        // else run the cli version of the framework
        if(php_sapi_name() == 'cli') {
            $cli = new Cli();
            $cli->run();
        } else {
            //run mvc
            $request = Request::getInstance();
            $controller = Controller::getInstance($request->getControllerName(), $request->getAction());
        }
    }
}