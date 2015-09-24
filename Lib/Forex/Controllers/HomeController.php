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
}