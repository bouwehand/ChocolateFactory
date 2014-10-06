<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/6/14
 * Time: 11:23 AM
 */ 
class Darwin {

    /**
     * Initial run function.
     */
    public static function run() {
        
        echo "\n\n               DARWIN OPERATIONAL                \n\n";
        echo " One who peers into nature must have a hart of stone \n\n";    
        
        $world = new World();
        $world->run();
    }
}

