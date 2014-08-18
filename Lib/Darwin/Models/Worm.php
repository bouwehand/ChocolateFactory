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

    protected $_id;


    protected $_fitness;

    protected $_gen;

    protected $_lastCurrencyRate;

    /**
     * @param int $gen
     */
    public function setGen($gen)
    {
        $this->_gen = $gen;
    }

    /**
     * @return int
     */
    public function getGen()
    {
        return $this->_gen;
    }

    /**
     * @param int $gen
     */
    public function addGen($gen)
    {
        $this->_gen[] = $gen;
    }

    /**
     * @param $id Generation id
     */
    function __construct($id) {

        $this->setId($id);

        $nodeNames = array(
          "currency", "lip", "teeth", "jaw"
        );

        foreach($nodeNames as $name) {
            $nodes[$name]['last']['min'] = $this->createValue();
            $nodes[$name]['last']['max'] = $this->createValue();;
            foreach($nodeNames as $second) {
                if($second != $name) {
                    $nodes[$name][$second]['min'] = $this->createValue();
                    $nodes[$name][$second]['max'] = $this->createValue();;
                }
            }
        }

        $this->setGen($nodes);
        die(var_dump($this));
    }

    public function createValue()
    {
        $dice = mt_rand(1, 3);
        switch($dice) {
            case 1:
                $val = mt_rand(0, 999) / 1000;
            break;
            case 2:
                $val = -1 * (mt_rand(0, 999) / 1000);
            break;
            case 3:
                $val = null;
            break;
        }
        return $val;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return \Generation
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $fitness
     */
    public function setFitness($fitness)
    {
        $this->_fitness = $fitness;
    }

    /**
     * @return mixed
     */
    public function getFitness()
    {
        return $this->_fitness;
    }

    /**
     * Go out and play
     */
    public function play() {
        $market = new Market();
        $market->run();
        $finalResult = $market->getCloseValue();
        $this->setFitness($finalResult);
        return $this;
    }
}