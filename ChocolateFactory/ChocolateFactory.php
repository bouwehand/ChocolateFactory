<?php
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

        // load the core
        $this->_loadCore();
      
        // Run the mvc web framework if we are in the browser
        // else run the cli version of the framework
        if(php_sapi_name() == 'cli') {
            $cli = new Cli();
            $cli->run();
        } else {
            //run mvc
            $mvc = new MVC();
            $mvc->run();
        }
    }
    
    /** Load Core functionality */
    private function _loadCore() {
        
        //load configs
        require_once(CHOCOLATE_FACTORY_CORE . '/JsonConfig.php');
        $jsonConfig = JsonConfig::getInstance();
        $jsonConfig->loadDir(CHOCOLATE_FACTORY_LIB);
        $jsonConfig->loadDir(APP_LIB);
        if (!($jsonConfig->checkSystemConf())) {
            $jsonConfig->writeSystemConf();
        }
        
        //load classes
        require_once(CHOCOLATE_FACTORY_CORE . '/ClassLoader.php');
        spl_autoload_register('ClassLoader::autoload');
    } 
}