<?php
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

    public function tool()
    {
        $csvFilePath = CHOCOLATE_FACTORY_DOC . '/AAPL.csv';
        $csv = ChocolateFactory_Core_Csv::init($csvFilePath);

        $rates = $csv->getColumn('Adj Close');
        $dates = $csv->getColumn('Date');
        /*
                // calculate the rate of for each bar
                $returns = array();

                $final = 0;
                foreach ($rates as $i => $value) {
                    // values should be positive, so they will be treated as difference of 0


                     using the rates of return which are normaly distributed
                    if($final) $returns[] = abs(Tool_Financial::rateOfReturn($value, $final));
                    $final = $value;
                }
        */
        rsort($rates);

        // calculate the mean and the sd
        $mean = Tool_Statistic::mean($rates);
        //die(var_dump($mean));
        $sd = Tool_Statistic::sd($rates);

        $lava = new Khill\Lavacharts\Lavacharts;
        $stocksTable = $lava->DataTable();  // Lava::DataTable() if using Laravel

        $stocksTable
            ->addDateColumn('Day')
            ->addNumberColumn('Rate of return');

        // Random Data For Example
        foreach ($rates as $i => $rate)
        {
            $rowData = array(
                "$dates[$i]", $rate
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
}