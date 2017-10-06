<?php

namespace ChocolateFactory\MVC;


/**
 * Main controller class of my MVC framework
 *
 * @author Bas Ouwehand <b.j.ouwehand@gmail.com>  22-07-2013
 *
 * Abstract class for creating a controller in my Framework.
 *
 */
abstract class Controller
{

    /**
     * array $models Handle to take the models
     */
    protected $_models = array();

    /**
     * @var $_module the
     */
    protected $_module;

    /**
     * Name of the invocke controller
     */
    protected $_nameController = null;

    /**
     * Handle to take the action
     */
    protected $_action = null;

    /**
     * Object   $template   handle to take the template
     */
    protected $_template = null;


    /**
     * Constructor method
     */
    private function __construct($name = null, $action = null)
    {

        // set the controller Name
        $this->setControllerName($name);

        // Set the Action.
        $this->setAction($action);

        // Set the model that is handled by the Controller.
        // $this->setModel($model);

        $this->{$this->getAction()}();

        // set the name of the module we are working in
        $this->setModule();

        // Set the correct tempalte for this controller and action.
        $this->setTemplate($this->_nameController, $this->_action);
    }// _construct

    /**
     * Holder for the concreate functions
     */
    protected function view()
    {

    }

    /**
     *
     * STATIC Get the instance of the controller that was requested
     * By the callhook
     * -> init.php/callhook()
     *
     * By default just load the homeController
     * See config.json
     *
     * @param null $name
     * @param null $action
     * @throws Exception
     * @return
     * @internal param string $nameController Name handle of the controllerclass
     *                                      that is being called.
     */
    public static function getInstance($name = null, $action = null)
    {

        // get configuration
        $jsonConfig = JsonConfig::getInstance();
        $defaultHomepage = $jsonConfig->getConf('MVC/default/homepage');

        if (empty($name)) {
            if (!isset($defaultHomepage) || empty($defaultHomepage) ) {
                throw new Exception('MVC/default/homepage was not configured' . PHP_EOL);
            }
            $name = $defaultHomepage;
        }

        $nameController = $name . 'Controller';

        // check if the controller class exists
        if (!class_exists($nameController)) {
            throw new Exception('Controller ' . $nameController . ' was not created' . PHP_EOL);
        }

        // return the right controller
        return new $nameController ($name, $action);
        // ;
    }// getInstance;

    /**
     * setControllerName($name= null)
     *
     * Setter for the controller name handle
     *
     * @param   string  $name   If no name was provided, strip the name form the classname Controller Object.
     * @return  Self    $this   Controller Object
     */
    private function setControllerName($name = null)
    {
        if (empty($name)) {
            $this->_nameController = strtolower(str_replace(
                    'Controller',
                    '',
                    get_class($this)
                )
            );
        } else {
            $this->_nameController = $name;
        }
        return $this;
    } // setControllerName

    /**
     * Method for setting the model for the controller.
     *
     * Default the default model is fetched from the CONF
     *
     * @param   Model       $model Generic model Object.
     * $return  self        $this       Controller Object.
     * @throws  Exeption
     * @return  $this
     */
    public function setModel($model = null)
    {
        // get configuration
        $config = JsonConfig::getInstance();
        $defaultModel = $config->getConf('MVC/default/model');

        if ($model === null) {
            if (
            isset($defaultModel)
                && !empty($defaultModel)
            ) {
                $model = $defaultModel;
            } else {
                throw new Exeption ('No model configured for this controller!');
            }
        }

        $this->_models[$model] = new $model;
        return $this;
    }// SetModel

    public function getControllerName()
    {
         return $this->_nameController;
    }

    /**
     * Sets the name of the module where the controller is placed
     *
     * @return $this
     */
    public function setModule()
    {
        $reflector = new ReflectionClass($this->getControllerName() . 'Controller');
        $module = explode('/', str_replace(APP_LIB, '', $reflector->getFileName()));
        $this->_module = $module[1];
        return $this;
    }

    /**
     * @return string module name
     */
    public function getModule()
    {
        return $this->_module;
    }

    /**
     * Method for geting an instance of a model configured for this controller
     *
     * @param null $modelName
     * @internal param null $modelname
     * @return null
     * @internal param \Model $model Model Object
     * $return  Model   $model  Model Object
     */
    public function getModel($modelName = null)
    {
        if (empty($modelName)) {
            return null;
        }
        $model = new $modelName;
        return $model;
    }

    /**
     * Method for setting the action for the controller.
     *
     * Default the default action is view
     *
     * @param   string $action Name of the action passed.
     * $return  self    $this       Controller Object.
     * @return $this
     */
    public function setAction($action)
    {
        $defaultAction = JsonConfig::getInstance()->getConf('MVC/default/action');
        if (empty($action)) {
            $this->_action = $defaultAction;
        } else $this->_action = $action;
        return $this;
    }// SetAction

    /**
     * Method for getting the action for the controller.
     *
     * Default the default action is view
     *
     * $return  string  $action     Name of the action passed.
     */
    public function getAction()
    {
        return $this->_action;
    }// getAction

    /**
     * FUNCTION FOR SETTING THE TEMPLATE
     */
    protected function setTemplate($name, $action = null)
    {
        $this->_template = APP_VIEW . '/' . $this->getControllerName() . '/' . $action . '.php';
        return $this;
    }// SetTemplate

    /**
     * Destruct function
     *
     * Renderse the template when no reference is longer
     * registerd
     */
    function __destruct()
    {
        if ($this->_template !== null) require_once($this->_template);
    }
    // Destruct
}