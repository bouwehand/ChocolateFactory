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

    protected $_openRate;

    protected $_pips = 0;

    protected $_in = false;



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
        $this->_openRate = $openRate;
    }

    /**
     * @return mixed
     */
    public function getOpenRate()
    {
        return $this->_openRate;
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


    /**
     * @internal param \Generation $id id
     */
    function __construct() {
        $this->setId(uniqid());
        $this->setGender(mt_rand(0,1));

        // create weights
        for($i = $this::VECTOR_SIZE;$i >= $this::OUTPUT_LAYER_SIZE; $i--) {
            $j = 0;
            while($j < $i) {
                $this->_weights[$i][$j] = (mt_rand(-1000, 1000) / 1000);
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

    public function createVector($interval) {
        $this->setInterval($interval[1]);
        $vector = new Vector($interval);
        $this->_vector = $vector->getValues();
    }

    public function getVector() {
        return $this->_vector;
    }

    public function decide() {

        $vector      = $this->getVector();

        $decision = $this->calculateWeights($vector);
        //echo $this->getId() . " " .$decision . "\n";

        if($decision == 'BUY' && $this->getIn() == false) {
            $this->setIn(true);
            $interval = $this->getInterval();
            $this->setOpenRate($interval['close']);

        }

        if($decision == 'SELL' && $this->getIn() == true) {
            $this->setIn(false);
            $interval = $this->getInterval();
            $pips = $this->getOpenRate() - $interval['close'];
            //echo "pips >>" .$this->_pips . " " . $pips .  " \n ";
            $this->_pips = $this->_pips + $pips;
        }
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
        if($vector > 0)  return "BUY";
        if($vector == 0) return "NONE";
        IF($vector < 0)  return "SELL";
    }

    public function cum() {
        $weights = $this->getWeights();
        $cum = array();
        foreach($weights as $k => $weightLayer) {
            $count = (floor(count($weightLayer) /2) );
            if(mt_rand(0,1)) {
                $cum[$k] = array_slice($weightLayer, $count, $count, true);
            }else {
                $cum[$k] = array_slice($weightLayer, 0, $count, true);
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
             $egg[$k] = array_replace($weightLayer, $cum[$k]);
        }

        $worm->setWeights($egg);
        return $worm;
    }


}