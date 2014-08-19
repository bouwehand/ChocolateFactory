<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/18/14
 * Time: 12:30 PM
 */
class ClarcInstrument {
    /**
     * @var
     */
    protected $_name;
    protected $_step;
    protected $_rate;
    protected $_delta;

    /**
     * @var
     */
    protected $_trendLength;
    protected $_offset;

    /**
     * @var
     */
    static protected $_instruments;
    static protected $_relations;

    static public function create($name)
    {
        $new = new self;
        $new->setName($name);
        self::addInstrument($new);
        return $new;
    }

    static public function reset()
    {
        self::$_instruments = null;
        self::$_relations   = null;
    }

    public static function createRelations($new){

        // create the relation instuments
        $instruments = self::getInstruments();
        if(!empty($instruments)) {
            foreach($instruments as $instrument) {
                if($instrument->getName() != $new->getName()) {
                    $relation = new self;
                    $relationName = $new->getName() . ucfirst($instrument->getName());
                    $relation->setName($relationName);
                    $rate = $new->getRate();
                    $lastRate = $instrument->getRate();
                    $relation->setDelta($rate, $lastRate);
                    self::addRelation($relation);
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public static function getRelations()
    {
        return self::$_relations;
    }

    /**
     * @param mixed $relation
     */
    public static function addRelation($relation)
    {
        self::$_relations[] = $relation;
    }

    /**
     * Returns an relational instrument by name
     *
     * @param $name
     * @return null
     * @throws Exception
     */
    static public function getRelationByName($name)
    {

        $instruments = self::getRelations();

        if (empty($instruments)) {
            throw new Exception('no instuments!');
        }

        foreach ($instruments as $instrument) {
            if ($instrument->getName() == $name) {
                return $instrument;
            }
        }
        return null;
    }

    /**
     * @param mixed $child
     */
    public function addChild($child)
    {
        $this->_children[] = $child;
    }

    /**
     * @return mixed
     */
    static public function getInstruments()
    {
        return self::$_instruments;
    }

    /**
     * Returns an instrument by name
     *
     * @param $name
     * @return null
     * @throws Exception
     */
    static public function getInstrumentByName($name)
    {

        $instruments = self::getInstruments();

        if (empty($instruments)) {
            throw new Exception('no instuments!');
        }

        foreach ($instruments as $instrument) {
            if ($instrument->getName() == $name) {
                return $instrument;
            }
        }
        return null;
    }

    /**
     * @param mixed $instruments
     */
    public function setInstruments($instruments)
    {
        $this->_instruments = $instruments;
    }

    /**
     * @param mixed $instruments
     */
    static public function addInstrument($instruments)
    {
        self::$_instruments[] = $instruments;
    }

    /**
     * @param $first
     * @param $last
     * @internal param mixed $delta
     */
    public function setDelta($first, $last)
    {
        $this->_delta = ($last - $first) / $last;
    }

    /**
     * @return mixed
     */
    public function getDelta()
    {
        return $this->_delta;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->_rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->_rate;
    }

    /**
     * @param mixed $step
     */
    public function setStep($step)
    {
        $this->_step = $step;
    }

    /**
     * @return mixed
     */
    public function getStep()
    {
        return $this->_step;
    }

    /**
     * @param mixed $trend
     */
    public function setTrend($trend)
    {
        $this->_trend = $trend;
    }

    /**
     * @return mixed
     */
    public function getTrend()
    {
        return $this->_trend;
    }

    /**
     * @param $trendLength
     * @internal param mixed $tendLength
     */
    public function setTrendLength($trendLength)
    {
        $this->_trendLength = $trendLength;
    }

    /**
     * @return mixed
     */
    public function getTrendLength()
    {
        return $this->_trendLength;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    public function loadData($step)
    {

        if($step >= $this->getTrendLength() + $this->getOffset()) {
            $data = new DataHandler();
            $trend = $data->getTrend(
                clarc::TRADING_CURRENCY_CODE,
                $step,
                $this->getTrendLength(),
                $this->getOffset()
            );
            $rate = Tools::movingAverage($trend, $this->getTrendLength());
        } else $rate = 0;
        $this->setRate($rate);
        return $rate;
    }

}