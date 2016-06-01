<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 10/5/14
 * Time: 12:49 PM
 */
class VectorSpace extends Darwin {

    protected $_vectors;

    /*
     * Generate the vector from interval data
     */
    public function __construct() {

    }

    public function getInstance()
    {
        $dataFeed = $this->getDataFeed();
        $this->setTime($this::TIME_THE_WORLD_STARTS);
        while($this->getTime() < $this::TIME_OF_THE_WORLD) {
            $interval = $dataFeed->getInterval($this->getTime() -2, $this->getTime());
            $vector = $this->createVector($interval);
            $this->setVector($this->getTime(), $vector);
            $this->timer();
        }
        // done, reset the clock
        $this->setTime($this::TIME_THE_WORLD_STARTS);
        return $this;
    }

    public function createVector($interval)
    {
        $vector = array();
        $lastBar = null;

        foreach($interval as $time => $bar) {
            if(!is_null($lastBar)) {

                // volumeD
                $volumeD = ($bar['volume'] - $lastBar['volume']) / $bar['volume'];
                $vector[] = $volumeD;

                // rangeD
                $range = $bar['high'] - $bar['low'];
                if(!$range) {
                    $rangeD = 0;
                } else {
                    $lastRange = $lastBar['high'] - $lastBar['low'];
                    $rangeD = ($range - $lastRange) / $range;
                }
                $vector[] = $rangeD;
//
//                // Moving avarage
//                if($bar['id'] > 5) {
//                    $datafeed = $this->getDataFeed();
//                    $interval2 = $datafeed->getInterval($bar['id'] -5, $bar['id']);
//                    $array = array();
//                    foreach($interval2 as $dataSet) {
//                        $array[] = $dataSet['close'];
//                    }
//                    $ma = Tools::movingAverage($array, 5);
//                    $ma = ($bar['close'] - $ma) / $bar['close'];
//                }else {
//                    $ma = 0;
//                }
//                $vector[] = $ma;

                if(!$rangeD) {
                    $mfiD = 0;
                } else {
                    $mfiD = $volumeD / $rangeD;
                }
                $vector[] = $mfiD;

                // Close D
                $closeD = ($bar['close'] - $lastBar['close']) / $bar['close'];
                $vector[] = $closeD;

                //Open D
                $openD = ($bar['open'] - $lastBar['open']) / $bar['open'];
                $vector[] = $openD;
            }
            $lastBar = $bar;
        }
        return $vector;
    }

    public function setVector($index, $value) {
        $this->_vectors[$index] = $value;
    }

    public function getVector($index) {
        return $this->_vectors[$index];
    }

    public function getVectors() {
        return $this->_vectors;
    }
}