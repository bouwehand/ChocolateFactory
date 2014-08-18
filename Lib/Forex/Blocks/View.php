<?php
/**
 * Created by PhpStorm.
 * User: vanax
 * Date: 5/13/14
 * Time: 6:14 PM
 */

 class  DataHandler_View{

    /**
     * Gives all historic data as csv
     *
     */
    public function toCsv() {
        $stepNumber = self::FIRST_STEP_NUM;
        while($stepNumber < self::MAX_STEP_NUM) {
            $step = $this->loadStep($stepNumber);
            if($stepNumber == 1) {
                $array = array();
                foreach ( $step as $index => $exchangeRate ) {
                    $array[] = $exchangeRate['currency'];
                }
                echo implode(";" , $array) ."\n";
            }
            $array = array();
            foreach ( $step as $index => $exchangeRate ) {
                $array[] = $exchangeRate['rate'];
            }
            echo implode(";" , $array) ."\n";
            $stepNumber++;
        }
    }

}