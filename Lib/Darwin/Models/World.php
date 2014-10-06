<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/15/14
 * Time: 5:11 PM
 */
class World{

    const TIME_THE_WORLD_STARTS  = 2;
    const TIME_OF_THE_WORLD = 1000;

    const GENERATION_SIZE = 10;

    const NUMBER_GENERATIONS = 100;

    protected $_time = 2;

    protected $_genN = 0;

    protected $_worms = array();

    public function timer () {
        return $this->_time++;
    }

    /**
     * @param int $time
     */
    public function setTime($time)
    {
        $this->_time = $time;
    }

    /**
     * @param $genN
     * @internal param int $gen
     */
    public function setGen($genN)
    {
        $this->_genN = $genN;
    }

    /**
     * @return int
     */
    public function getGenN()
    {
        return $this->_genN;
    }

    public function genTimer() {
        return $this->_genN++;
    }

    /**
     * @return int
     */
    public function getTime()
    {
        return $this->_time;
    }


    /**
     * @param array $worms
     */
    public function setWorms($worms)
    {
        $this->_worms = $worms;
    }

    /**
     * @return array
     */
    public function getWorms()
    {
        if(empty($this->_worms)) {
            $worms = $this->createGeneration($this::GENERATION_SIZE);
        } else {
            $worms = $this->_worms;
        }
        $this->setWorms($worms);
        return $worms;
    }

    public function getWorm($id) {
        return $this->_worms[$id];
    }

    public function run() {
        $worms = $this->getWorms();
        while($this->getGenN() < $this::NUMBER_GENERATIONS) {
            echo ">> generation " .$this->getGenN() . " \n";
            $worms = $this->getRich($worms);
            $worms = $this->starveWorms($worms);
            $worms = $this->tinder($worms);
            $worms = $this->addMutants($worms);
            $this->setWorms($worms);
            $this->genTimer();
        }
    }

    public function getRich($worms) {
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
            echo $worm->getId() . " ". $worm->getPips() . " \n";
        }

        return $worms;
    }

    /**
     * Starve worms with no pips
     *
     * @param $worms
     * @return array
     * @throws Exception
     */
    public function starveWorms($worms) {

        // worms without pips starve
        $return = array();
        foreach ($worms as $id =>$worm) {

            if ($worm->getPips() > 0) {
                $return[$id] =  $worm;

            }
        }

        if(count($return) == 0) {
            //throw new Exception('all your worms died on generation ' . $this->getGenN());
            echo "\n they all died at gen ". $this->getGenN() . " creating new generation";
            $return = $this->createGeneration($this::GENERATION_SIZE);
        }

        return $return;
    }

    public function tinder($worms) {
        $pares = $this->paardCafe($worms);
        $worms = $this->bedRoom($pares);
        return $worms;
    }

    /**
     * Order females by titSize
     * Order males by dickSize
     * select next generation
     *
     * @param array $worms
     * @throws Exception
     * @return array $worms
     */
    public function paardCafe($worms) {
        $return = array();
        $females = $this->selectGender($worms, 0);
        $males   = $this->selectGender($worms, 1);

        if(count($females) == 0) {
            //throw new Exception('cockfest at the party');

            echo "\n cockfest, doing the gay-out \n";
            // the gay-out
            $females = $males;
        }

        // the woman chooses
        foreach($females as $titSize => $female) {
            if(count($males) > 0) {
                foreach($males as $dickSize => $male) {
                    $return[$male->getId()] = $female->getId();
                    echo $titSize . " " . $dickSize . " \n";
                    unset($males[$dickSize]);
                    continue;
                }
            }
        }
        return $return;
    }

    public function bedRoom($pares) {
        $newWorms = array();
        $i = 0;
        $pSize = floor($this::GENERATION_SIZE * 0.7);

        while($i < $this::GENERATION_SIZE) {
            $newId = $this->getGenN() . "_" . uniqid();
            foreach($pares as $maleId => $femaleId) {
                $female     = $this->getWorm($femaleId);
                $male       = $this->getWorm($maleId);
                $cum        = $male->cum();
                $newWorms[$newId] = $female->vagina($cum, $newId);

                if(count($newWorms) == $pSize) {
                    return $newWorms;
                }
            }

            $i++;
        }
        $this->setWorms($newWorms);
        return $newWorms;
    }

    public function addMutants($worms) {
        $pSize = ceil($this::GENERATION_SIZE * 0.3);
        $mutants = $this->createGeneration($pSize);
        $worms = array_merge($worms, $mutants);
        return $worms;
    }

    public function selectGender($worms, $gender) {
        $return = array();
        foreach($worms as $worm) {
            if($worm->getGender() == $gender) {
                $pips = (int) (round($worm->getPips(), 2) * 100);
                $return[$pips] = $worm;
            }
            krsort($return);
        }
        return $return;
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

        // create new worms
        $i = 0;
        while($i < ($size - count($fittestWorms))) {
            $newId = $this->getGenN() . "_" . uniqid();
            $generation[$newId] = new Worm($newId);
            $i++;
        }
        return $generation;
    }
}