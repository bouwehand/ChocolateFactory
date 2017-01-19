<?php
/**
 * Cli script to fil the European bank db horizontal
 */

$i = 1;
$XML=simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml");
Query::getInstance()->truncate('horizontal');
foreach($XML->Cube->Cube as $rowRates ) {

    if(!$rowRates instanceof SimpleXMLElement ) {
        die(var_dump($rowRates));
    }
    $outputDatetime = (string) $rowRates->attributes()['time'];
    $outputDatetime = strtotime($outputDatetime);
    $outputCurrency = array(
    'id' => $i,
    'datetime' => $outputDatetime
    );
    foreach($rowRates->Cube as $rate){
        // they changed the feed so this is my ugly hack
        $rate = current((array)$rate);
        $outputCurrency[$rate["currency"]]= $rate["rate"];
    }

    $result = Query::getInstance()
        ->setTable('horizontal')
        ->insert($outputCurrency, false);
    $i++;
    echo "\n finished line $i ";
}
die();