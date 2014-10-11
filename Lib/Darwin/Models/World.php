<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/15/14
 * Time: 5:11 PM
 */
class World {

    const TIME_THE_WORLD_STARTS  = 2;
    const TIME_OF_THE_WORLD = 1000;

    const GENERATION_SIZE = 20;

    const NUMBER_GENERATIONS = 20;

    protected $_time = 2;

    protected $_genN = 0;

    protected $_worms = array();

    protected $_dataFeed;

    /**
     * She is the best
     *
     * @var worm object
     */
    protected $_oldQueen;

    /**
     * @param mixed $dataFeed
     */
    public function setDataFeed($dataFeed)
    {
        $this->_dataFeed = $dataFeed;
    }

    /**
     * @return mixed
     */
    public function getDataFeed()
    {
        return $this->_dataFeed;
    }


    /**
     * Set the king if score is higher
     *
     * @param $newKing
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

        // initialize datafeed
        $dataFeed = new AAPL();
        $this->setDataFeed($dataFeed);

        $worms = $this->getWorms();
        while($this->getGenN() < $this::NUMBER_GENERATIONS) {
            echo "\n\n >> generation " .$this->getGenN() . " \n\n";
            $worms = $this->getRich($worms);
            $worms = $this->starveWorms($worms);
            $worms = $this->selectFittest($worms);
            //$worms = $this->addMutants($worms);
            $this->setWorms($worms);
            $this->genTimer();
        }
    }

    public function getRich($worms) {
        $this->setTime($this::TIME_THE_WORLD_STARTS);
        $dataFeed = $this->getDataFeed();
        while($this->getTime() < $this::TIME_OF_THE_WORLD) {
            $interval = $dataFeed->getInterval($this->getTime() -1, $this->getTime());
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
        // Has a new Queen rissen?
        if($this->setOldQueen($promQueen) ) {
            echo "\n Queen is : " .
                $this->getOldQueen()->getPips() .
                " \n\t" .
                serialize($this->getOldQueen()->getWeights());

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

    public function addMutants($worms) {
        $pSize = ceil($this::GENERATION_SIZE * 0.3);
        $mutants = $this->createGeneration($pSize);
        $worms = array_merge($worms, $mutants);
        return $worms;
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
