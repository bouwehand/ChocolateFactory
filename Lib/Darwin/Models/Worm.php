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

    /**
     * @var
     */
    protected $_id;

    /**
     * @var
     */
    protected $_fitness;

    /**
     * @var
     */
    protected $_gen;

    protected $_market;

    /**
     * @param mixed $market
     */
    public function setMarket($market)
    {
        $this->_market = $market;
    }

    /**
     * @return mixed
     */
    public function getMarket()
    {
        return $this->_market;
    }


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
        $gen = $this->createGen();
        $this->setGen($gen);
    }

    public function createGen()
    {

        $gen['buy'] = $this->createChromosome();
        $gen['sell'] = $this->createChromosome();

        return $gen;
    }

    public function createChromosome()
    {
        $nodeNames = array(
            "currency", "lip", "teeth", "jaw"
        );

        $chromosome = array();
        foreach($nodeNames as $name) {

            $nucloid = new stdClass();
            $nucloid->name = 'last' . ucfirst($name);
            $nucloid->min = $this->createGenValue();
            $nucloid->max = $this->createGenValue();
            $chromosome[] = $nucloid;
            foreach($nodeNames as $second) {
                if($second != $name) {
                    $nucloid = new stdClass();
                    $nucloid->name = $name . ucfirst($second);
                    $nucloid->min = $this->createGenValue();
                    $nucloid->max = $this->createGenValue();
                    $chromosome[] = $nucloid;
                }
            }
        }
        return $chromosome;
    }


    public function createGenValue()
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

        $market = $this->getMarket();

        $clarc = new Clarc();

        $clarc->infuse($this->getGen());
        $market->setClarc($clarc);
        $market->run();
        $finalResult = $market->getCloseValue();
        $this->setFitness($finalResult);
        return $this;
    }
}