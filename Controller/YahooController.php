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
        $yahoo = new YahooFinance();
        $json = $yahoo->getQuotes('EFSI');
        $quotes = json_decode($json);

        die(var_dump($quotes));
    }
}