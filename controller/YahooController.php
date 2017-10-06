<?php
/**
 * YahooController.php
 *
 * <description>
 *
 * @category Youwe Development
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 10/7/15
 *
 */
class YahooController extends ChocolateFactory_MVC_Controller {

    public function home()
    {

        $table = ChocolateFactory_Mysql_Table::init('snp500');
        $table->getId(11);
        die(var_dump($table));
    }

    public function example()
    {
        $yahoo = new YahooFinance();
        $json = $yahoo->getQuotes('SPY');
        $quotes = json_decode($json);
        $time = $quotes->query->created;

        die(var_dump($quotes->query->results));
    }

    public function historical()
    {
        $columns = array(

        );

        // walk trough the whole snp500
        $table = ChocolateFactory_Mysql_Table::init('snp500');
        $yahoo = new YahooFinance();
        foreach ($table->getAll() as $row) {

            // get the historical data
            $json = $yahoo->getHistoricalData($row['symbol'], "2015-01-01", "2015-10-07");
            $json = json_decode($json);
            if(!$json) {
                continue;
            }

            ChocolateFactory_Mysql_Table::createTable('Yahoo_historical_' . $row['symbol'], $columns);
            foreach($json->query->results as $quote) {
                die(var_dump($quote));
            }


        };
    }
}