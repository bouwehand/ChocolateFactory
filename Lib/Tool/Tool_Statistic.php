<?php
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
     * @param array $p
     * @return mixed
     */
    static function zScore($x, Array $p) {
        $m = self::mean($p);
        $sd = self::sd($p);
        return $x - $m / $sd;
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
}
$a = array(
    796.70,
    425.70,
    371.68,
    725.48,
    1116.75,
    1453.04,
    1047.59,
    1147.30,
    2588.53,
    1824.40,
    2141.22,
    1279.14,
    1652.35,
    1657.36,
    2392.31,
    1759.18,
    2193.11,
    1121.48,
    1187.00,
    2337.72,
    2843.54,
    2387.14,
    3115.58,
    3674.36,
    432.19,
    4211.44,
    2665.15,
    432.19,
    1101.49,
    1108.37,
    345.53,
    517.25,
    1298.37,
    848.52,
    273.84,
    808.50,
    1135.06,
    521.70,
    347.80,
    343.43,
    460.73,
    686.87,
    578.14,
    235.12,
    243.44,
    235.12,
    417.27,
    599.99,
    699.99,
    830.31,
    782.89,
    522.68,
    632.55,
    709.32,
    2160.60,
    2065.75,
    1786.44,
    2025.76,
    3190.90,
    2109.16,
    1887.02,
    1652.60,
    3598.93,
    664.99,
    673.63,
    4493.26,
    1422.96,
    1181.20,
    500.44,
    449.46,
    540.98,
    457.587,
    89.02,
    743.12,
    6249.16,
    2103.99,
    578.13,
    1003.62,
    1748.03,
    1512.25,
    1782.85,
    465.75,
    1110.40,
    2658.48,
    987.93,
    1808.22,
    2510.86,
    1561.90,
    2947.39,
    4017.31,
    479.66,
    2386.98,
    1028.28,
    1301.45
); $b = array(
    1428.42,
    3093.44,
    1212.77,
    1308.62,
    1134.24,
    2493.63,
    3045.74,
    2759.02,
    3223.01,
    2571.50,
    2621.11,
    2222.28,
    1337.04,
    1286.16,
    1474.78,
    1145.49,
    2215.41,
    4233.91,
    2714.28,
    2629.93,
    2657.05,
    3474.56,
    749.13,
    3809.06,
    4533.39,
    1309.04,
    4085.50,
    4128.00,
    1437.19,
    4005.25,
    4695.29,
    3190.39,
    847.59,
    2664.70,
    3748.92,
    1839.42,
    739.07,
    1469.14,
    809.29,
    1026.29,
    835.76,
    822.52,
    392.89,
    476.22,
    452.81,
    675.79,
    1205.43,
    912.29,
    968.86,
    729.22,
    589.34,
    976.47,
    989.93,
    1010.01,
    976.47,
    1864.97,
    815.64,
    879.64,
    2957.40,
    2464.01,
    3107.68,
    2311.18,
    6746.77,
    3179.73,
    4074.20,
    3439.41,
    411.20,
    1753.65,
    1041.48,
    1000.40,
    602.91,
    753.56,
    698.63,
    2630.29,
    3082.19,
    8243.15,
    5828.21,
    3309.90,
    9521.83,
    3685.61,
    1846.11,
    3221.18,
    5082.69,
    3232.88,
    1851.27,
    671.83,
    3647.81,
    698.63,
    3836.90,
    1342.54,
    1520.81,
    1222.14,
    2726.06,
    1205.56
);

$mA = mean($a);
$sA = sd($a);
$mB = mean($b);
$spreadA = spread($a);
$r = pearsonCorrelation($a, $b);

echo " mA : $mA , sA : $sA , spread = $spreadA ";
