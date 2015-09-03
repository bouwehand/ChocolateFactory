<?php
/**
 * Financial.php
 *
 * <description>
 *
 * @category Youwe Development
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 9/2/15
 *
 */
class Tool_Financial
{
    /**
     * Average of the values in an Array
     *
     * @param $array
     * @return float
     */
    public static function average(Array $array) {
        return array_sum($array) / count($array);
    }

    /**
     * @param $array
     * @param $range
     * @throws Exception
     * @return float
     */
    public static function movingAverage(Array $array, $range= 0)
    {
        if($range > count($array)) {
            throw new Exception('range ' . $range . ' bigger than array' . print_r($array, 1));
        }
        $array = array_slice($array, 0, $range, true);
        return array_sum($array) / $range;
    }

    /**
     * Return the liniar transformation of an array
     * (assuming values are sequential)
     *
     * @param $array
     * @return mixed
     */
    public static function liniarDelta(Array $array) {
        return current($array) - end($array) / count($array);
    }

    /**
     * Return of an array de interval value of each interval
     *
     * @param array $array intervals
     * @return
     */
    public static function interval(Array $array) {
        foreach($array as $key => $value) {
            if(isset($lastkey)) {
                $intervals[$lastkey] = $value - $array[$lastkey];
            }
            $lastkey = $key;
        }
        return $intervals;
    }


    public static function rateOfReturn($vInitial, $vFinal)
    {
        return ($vFinal - $vInitial) / $vInitial;
    }

    /**
     * Return of an array the rate of return for each value
     * @param array $array
     * @return mixed
     */
    public static function rateOfReturns(Array $array) {
        foreach($array as $key => $value) {
            if(isset($lastkey)) {
                $returns[$lastkey] = ($array[$lastkey] - $value) / $value ;
            }
            $lastkey = $key;
        }
        return $returns;
    }

    /**
     * Find the linear difference in an array
     *
     * @param $array
     * @return mixed
     */
    public static function linearDifference($array) {

        $difference =  current($array) - end($array);
        return $difference;
    }

    /**
     * Volume Weighted Average Price
     *
     * @param array $rates
     * @param array $volumes
     * @throws Exception
     * @return float
     */
    public static function vwap(Array $rates, Array $volumes)
    {
        $countRates = count($rates);
        $countVolumes = count($volumes);
        if($countRates != $countVolumes) {
            throw new Exception("Rates lenght $countRates is not lenght volume $countVolumes");
        }
        $sumA = 0;
        $sumB = 0;
        foreach($rates as $i => &$rate) {
                $sumA += $rates[$i] * $volumes[$i];
                $sumB += $volumes[$i];
        }
        return $sumA / $sumB;
    }
}