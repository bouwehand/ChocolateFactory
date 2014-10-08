<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/16/14
 * Time: 2:36 PM
 */
class Cli{


    
    /**
     * Run a cli script 
     * 
     * @throws Exception
     */
    public function run() {
        $options = getopt('s:');
        if(!isset($options['s'])) {
            die("\n\n options -s<scriptname> not given for cli script \n\n");
        }
        $scriptName = $options['s'];
        $jsonConfig = JsonConfig::getInstance();
        $cliList = $jsonConfig->getCliList();
        if(empty($cliList)) throw new Exception('no cli configured');
        foreach($cliList as $config) {
            $cli = $config->cli;
            if(isset($cli->$scriptName)) {
                
                $scriptPaths[0] = APP_LIB . '/'. $config->module . '/cli/' . $config->cli->$scriptName;
                $scriptPaths[1] = CHOCOLATE_FACTORY_LIB. '/'. $config->module . '/cli/' . $config->cli->$scriptName;
                
                foreach($scriptPaths as $scriptPath) {
                    if(file_exists($scriptPath)) {
                        $this->executeCli($scriptPath);    
                    }
                }
            }
        }
        die("\n\n cli script '$scriptName' not configured for : $scriptPath \n\n");
    }
    
    private function executeCli($scriptPath) {
        echo "Chocolate Factory run $scriptPath \n\n";
        include_once($scriptPath);
        die();
    }
}