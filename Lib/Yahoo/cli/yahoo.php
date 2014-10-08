<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/6/14
 * Time: 3:55 PM
 */ 
$yahoo = new YahooFinance();
$json = $yahoo->getQuotes('EFSI');
$quotes = json_decode($json);

die(var_dump($quotes->query->results->quote));