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
}