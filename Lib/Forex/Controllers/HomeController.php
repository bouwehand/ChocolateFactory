<?php
/**
 *
 */
class HomeController extends ChocolateFactory_MVC_Controller {


    public function home() {
        $eurgbp = ChocolateFactory_Core_Csv::init('/home/bas/vhosts/darwin/doc/EURGBP.csv');
        $eurgbp->dropColumn('TICKER');
        $eurgbp->dropColumn('TIME');

        $array = array(
            4,5,7,8,4,3,2,1,1,5,7
        );
        $graph = new Graph();

        $lines['name'] = 'rate';
        $lines['color'] = '000000';
        foreach($array as $i => $high) {
            $line = new stdClass();
            $line->step = $i;
            $line->rate = $high;
            $lines['name'] = 'rate';
            $lines['color'] = '00CED1';
            $lines['data'][] = $line;
        }
        $first = reset($array);
        $graph->setXaxis(0, 10, 1);
        $graph->setYaxis(0, 10, 1);
        $graph->addLine($lines);
        $graph->render();
        die();
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