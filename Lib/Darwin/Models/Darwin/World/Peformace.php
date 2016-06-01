<?php
require_once '/var/www/html/Lib/Darwin/Models/Darwin/World.php';
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 10/8/14
 * Time: 10:41 PM
 */
class Performace extends World{

    function spin() {


        $queen = $this->getKing();
        $return = array();
        $this->setTime($this::TIME_THE_WORLD_STARTS);
        $dataFeed = new AAPL();
        $vectorSpace = new VectorSpace();
        $this::$_dataFeed = $dataFeed;
        $vectorSpace = $vectorSpace->getInstance();
        while($this->getTime() < $this::TIME_OF_THE_WORLD) {
            $step = $dataFeed->getStep($this->getTime());
            $vector = $vectorSpace->getVector($this->getTime());
            $queen->setVector($vector);
            $queen->setInterval($step);
            $queen->decide();
            $this->timer();
            // set data feed
            //echo $queen->getPips() . " \n";
            $return[$this->getTime()] = current($dataFeed->getStep($this->getTime()));
            $return[$this->getTime()]['opendPosition']  = $queen->getOpendPosition();
            $return[$this->getTime()]['closedPosition'] = $queen->getClosedPosition();
        }
        //die();
        return $return;
    }

    public function getKing() {

        $worm = new Worm();
        $weights = file_get_contents(APP_LIB . '/Darwin/doc/king.txt');
        //$weights = file_get_contents(APP_LIB . '/Darwin/doc/lozer.txt');
        $weights = unserialize($weights);
        $worm->setWeights($weights);
        return $worm;

    }


}