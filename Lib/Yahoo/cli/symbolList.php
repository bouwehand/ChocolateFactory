<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/7/14
 * Time: 4:51 PM
 */ 

$filepath = APP_LIB . '/Yahoo/doc/YahooTickerSymbols.csv';

$data = array_map('str_getcsv', file($filepath));

foreach($data as $k => $row) {
    if($k == 0) {
        $keys = $row;
    }else {
        $mappedRow = array_combine($keys, $row);
        
        $symbolList = new Yahoo_Model_SymbolList();
        $symbolList->setData($mappedRow);
        die(var_dump($symbolList));
        //$symbolList->save();    
    }
}
