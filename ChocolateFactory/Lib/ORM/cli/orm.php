<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/7/14
 * Time: 11:50 AM
 */ 
$query = Query::getInstance();

$result = $query
    ->select(array("open", "close", "volume"))
    ->from('yahoo_historical')
    ->where("id = 1")
    ->join('yahoo', "volume = volume")
    ->fetchOne();
var_dump($result);

