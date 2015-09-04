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


$high = $csv->getColumn('high');
$low = $csv->getColumn('low');
$volumes = $csv->getColumn('volume');

// calculate the vwap for each bar
foreach ($csv->getData() as $i => $row ) {

    $vwaps[] = Tool_Financial::vwap(
        array($row['high'], $row['low']),
        array($volumes[$i], $volumes[$i]),
        $volumes[$i]);
}

die(var_dump($vwaps));

// first calculate the rates of return, they will be normally distributed
$returns = Tool_Financial::rateOfReturns($high);

// calculate the mean and the sd
$mean = Tool_Statistic::mean($returns);
$sd = Tool_Statistic::sd($returns);
//$targetRate = Tool_Financial::rateOfReturn(55,56);
$targetRate = 0.245;
$zScore = Tool_Statistic::zScore($targetRate, $returns);

echo "chance of beating the house: " . PHP_EOL;
echo "mean: $mean sd: $sd, targetRate : $targetRate, zScore: $zScore". PHP_EOL;
echo " mean VS vwap: ";
echo Tool_Statistic::mean($high) . " " . Tool_Financial::vwap($high, $volumes) . PHP_EOL;