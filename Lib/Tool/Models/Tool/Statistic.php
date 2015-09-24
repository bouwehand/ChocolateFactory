<?php

/**
 * Class Tool_Statistic
 *
 * Statistic methods
 * @todo add regression analysis
 *       - is there a polominal aproximisation of stock chance possible?
 *       https://en.wikipedia.org/wiki/Polynomial_regression
 */
class Tool_Statistic
{
    /**
     * @param array $x
     * @return float
     */
    static function mean(Array $x) {
        return array_sum($x) / count($x);
    }

    /**
     * @param       $x
     * @param       $m
     * @param       $sigma
     * @return float
     */
    static function zScore($x, $m, $sigma) {

        return ($x - $m) / $sigma;
    }

    static function transformZScoreToX($z, $m , $sigma)
    {
        return $sigma * $z + $m;
    }

    /**
     * @param array $x
     * @return float
     */
    static function sd(Array $x) {
        $m = self::mean($x);
        $var = 0;
        $p = count($x);
        foreach($x as $i => &$value) {
            $var += pow(($x[$i]- $m), 2);
        }
        return sqrt($var / $p);
    }

    /**
     * @param array $x
     * @return mixed
     */
    static function spread(Array $x) {
        return max($x) - min($x);
    }

    /**
     * @param array $x
     * @param array $y
     * @return float $r
     * @throws Exception
     */
    static function correlation(Array $x, Array $y)
    {
        // Number of values or elements
        $n = count($x);
        $nY = count($y);
        if ($n != $nY) {
            throw new Exception("series must have same size " . $n . " : " . $nY);
        }
        //Sum of the product of first and Second Scores
        $sumPxy = 0;
        //Sum of First Scores
        $sumX = 0;
        //Sum of square First Scores
        $sumXs = 0;
        //Sum of Second Scores
        $sumY = 0;
        //Sum of square Second Scores
        $sumYs = 0;
        foreach($x as $i => &$value) {
            $sumPxy += $value * $y[$i];
            $sumX += $value;
            $sumXs += $value * $value;
            $sumY += $y[$i];
            $sumYs += $y[$i] * $y[$i];
        }
        $correlation = ((($n * $sumPxy) - ($sumX * $sumY)) / sqrt(
                ($n * $sumXs - ($sumX * $sumX)) * ($n * $sumYs - ($sumY * $sumY))
        ));
        return $correlation;
    }

    /**
     * @param $x
     * @param $mu
     * @param $sigma
     * @return float
     */
    static function normalDistribution($x, $mu, $sigma) {
        return exp(-0.5 * ($x - $mu) * ($x - $mu) / ($sigma*$sigma))
        / ($sigma * sqrt(2.0 * M_PI));
    }
}