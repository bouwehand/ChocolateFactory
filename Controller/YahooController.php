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