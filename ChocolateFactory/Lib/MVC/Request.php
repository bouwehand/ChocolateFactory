<?php

/**
 * Request Object to handle the requests and return them to the framework
 *
 * Typical request format is:
 * /<controllerName>/<action>/params
 *
 * @author B.j.ouwehand <b.j.ouwehand@gmail.com
 *
 */
class ChocolateFactory_MVC_Request
{
    /**
     * Instance $requestInstance Instanse of the request Object
     */
    private static $_instance;

    /**
     * The params form the request;
     */
    private $_params = array();

    /**
     * The ControllerName;
     */
    private $_controllerName = null;

    /**
     * The action;
     */
    private $_action = null;

    /**
     * @var null
     */
    private $_url = null;

    /**
     * @var null
     */
    private $_baseUrl = null;


    /**
     * Static getter Singleton pattern
     *
     */
    public static function getInstance()
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor class of the Request enity
     *
     * Gets the http host and the request uri and parses them
     *
     *
     *
     */
    private function __construct()
    {

        $this
            ->setFullUrl()
            ->setBaseUrl()
            ->setParams()
            ->setControllerName()
            ->setAction();

        return $this;
    }// _construct();

    public function setBaseUrl($string = null ) {

        if ($string == null ) {
            $string = "http://" . $_SERVER['SERVER_NAME'];
        }
        $this->_baseUrl = $string;
        return $this;

    }

    /**
     * @return null| string
     */
    public function getBaseUrl() {
        return $this->_baseUrl;
    }

    /**
     * Full url Setter
     */
    public function setFullUrl($string = null)
    {
        //curl 'http://camsite.local/home/home?input%5B0%5D=&input%5B1%5D=&input%5B2%5D=&input%5B3%5D=&input%5B4%5D=' -H 'Accept-Encoding: gzip,deflate,sdch' -H 'Accept-Language: nl-NL,nl;q=0.8,en-US;q=0.6,en;q=0.4' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36' -H 'Accept: */*' -H 'Referer: http://camsite.local/' -H 'X-Requested-With: XMLHttpRequest' -H 'Connection: keep-alive' --compressed
        if($string == null) {
            $string = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        $this->_url  = $string;
        return $this;
    }// setFullUrlll()

    /**
     * Full url Getter
     */
    public function getFullUrl()
    {
        if (empty($this->_url)) {
            $this->setFullUrl();
        }
        return $this->_url;
    }// getFullUrl()

    /**
     * Setter for the controllerName
     */
    public function setControllerName($controllerName = null)
    {
        if ($controllerName == null) {
            $controllerName = explode('/', $this->getFullUrl());
            $controllerName = $controllerName[1];
        }
        $this->_controllerName = $controllerName;

        return $this;

    }// setControllerName()


    /**
     * Getter for the controllerName
     */
    public function getControllerName()
    {
        return $this->_controllerName;
    }//getControllerName();

    /**
     * Setter for the acction
     */
    public function setAction($action = null)
    {
        if ($action === null) {
            $action = explode('/', $this->getFullUrl());
            if (count($action) > 2) {
                $action = $action[2];
            } else {
                $action = null;
            }
        }
        $this->_action = $action;
        return $this;
    } // setAction;

    public function getAction()
    {
        return $this->_action;
    }//getAction();

    /**
     * Function to set the params of the request.
     * IF no params are provided, the params from the request are used
     *
     * @param null $array
     * @internal param array $params Pamams to be set by the request
     *
     * @return  Self    $this   Request Object
     */
    public function setParams($array = null)
    {
        // check for params in url
        $url = $this->getFullUrl();

        // remove them for now
        if(strpos($url, '?') !== false) {
           ;
            $url = explode("?", $this->getFullUrl());
            $this->setFullUrl($url[0]);
        }

        if (empty ($array)) {
            $this->_params = $_REQUEST;
        } else {
            $this->_params = $array;
        }
        return $this;
    }

    /**
     * Getter for the params
     */
    public function getParams()
    {
        return $this->_params;
    }

    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}