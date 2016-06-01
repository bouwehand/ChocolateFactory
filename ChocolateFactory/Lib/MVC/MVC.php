<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 7/13/14
 * Time: 7:05 PM
 */
class ChocolateFactory_MVC {
    /**
     * MVC mule function
     */
    public function run() {
        $request = ChocolateFactory_MVC_Request::getInstance();
        $controller = ChocolateFactory_MVC_Controller::getInstance($request->getControllerName(), $request->getAction());
    }

}