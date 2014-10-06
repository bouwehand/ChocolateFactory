<?php
/**
 * Class tools
 *
 * Tools that a trader applies to market data
 *
 * this is is a static class for using static manipulation
 */
class Tools {

    const ARRAY_SLICE_START = 0;

    /**
     * @param $array
     * @param $range
     * @throws Exception
     * @return float
     */
    public static function movingAverage($array, $range)
    {
        if($range > count($array)) {
            throw new Exception('range ' . $range . ' bigger than array' . print_r($array, 1));
        }
        $array = array_slice($array, self::ARRAY_SLICE_START, $range, true);
        return array_sum($array) / $range;
    }

    public static function average($array) {
        return array_sum($array) / count($array);
    }

    /**
     * Find the difference in an array
     *
     * @param $array
     * @return mixed
     */
    public static function difference($array) {

        $difference =  current($array) - end($array);
        return $difference;
    }

    /**
     * Return the liniar transformation
     *
     * @param $array
     * @return mixed
     */
    public static function liniarDelta($array) {
        return current($array) - end($array) / count($array);
    }

    /**
     * Return of an array de interval value of each interval
     *
     * @param $array intervals
     *
     */
    public static function interval($array) {
        foreach($array as $key => $value) {
            if(isset($lastkey)) {
                $intervals[$lastkey] = $value - $array[$lastkey];
            }
            $lastkey = $key;
        }
        return $intervals;
    }

    /**
     * return highest value of array
     *
     * @param $array
     * @internal param $step
     * @return mixed
     */
    public static function highest($array) {
        asort($array);
        return end($array);
    }

    /**
     * return lowest value of array
     *
     * @param $array
     * @internal param $step
     * @return mixed
     */
    public static function lowest($array) {
        arsort($array);
        return end($array);
    }
}


