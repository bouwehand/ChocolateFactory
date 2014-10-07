<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/7/14
 * Time: 3:58 PM
 */ 
class Yahoo_Model_ImportHistorical {
    
    public function run() {
        $yahoo = new YahooFinance();
        $startDate = new DateTime("2010-01-01");
        $endDate = new DateTime("2010-12-30");
        $data = $yahoo->getHistoricalData('GOOGL', $startDate, $endDate);
        $quotes = json_decode($data);
        die(var_dump($quotes->query->results));
    }
    
}