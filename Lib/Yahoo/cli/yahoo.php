<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/6/14
 * Time: 3:55 PM
 */
// init snp500
$csv = ChocolateFactory_Core_Csv::init(CHOCOLATE_FACTORY_DOC .'/snp500.csv');
$columns = array (
    array(
        'name' => 'symbol',
        'type' => 'string',
    ),
    array(
        'name' => 'security',
        'type'  => 'string',
    ),
    array(
        'name' => 'gicssector',
        'type'  => 'string',
    ),
    array(
        'name' => 'gicsindustry',
        'type'  => 'string',
    ),
    array(
        'name' => 'headquarters',
        'type' => 'string'
    ),
    array(
        'name' => 'added',
        'type'  => 'timestamp',
    ),
    array(
        'name' => 'cik',
        'type' => 'int'
    )
);
$table = ChocolateFactory_Mysql_Table::createTable("snp500", $columns, $csv->getData());

$yahoo = new YahooFinance();
$json = $yahoo->getQuotes('EFSI');
$quotes = json_decode($json);

die(var_dump($quotes->query->results->quote));