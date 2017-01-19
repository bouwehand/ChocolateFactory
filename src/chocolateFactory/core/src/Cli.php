<?php

namespace ChocolateFactory\Core;
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/16/14
 * Time: 2:36 PM
 */
class Cli {

    /**
     * Run a cli script
     *
     * $param array$params
     *
     * @throws \Exception
     */
    public function run(array $params) {
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
                include_once(ROOT. '/Lib/' . $config->module . '/cli/' . $config->cli->$scriptName);
                die();
            }
        }
        die("\n\n cli script '$scriptName' not configured\n\n");
    }
}