<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/6/14
 * Time: 11:23 AM
 */ 
class Darwin {

    const TIME_THE_WORLD_STARTS  = 3;
    const TIME_OF_THE_WORLD      = 1000;

    protected $_time;

    protected static $_dataFeed;

    protected static $_vectorSpace;

    /**
     * @return mixed
     */
    public function getDataFeed()
    {
        return $this::$_dataFeed;
    }

    /**
     * @return mixed
     */
    public static function getVectorSpace()
    {
        return self::$_vectorSpace;
    }

    /**
     * Initial run function.
     */
    public static function run() {
        
        echo "\n\n               DARWIN OPERATIONAL                \n\n";
        echo " One who peers into nature must have a hart of stone \n\n";
        self::$_dataFeed = new AAPL();

        $vectorSpace = new VectorSpace();
        self::$_vectorSpace = $vectorSpace->getInstance();

        $world = new World();
        $world->spin();
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
     * @return int
     */
    public function getTime()
    {
        return $this->_time;
    }
}

