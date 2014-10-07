<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/7/14
 * Time: 11:50 AM
 */ 
$query = Query::getInstance();

$query
    ->select(array("open", "close", "volume"))
    ->from('yahoo_historical')
    ->where("`volume` > 100")
    ->join('yahoo', "volume = volume");
echo $query->buildSql();