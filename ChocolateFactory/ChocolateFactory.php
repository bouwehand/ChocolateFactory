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
        $this->_loadClasses(CHOCOLATE_FACTORY_CORE);

        //load configs
        $jsonConfig = JsonConfig::getInstance();
        $jsonConfig->loadDir(CHOCOLATE_FACTORY_LIB);
        $jsonConfig->loadDir(APP_LIB);
        $jsonConfig->writeSystemConf();

        //load class library
        $this->_loadClasses(CHOCOLATE_FACTORY_LIB);
        $this->_loadClasses(APP_LIB);

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

    /**
     * LoadClasses
     *
     * loads all the classes in a given directory
     * Dirs that start with a capital are autoloaded
     *
     *
     * @param  $dirAddress
     * @return true;
     */
    protected function _loadClasses($dirAddress){
        if ($handle = opendir($dirAddress)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." ) {

                    if(preg_match("/^[A-Z][a-zA-Z0-9]+$/", $entry) && is_dir($dirAddress . '/' . $entry)) {
                        $this->_loadClasses($dirAddress. '/' . $entry);
                    }

                    if(preg_match("/^[A-Z][a-zA-Z0-9]+\.php$/", $entry)){
                        require_once($dirAddress. '/' . $entry);
                    }

                }
            }
            closedir($handle);
        }

        return true;
    }
}