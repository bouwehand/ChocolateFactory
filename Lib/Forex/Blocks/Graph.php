<?php
/**
 * Created by PhpStorm.
 * User: vanax
 * Date: 5/14/14
 * Time: 7:45 PM
 */
class Graph {

    protected $table;

    public function addRow($array) {
        $this->table[] = '[' . implode(",", $array) . ']';
        return $this;
    }

    public function setTitles($array) {
        $this->table[0] = '[label: \'' . implode("','", $array) . '\' ]';
        return $this;
    }

    public function setAnnotation($array) {
        $this->table[0] = '[\'' . implode("','", $array) . '\' ]';
        //role: domain,     data,    annotation,  annotationText,    data,  annotation, annotationText

    }


    public function render() {


        return '';
    }

    /**
     * Gives all historic data as google chart format
     *
     */
    public function toGoogleGraph() {
        $stepNumber = self::FIRST_STEP_NUM;
        $horizontalArray = array();
        while($stepNumber < self::MAX_STEP_NUM) {
            $step = $this->loadStep($stepNumber);
            $array = array();
            if($stepNumber == 1) {
                foreach ( $step as $index => $exchangeRate ) {
                    switch($index) {
                        case('id') :
                            break;
                        case 'datetime' :
                            $array[] = "'". $index. "'" ;
                            break;
                        case "USD" :
                            $array[] = "'" . $index. "'" ;
                            break;
                        case "JPY" :
                            //$array[] = "'" . $index. "'" ;
                            break;

                    };
                }
                $horizontalArray[] = "[" . implode("," , $array) ."] \n";
                $array = array();
            }
            foreach ( $step as $index => $exchangeRate ) {
                switch($index) {
                    case('id') :
                        break;
                    case 'datetime' :
                        $exchangeRate = "'". date("Y-m-d", $exchangeRate). "'";
                        $array[] = $exchangeRate ;
                        break;
                    case "USD" :
                        $array[] = $exchangeRate ;
                        break;
                    case"JPY":
                        // $array[] = $exchangeRate ;
                        break;

                };
            }
            $horizontalArray[] =  "[" . implode("," , $array) ."] \n";
            $stepNumber++;
        }
        return implode(',', $horizontalArray);
    }

}