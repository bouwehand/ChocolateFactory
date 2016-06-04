<?php
/**
 * dogeQuestion.php
 *
 * Given the financial market of the dogeCoin on 2 sept. What is the change of making a profit?
 *
 * To do: implement H0 to H1 structure
 *
 * assume that h0 is true and there is no distirbution
 *
 * try to model a densitiy function and give a P that this is true under an given ALPHA
 *
 * See if alpha is met and decide if HO is rejected or not
 *
 * @category Wolf
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 9/2/15
 */
$csvFilePath = CHOCOLATE_FACTORY_DOC . '/AAPL.csv';
$csv = ChocolateFactory_Core_Csv::init($csvFilePath);

$rates = $csv->getColumn('AdjClose');


// calculate the rate of for each bar
$returns = array();
$final = 0;
foreach ($rates as $i => $value) {
    if($final) $returns[] = Tool_Financial::rateOfReturn($value, $final);
    $final = $value;
}
asort($returns);

// calculate the mean and the sd
$mean = Tool_Statistic::mean($returns);
//die(var_dump($mean));
$sd = Tool_Statistic::sd($returns);
//$targetRate = Tool_Financial::rateOfReturn(55,56);
$targetRate = 0.0013;
$zScore = Tool_Statistic::zScore($targetRate, $mean, $sd);

//echo "chance of beating the house: " . PHP_EOL;
// echo "mean: $mean sd: $sd, targetRate : $targetRate, zScore: $zScore". PHP_EOL;

$test = new Test_Statistical();
$test->normality(183, 10);
