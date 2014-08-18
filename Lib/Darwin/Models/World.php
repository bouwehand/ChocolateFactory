<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/15/14
 * Time: 5:11 PM
 */
class World{

    const TIME_OF_OF_THE_WORLD = 1000;

    const GENERATION_SIZE = 100;

    protected $_time = 1;

    public function timer () {
        return $this->_time++;
    }

    /**
     * Create a generation of worms
     *
     * include fittest worms of last generation
     *
     * @param $size
     * @param array $fittestWorms
     * @return array
     * @internal param array $fittest
     */
    public function createGeneration($size, Array $fittestWorms = null) {

        $generation = array();

        // handle fittest worms
        if($fittestWorms) {
            foreach($fittestWorms as $fittest) {
                $generation[] = Worm::create()->setData($fittest);
            }
        }

        // create new worms
        $i = 0;
        while($i < ($size - count($fittestWorms))) {
            $generation[$i] = new Worm($i);
            $i++;
        }
        return $generation;
    }

    /**
     * @param array $worms
     * @return array $results
     */
    public function goForth($worms) {
        foreach($worms as $worm) {
            $oldWorms[$worm->getId()] = $worm->play();
        }
        return $oldWorms;
    }

    public function select($oldWorms) {

        foreach($oldWorms as $i => $oldWorm) {
            $fitness = $oldWorm->getFitness();

            // lower than 100 we must kill
            if($fitness <= 100) {
                unset($oldWorms[$i]);
            }
            die(var_dump($oldWorms));
        }

    }

}