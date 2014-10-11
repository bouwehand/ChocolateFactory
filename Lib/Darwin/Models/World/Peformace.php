<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 10/8/14
 * Time: 10:41 PM
 */
class Performace extends World{

    function run() {

        $king = $this->getKing();
        $return = array();

        $this->setTime($this::TIME_THE_WORLD_STARTS);
        $aapl = new AAPL();
        while($this->getTime() < $this::TIME_OF_THE_WORLD) {

            $interval = $aapl->getInterval($this->getTime() -1, $this->getTime());
            $king->createVector($interval);
            $king->decide();
            $this->timer();

            // set data feed
            $return[$this->getTime()] = current($aapl->getStep($this->getTime()));
            $return[$this->getTime()]['opendPosition']  = $king->getOpendPosition();
            $return[$this->getTime()]['closedPosition'] = $king->getClosedPosition();
        }
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