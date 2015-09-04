<?php
/**
 *
 */
class HomeController extends ChocolateFactory_MVC_Controller {


    public function home() {

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



        $eurgbp = CSV::load('EURGBP');
        $eurusd = CSV::load('EURUSD');
        $gbpusd = csv::load('GPBUSD');

        // walk through time
        for($i =0 ; $i < $eurgbp->getLength(); $i++) {


            // check if a forex has rissen above the bank cost
        }
    }
}