<?php

/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/18/14
 * Time: 12:30 PM
 */
class ClarcInstrument
{


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
    static protected $_highLow;

    protected $_trendDelta;


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
        self::$_relations = null;
    }

    static public function createRelations($new)
    {

        // create the relation instuments
        $instruments = self::getInstruments();
        if (!empty($instruments)) {
            foreach ($instruments as $instrument) {
                if ($instrument->getName() != $new->getName()) {
                    $relation = new self;
                    $relation->setStep($instrument->getStep());
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
    static public function getRelations()
    {
        return self::$_relations;
    }

    /**
     * @param mixed $relation
     */
    static public function addRelation($relation)
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
     * @return mixed
     */
    static public function getHighLow()
    {
        return self::$_highLow;
    }

    /**
     * @param $name
     * @return mixed
     */
    static public function getHighLowByName($name)
    {
        if (!empty(self::$_highLow)) {
            foreach (self::$_highLow as $i => $highlow) {
                if ($name == $highlow->name) {
                    $highlow->id = $i;
                    return $highlow;
                }
            }
        }
        return null;
    }

    /**
     * @param $instrument
     * @internal param mixed $high
     */
    static public function setHighLow($instrument)
    {
        $name = $instrument->getName();
        $highLow = self::getHighLowByName($name);

        // no highlow? create one
        if (empty($highLow)) {
            self::createHighLow($instrument);
            return;
        }

        if (!$highLow->direction && $instrument->getRate() <= $highLow->breakout) {

            // its not a new trend, continue
            $highLow->newTrend = 0;
            $highLow->breakout = $instrument->calculateBreakout();
            self::addHighlow($highLow);
            return;
        }

        if (!$highLow->direction && $instrument->getRate() > $highLow->breakout) {
            //var_dump($instrument->getRate());
            //var_dump($highLow->breakout);
            //die();
            self::createHighLow($instrument, $highLow);
            return;
        }

        if ($highLow->direction && $instrument->getRate() >= $highLow->breakout) {


            $highLow->newTrend = 0;
            $highLow->breakout = $instrument->calculateBreakout();
            self::addHighlow($highLow);
            return;
        }

        if ($highLow->direction && $instrument->getRate() < $highLow->breakout) {
            self::createHighLow($instrument, $highLow);
            return;
        }

        echo "we have a breakout";
        var_dump($instrument->getRate());
        var_dump($highLow->breakout);
        die();
    }

    /**
     * @param $highLow
     */
    static public function addHighLow($highLow)
    {
        self::$_highLow[$highLow->id] = $highLow;
    }

    /**
     * @param $instrument
     * @param null $highLow
     * @return null|stdClass
     */
    static public function createHighLow($instrument, $highLow = null)
    {
        if ($highLow == null) {
            $highLow = new stdClass();
            $highLow->id = count(self::getHighLow());
        }
        $highLow->newTrend = 1;
        $highLow->name = $instrument->getName();
        $highLow->rate = $instrument->getRate();
        $highLow->step = $instrument->getStep();
        $highLow->delta = $instrument->getDelta();

        // trend apears up
        if ($highLow->delta > 0) {
            $highLow->direction = 1;
        }

        // trend apears down
        if ($highLow->delta < 0) {
            $highLow->direction = 0;
        }
        $highLow->breakout = $instrument->calculateBreakout();
        self::addHighlow($highLow);
        return $highLow;
    }

    /**
     * @return float new breakout rate
     */
    public function calculateBreakout()
    {
        $newRate = $this->getRate() + ($this->getRate() * 0.0005);
        return $newRate;
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
        self::$_instruments = $instruments;
    }

    /**
     * @param mixed $instruments
     */
    static public function addInstrument($instruments)
    {
        self::$_instruments[] = $instruments;
    }

    /**
     * @param $rate
     * @param $lastRate
     * @internal param mixed $delta
     */
    public function setDelta($rate, $lastRate)
    {
        $this->_delta = ($rate - $lastRate) / $rate;
    }

    /**
     * @return mixed
     */
    public function getDelta()
    {
        return $this->_delta;
    }

    /**
     * Sets the long delta over the trend
     */
    public function setTrendDelta()
    {
        $highLow = self::getHighLowByName($this->getName());
        $this->_trendDelta = $this->getRate() - $highLow->rate / $this->getRate();
    }

    /**
     * @return mixed
     */
    public function getTrendDelta()
    {
        return $this->_trendDelta;
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

    /**
     * Loads the rate for a trend tool trough moving avarage
     *
     * @param $step
     * @return float|int
     */
    public function loadData($step)
    {

        if ($step >= $this->getTrendLength() + $this->getOffset()) {
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