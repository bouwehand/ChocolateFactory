<?php


use Khill\Lavacharts\Lavacharts;

/**
 *
 */
class HomeController extends ChocolateFactory_MVC_Controller {


    public function home() {
        $lava = new Khill\Lavacharts\Lavacharts;
        $stocksTable = $lava->DataTable();  // Lava::DataTable() if using Laravel

        $stocksTable
            ->addDateColumn('Day of Month')
            ->addNumberColumn('Projected')
            ->addNumberColumn('Official');

        // Random Data For Example
        for ($a = 1; $a < 30; $a++)
        {
            $rowData = array(
                "2014-8-$a", rand(800,1000), rand(800,1000)
            );

            $stocksTable->addRow($rowData);
        }

        $lava->LineChart('Stocks')
            ->setOptions(array(
                    'datatable' => $stocksTable,
                    'title' => 'Stock Market Trends'
                ));

        echo $lava->render('LineChart', 'Stocks', 'stocks-div', array('width'=>1024, 'height'=>768));
    }

    public function darwin() {

    }

    /**
     * my hill climber idea proof of concept
     * 1. check if a forex has rissen above the bank cost
     * 2. change to forex
     * 3. repeat
     */
    public function forex()
    {
        $eurgbp = ChocolateFactory_Core_Csv::init('/home/bas/vhosts/darwin/doc/EURGBP.csv');
        $eurusd = ChocolateFactory_Core_Csv::init('/home/bas/vhosts/darwin/doc/EURUSD.csv');
        $gbpusd = ChocolateFactory_Core_Csv::init('/home/bas/vhosts/darwin/doc/GBPUSD.csv');

        $graph = new Graph();
        $lines['name'] = 'rate';
        $lines['color'] = '000000';
        foreach($eurgbp->getColumn('HIGH') as $i => $high) {
            $line = new stdClass();
            $line->step = $i;
            $line->rate = $high;
            $lines['name'] = 'rate';
            $lines['color'] = '00CED1';
            $lines['data'][] = $line;
        }
        $graph->setXaxis(0, $i, 1);
        $graph->setYaxis(5000, 8000, 500);
        $graph->addLine($lines);
        $graph->render();
    }

    /**
     * First setup for the dev of the normality tool
     *
     * @throws Exception
     */
    public function tool()
    {
        $csvFilePath = CHOCOLATE_FACTORY_DOC . '/AAPL.csv';
        $csv = ChocolateFactory_Core_Csv::init($csvFilePath);

        $rates = $csv->getColumn('AdjClose');
        $dates = $csv->getColumn('Date');

                // calculate the rate of for each bar
                $returns = array();

                $final = 0;
                foreach ($rates as $i => $value) {
                    // values should be positive, so they will be treated as difference of 0

                    // using the rates of return which are normally distributed
                    if($final) $returns[] = abs(Tool_Financial::rateOfReturn($value, $final));
                    $final = $value;
                }
        //$rates = $returns;
        rsort($rates);

        // calculate the mean and the sd
        $mean = Tool_Statistic::mean($rates);
        //die(var_dump($mean));
        $sd = Tool_Statistic::sd($rates);

        $test = new Test_Statistical();
        $normal = $test->normality($mean, $sd, false);
        $normal2 = $test->normality(183, 10, false);

        $lava = new Khill\Lavacharts\Lavacharts;
        $stocksTable = $lava->DataTable();  // Lava::DataTable() if using Laravel

        $stocksTable
            ->addNumberColumn('z')
            ->addNumberColumn('p1 : distribution rates AAPL')
            ->addNumberColumn('p2 : distribution male length');

        // Random Data For Example
        foreach ($normal as $i => $value)
        {
            $rowData = array(
                $value['z'],  $value['P'], $normal2[$i]['P']
            );
            $stocksTable->addRow($rowData);
        }

        $lava->LineChart('Stocks')
            ->setOptions(array(
                    'datatable' => $stocksTable,
                    'title' => 'Stock Market Trends'
                ));
        $this->lava = $lava;
    }

    /**
     * fire up the mutherfucker
     */
    public function normality()
    {
        $csvFilePath = CHOCOLATE_FACTORY_DOC . '/AAPL.csv';
        $csv = ChocolateFactory_Core_Csv::init($csvFilePath);

        $rates = $csv->getColumn('AdjClose');

        // calculate the rate of for each bar
        $returns = array();

        $final = 0;
        foreach ($rates as $i => $value) {
            // values should be positive, so they will be treated as difference of 0

            // using the rates of return which are normally distributed
            if($final) $returns[] = abs(Tool_Financial::rateOfReturn($value, $final));
            $final = $value;
        }
        //$rates = $returns;
        rsort($rates);

        $lava = new Lavacharts; // See note below for Laravel
        $votes  = $lava->DataTable();
        $votes->addStringColumn('Food Poll')
            ->addNumberColumn('Votes');

        // Random Data For Example
        foreach ($rates as $i => $value)
        {
            $votes->addRow(array($i, $value));
        }

        $lava->BarChart('Votes')
        ->setOPtions(array(
            'datatable' => $votes,
            'title' => 'Stock Market Trends'
        ));
        $this->lava = $lava;
    }
}