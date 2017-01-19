<?php

namespace ChocolateFactory\MVC;

class Template {

    private $controller = null;
    private $action = null;

    /**
     * Constructor takes the controller and the action
     * And determines the correct template for this
     */
    public function __construct() {
        //$this->_controller = $controller;
        //$this->_action = $action;
    }

    /**
     * Function to render the template based on the controller
     * and the action to the page
     */
    public function render() {
        $this->dump();
    }
}