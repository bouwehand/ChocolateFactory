<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 9/4/15
 * Time: 2:27 PM
 */
class Test_Statistical{

    /**
     *
     */
    public function normality($mean, $sd, $verbose = true)
    {
        $data = array();

        $sumP = 0;
        $i = 0;
        for($z = -6; $z < 6; $z += 0.1) {
            $i++;
            $x = Tool_Statistic::transformZScoreToX($z, $mean, $sd);
            $p = Tool_Statistic::normalDistribution($x, $mean, $sd);

            /** cumulative P for reading */
            $sumP += $p;

            /** calculate main area */
            if ($i == 51) $sigmaMin1 = $sumP;
            if ($i == 71) $sigma1 = $sumP;

            if($verbose) echo "z: " . number_format($z, 1) . "\t\t x: " . number_format($x, 9) . " \t\t P: ". number_format($sumP, 9). PHP_EOL ;
        }

        if($verbose) {
            $sigma1Area =  $sigma1 - $sigmaMin1;
            echo PHP_EOL;
            echo " total density $sumP : 1 , sigma $sigma1Area : 0.682 ". PHP_EOL;
            echo PHP_EOL;
        }
        return $data;
    }
}