<?php
require_once(APP_LIB . '/Darwin/Models/Darwin.php');
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/15/14
 * Time: 5:11 PM
 */
class World extends Darwin {

    const GENERATION_SIZE = 20;

    const NUMBER_GENERATIONS = 10000;

    protected $_genN = 0;

    protected $_worms = array();

    /**
     * She is the best
     *
     * @var worm object
     */
    protected $_oldQueen;

    /**
     * Set the Queen if score is higher
     *
     * @param $newQueen
     * @internal param $newKing
     * @return bool
     * @internal param \worm $oldKing
     */
    public function setOldQueen($newQueen)
    {
        if(is_null($this->_oldQueen)) {
            $this->_oldQueen = $newQueen;
        }

        if($newQueen->getPips() >= $this->_oldQueen->getPips()){
            $this->_oldQueen = $newQueen;
            return true;
        }
        return false;

    }

    /**
     * @return \worm
     */
    public function getOldQueen()
    {
        return $this->_oldQueen;
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

    /**
     *
     */
    public function spin() {
        $worms = $this->getWorms();
        while($this->getGenN() < $this::NUMBER_GENERATIONS) {
            echo "\n\n >> generation " .$this->getGenN() . " \n\n";
            $worms = $this->getRich($worms);
            $worms = $this->starveWorms($worms);
            if($worms) {
                $worms = $this->selectFittest($worms);
            } else {
                echo "\n\n all worms died, create new generation \n\n";
                $worms = $this->createGeneration($this::GENERATION_SIZE);
            }
            $this->setWorms($worms);
            $this->genTimer();
        }
    }

    /**
     *
     *
     */
    public function getRich($worms) {
        $this->setTime($this::TIME_THE_WORLD_STARTS);
        $dataFeed = $this->getDataFeed();

        $vectorSpace = $this->getVectorSpace();
        while($this->getTime() < $this::TIME_OF_THE_WORLD) {
            $step = $dataFeed->getStep($this->getTime());
            foreach ($worms as $k => $worm) {
                $vector = $vectorSpace->getVector($this->getTime());
                $worm->setVector($vector);
                $worm->setInterval($step);
                $worm->decide();

                // kill the lozers
                if($worm->getPips() < -5) {
                   unset($worms[$k]);
                }
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
            return false;
        }
        return $return;
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
    public function selectFittest($worms) {

        $worms = $this->orderByFitness($worms);
        $promQueen = array_shift($worms);
        // Has a new Queen risen?
        if($this->setOldQueen($promQueen) ) {
            echo "\n Queen is : " .
                $this->getOldQueen()->getPips() .
                " \n\t" .
                $sq = serialize($this->getOldQueen()->getWeights());
                file_put_contents(APP_LIB . "/Darwin/doc/king.txt", $sq);

        } else {
            $promQueen = $this->getOldQueen();
        };
        $worms = $this->bedRoom($promQueen, $worms);

        return $worms;
    }

    /**
     * @param $promQueen
     * @param $worms
     * @internal param $promKing
     * @return array
     */
    public function bedRoom($promQueen, $worms) {
        $newWorms = array();
        $i = 0;
        $pSize = $this::GENERATION_SIZE;
        while($i < $this::GENERATION_SIZE) {
            foreach($worms as $worm) {
                $cum = $worm->cum();
                $newWorm = $promQueen->vagina($cum);
                $newWorms[$newWorm->getId()] = $newWorm;
                if(count($newWorms) == $pSize) {
                    return $newWorms;
                }
            }
            $i++;
        }
        return $newWorms;
    }

    /**
     * Returns sorted array of worms ordered by fitness scores
     *
     * @param $worms
     * @return array
     */
    public function orderByFitness($worms) {
        $return = array();

        foreach($worms as $worm) {
            $pips = (int) (round($worm->getPips(), 2) * 100);
            $return[$pips ] = $worm;
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
            $worm = new Worm();
            $generation[$worm->getId()] = $worm;
            $i++;
        }
        return $generation;
    }
}
