<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/15/14
 * Time: 4:01 PM
 */

/**
 * Class Worm
 *
 * At the begining of a generation. A hundred worms are created with random values
 * At the end of a generation, the worm with the highst fittest lives on and duplictes
 * other worms are destroyed and recreated randomly
 *
 * When to many worms of a certain kind arise, lets say 95% some worms begin to produce mall offspring
 */
class Worm {

    const VECTOR_SIZE = 10;
    const OUTPUT_LAYER_SIZE = 1;

    const POS_BUY  = 'BUY';
    const POS_SELL = 'SELL';
    const POS_NONE = 'NONE';

    /**
     * @var
     */
    protected $_id;

    /*
     * 1 = male, 0 = female
     */
    protected $_gender = 0;

    protected $_vector      = array();

    protected $_weights     = array();

    protected $_hiddenLayer = array();

    protected $_interval;

    protected $_openedPositionRate;

    protected $_pips = 0;

    protected $_lastPips = 0;

    protected $_in = 0;

    protected $_openedPosition = 0;

    protected $_closedPosition = 0;

    /**
     * @return boolean
     */
    public function getClosedPosition()
    {
        return $this->_closedPosition;
    }

    /**
     * @return boolean
     */
    public function getOpendPosition()
    {
        return $this->_openedPosition;
    }

    /**
     * @param array $hiddenLayer
     */
    public function setHiddenLayer($hiddenLayer)
    {
        $this->_hiddenLayer = $hiddenLayer;
    }

    /**
     * @return array
     */
    public function getHiddenLayer()
    {
        return $this->_hiddenLayer;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * @param mixed $openRate
     */
    public function setOpenRate($openRate)
    {
        $this->_openedPositionRate = $openRate;
    }

    /**
     * @return mixed
     */
    public function getOpenRate()
    {
        return $this->_openedPositionRate;
    }


    /**
     * @return mixed
     */
    public function getIn()
    {
        return $this->_in;
    }

    /**
     * @param mixed $in
     */
    public function setIn($in)
    {
        $this->_in = $in;
    }


    /**
     * @param mixed $interval
     */
    public function setInterval($interval)
    {
        $this->_interval = $interval;
    }

    /**
     * @return mixed
     */
    public function getInterval()
    {
        return $this->_interval;
    }

    /**
     * @return mixed
     */
    public function getWeights()
    {
        return $this->_weights;
    }

    /**
     * @param mixed $weights
     */
    public function setWeights($weights)
    {
        $this->_weights = $weights;
    }

    public function getRandomWeight() {
        return (mt_rand(-9000, 9000) / 1000);
    }


    /**
     * @internal param \Generation $id id
     */
    function __construct() {
        $this->setId(uniqid());

        // create weights
        for($i = $this::VECTOR_SIZE;$i >= $this::OUTPUT_LAYER_SIZE; $i--) {
            $j = 0;
            while($j < $i) {
                $this->_weights[$i][$j] = $this->getRandomWeight();
                $j++;
            }
        }
    }


    /**
     * @return mixed
     */
    public function getPips()
    {
        return $this->_pips;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return int id
     */
    public function getId()
    {
        return $this->_id;
    }

    public function setVector($vector) {
        $this->_vector = $vector;
    }

    public function getVector() {
        return $this->_vector;
    }

    /**
     * @return int
     */
    public function getLastPips()
    {
        return $this->_lastPips;
    }

    /**
     *
     */
    public function decide() {

        $vector   = $this->getVector();
        $decision = $this->calculateWeights($vector);
        $this->handleAccount($decision);
    }

    public function handleAccount($decision) {
        $in = $this->getIn();
        $interval = current($this->getInterval());
        switch($decision) {
            case $this::POS_BUY :
                if($in == 0) {
                    $this->_in    = 1;
                    $this->_openedPosition  = 1;
                    $this->_closedPosition = 0;
                    $this->setOpenRate($interval['close']);
                } else {
                    $this->_openedPosition  = 0;
                    $this->_closedPosition = 0;
                }
                break;
            case $this::POS_SELL :
                if($in == 1) {
                    $this->_in    = 0;
                    $this->_openedPosition = 0;
                    $this->_closedPosition = 1;
                    $this->_lastPips = $interval['close'] - $this->getOpenRate();
                    $this->_pips = $this->_pips + $this->_lastPips;
                }else {
                    $this->_openedPosition  = 0;
                    $this->_closedPosition = 0;
                }
                break;
            default :
                $this->_openedPosition  = 0;
                $this->_closedPosition = 0;
        }
        //echo $decision . "\t Open: " . $this->getOpen() . " Close :" . $this->getClosedPosition() . " In: " . $this->getIn() . "\n";
    }

    public function calculateWeights($vector) {
        $weights     = $this->getWeights();
        $hiddenLayers = array();
        // go through the hidden ones
        for($i = $this::VECTOR_SIZE;$i >= $this::OUTPUT_LAYER_SIZE; $i--) {
            $weightLayer = $weights[$i];
            foreach($weightLayer as $j =>$weight) {

                // see doc;
                $o_o    = 0;
                $F      = 0;
                foreach($vector as $k => $value) {
                    $o_o =+ ($value * $weight);
                    $F =+ abs($weight);
                }

                $o_o = round($o_o * 1000);
                $F   = round($F * 1000);

                // fix division by zero error
                if($F != 0) {
                    $O = ($o_o / $F);
                }else $O = 0;

                $hiddenLayers[$i][$j] = $O;
            }
            $vector = $hiddenLayers[$i];
        }
        $vector = current($vector);
        if($vector > 0)  return $this::POS_BUY;
        if($vector == 0) return $this::POS_NONE;
        IF($vector < 0)  return $this::POS_SELL;
    }

    /**
     * @return array
     */
    public function cum() {
        $weights = $this->getWeights();
        $cum = array();
        foreach($weights as $k => $weightLayer) {
            $count = count($weightLayer);
            $sampleStart = mt_rand(0, 1);
            $sampleIndex = mt_rand($sampleStart, $count);
            for($i = $sampleStart; $i <= $sampleIndex; $i++) {
                if(isset($weightLayer[$i])) {

                    // mutation
                    if(mt_rand(0, 1)) {
                        $weightLayer[$i] = $this->getRandomWeight();;
                    }

                    // recombination
                    $cum[$k][$i] = $weightLayer[$i];
                }
            }
        }
        return $cum;
    }

    /**
     * @param $cum
     * @internal param $id
     * @internal param $idMale
     * @internal param $id
     * @return \Worm
     */
    public function vagina($cum) {

        $worm = new Worm();
        $weights = $this->getWeights();
        foreach($weights as $k => $weightLayer) {
            if(isset($cum[$k])) {
                $egg[$k] = array_replace($weightLayer, $cum[$k]);
            } else {
                $egg[$k] = $weightLayer;
            }
        }

        $worm->setWeights($egg);
        return $worm;
    }


}