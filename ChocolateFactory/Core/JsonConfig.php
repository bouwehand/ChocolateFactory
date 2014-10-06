<?php

/**
 * Class JsonConfig
 *
 * @depends JsonPath
 */
class JsonConfig
{
    /**
     * Name of the json config files
     */
    const JsonConfigFIleName = 'config.json';

    const systemConfFileName = 'systemConf.json';

    protected $_conf;

    /**
     * @var null
     */
    protected $_object = null;

    /**
     * @var null
     */
    protected $_xpath = null;

    protected static $_instance = null;

    /**
     * @return JsonConfig|null
     */
    public static function getInstance()
    {
        if(!JsonConfig::$_instance) {
            JsonConfig::$_instance = new JsonConfig();
        }
        return JsonConfig::$_instance;
    }

    /**
     * Read a json config file
     *
     * @param $filePath
     * @throws Exception
     * @return mixed Object json decoded
     */
    protected function _read($filePath)
    {

        $fullPath = $filePath . '/' . $this::JsonConfigFIleName;
        if (!file_exists($fullPath)) {
            throw new Exception('Cant find config file: ' . $fullPath);
        }

        $this->_object = json_decode(file_get_contents($fullPath));
        return $this->_object;
    }

    /**
     *
     *
     * @param $xpath
     * @return null
     */
    public function getConf($xpath)
    {
        $this->_xpath = $this->_conf;
        $xpathArray = explode('/', $xpath);
        array_walk($xpathArray, array($this, '_xpath'));
        return $this->_xpath;
    }  

    /**
     *
     *
     * @param $filePath
     * @return $this
     * @throws Exeption
     */
    public function load($filePath)
    {
        $object = $this->_read($filePath);
        if (!isset($object->module)) {
            throw new Exeption('the config in ' . $filePath . ' does not have a module name configured');
        }

        if (empty($this->_conf)) {
            $this->_conf = new stdClass();
        }
        $this->_conf->{$object->module} = $object;
        return $this;
    }

    /**
     * @param $dirPath
     * @return $this
     */
    public function loadDir($dirPath)
    {
        $entries = array_diff(scandir($dirPath), array('..', '.'));
        foreach ($entries as $entry) {

            if (
                is_dir($dirPath . '/' . $entry)
                && file_exists($dirPath . '/' . $entry . '/' . $this::JsonConfigFIleName)
            ) {
                $this->load($dirPath . '/' . $entry);
            }
        }
        return $this;
    }

    /**
     *
     *
     * @param $xpath
     */
    protected function _xpath($xpath)
    {
        $this->_xpath = $this->_xpath->$xpath;
    }

    public function writeSystemConf()
    {
        if(empty($this->_conf)) {
            throw new Exception('There is no systemconf to write');
        }
        file_put_contents(ROOT .'/'. $this::systemConfFileName, json_encode($this->_conf));
    }

    public function checkSystemConf()
    {
        return file_exists(ROOT .'/'. $this::systemConfFileName, json_encode($this->_conf));
    }


    /**
     * Load special and sensitive specs only in the sysConf
     * 
     * @param $xpath
     * @return null
     */
    public function getSysConf($xpath)
    {
        $fullPath = ROOT .'/'. $this::systemConfFileName;
        $this->_conf = json_decode(file_get_contents($fullPath));
        return $this->getConf($xpath);
    }

    /**
     * Returns the list of cli scripts that are configured
     */
    public function getCliList()
    {
        $cliList = array();
        foreach($this->_conf as $config) {
            if(isset($config->cli)) $cliList[] = $config;
        }
        return $cliList;
    }
}