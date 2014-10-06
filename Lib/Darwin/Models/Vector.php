<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 10/5/14
 * Time: 12:49 PM
 */
class Vector {

    protected $_array;

    /*
     * Generate the vector from interval data
     */
    public function __construct($interval) {

        $range = $interval[1]['high'] - $interval[1]['low'];
        $lastRange = $interval[0]['high'] - $interval[0]['low'];

        // strip zero ranges for halted stock
        if($range == 0) $range = 1;
        if($lastRange == 0) $range =1;


        // open - low
        $openLow = ($interval[1]['open'] - $interval[1]['low']) / $range;
        $this->setValue(0 , $openLow);

        //High – open
        $highOpen = ($interval[1]['high'] - $interval[1]['open']) / $range;
        $this->setValue(1 , $highOpen);

        // high – close
        $value = ($interval[1]['high'] - $interval[1]['close']) / $range;
        $this->setValue(2 , $value);

         // Close – low
        $value = ($interval[1]['close'] - $interval[1]['low']) / $range;
        $this->setValue(3 , $value);

        // open-close D
        $value = ($interval[1]['close'] - $interval[1]['low']) / $range;
        $this->setValue(4 , $value);

        // Volume D
        $value = ($interval[1]['volume'] - $interval[0]['volume']) / $interval[1]['volume'];
        $this->setValue(5 , $value);

        // Range D
        $value = ($range - $lastRange) / $range;
        $this->setValue(6 , $value);

        // MFI D
        $value = $this->getValue(6) / $this->getValue(5);
        $this->setValue(7 , $value);

        // Close D
        $value = ($interval[1]['close'] - $interval[0]['close']) / $interval[1]['close'];
        $this->setValue(8 , $value);

        //Open D
        $value = ($interval[1]['open'] - $interval[0]['open']) / $interval[1]['open'];
        $this->setValue(9 , $value);

    }

    public function setValue($index, $value) {
        $this->_array[$index] = $value;
    }

    public function getValue($index) {
        return $this->_array[$index];
    }

    public function getValues() {
        return $this->_array;
    }

}