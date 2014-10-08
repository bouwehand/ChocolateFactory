<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 10/8/14
 * Time: 10:41 PM
 */
class Peformace extends World{

    function run() {

        $this->getKing();
        die();

        $this->setTime($this::TIME_THE_WORLD_STARTS);
        while($this->getTime() < $this::TIME_OF_THE_WORLD) {
            $aapl = new AAPL();
            $interval = $aapl->getInterval($this->getTime() -1, $this->getTime());
            foreach ($worms as $worm) {
                $worm->createVector($interval);
                $worm->decide();
            }
            $this->timer();
        }

        // close all positions
        foreach ($worms as $worm) {
            $worm->setIn(false);
            echo "closed: ". $worm->getId() . " ". $worm->getPips() . " \n";
        }

        return $worms;

    }

    public function getKing() {

        $worm = new Worm();
        $egg = file_get_contents(APP_LIB . '/Darwin/doc/king.txt');
        $egg = unserialize($egg);
        die(var_dump($egg));

    }


}