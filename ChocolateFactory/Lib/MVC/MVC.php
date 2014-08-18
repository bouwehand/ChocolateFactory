<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 7/13/14
 * Time: 7:05 PM
 */
class MVC {
    /**
     * MVC mule function
     */
    public function run() {
        $request = Request::getInstance();
        $controller = Controller::getInstance($request->getControllerName(), $request->getAction());


    }

}