<?php
/**
 * dogeQuestion.php
 *
 * Given the financial market of the dogeCoin on 2 sept. What is the change of making a profit?
 *
 * @category Wolf
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 9/2/15
 *
 *
 */




$csvFilePath = '/home/bas/vhosts/darwin/Lib/Tool/docs/dogeSmilloions.csv';
$csv = ChocolateFactory_Core_Csv::init($csvFilePath);

// first calculate the rates of return, they will be normally distributed
$rates = $csv->getColumn('rate');
$returns = Tool_Financial::rateOfReturns($rates);

// calculate the mean and the sd
$mean = Tool_Statistic::mean($returns);
$sd = Tool_Statistic::sd($returns);
//$targetRate = Tool_Financial::rateOfReturn(55,56);
$targetRate = 0.245;
$zScore = Tool_Statistic::zScore($targetRate, $returns);
die("mean: $mean sd: $sd, targetRate : $targetRate, zScore: $zScore". PHP_EOL);