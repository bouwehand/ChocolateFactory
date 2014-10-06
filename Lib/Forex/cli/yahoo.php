<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/22/14
 * Time: 9:48 PM
 */
$url = 'http://query.yahooapis.com/v1/public/yql?q=select * from yahoo.finance.xchange where pair in ("EURUSD", "EURJPY", "EURUSD", "EURCZK", "EURDKK", "EURGBP", "EURHUF", "EURLTL", "EURLVL", "EURPLN", "EURRON", "EURSEK", "EURCHF", "EURNOK", "EURHRK", "EURRUB", "EURTRY", "EURAUD", "EURBRL", "EURCAD", "EURCNY", "EURHKD", "EURIDR", "EURILS", "EURINR", "EURKRW", "EURMXN", "EURMYR", "EURNZD", "EURPHP", "EURSGD", "EURTHB", "EURZAR", "EURISK")&env=store://datatables.org/alltableswithkeys';
$xml = simplexml_load_file($url);
$outputCurrency = array();
foreach($xml->results->rate as $i=>$node ) {
    if($i == 0) {
        $outputDatetime = (string) $node['Date'] . ' ' . $node['Time'];
        $outputDatetime = strtotime($outputDatetime);
        $outputCurrency['datetime'] = $outputDatetime;
    }
    $name = substr($node['id'], 3,6);
    $outputCurrency[$name] = $node->Rate;
}

$result = Query::getInstance()
    ->setTable('yahoo')
    ->insert($outputCurrency, true);