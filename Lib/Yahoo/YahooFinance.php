<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/6/14
 * Time: 3:54 PM
 *
 * @see https://github.com/aygee/yahoo-finance-api/blob/master/lib/YahooFinance/YahooFinance.php
 */
class YahooFinance {

    /**
     * @var string
     */
    private $yqlUrl = "http://query.yahooapis.com/v1/public/yql";

    /**
     * @var array
     */
    private $options = array(
        "env" => "http://datatables.org/alltables.env", // need this env to query yahoo finance
    );

    /**
     * @var
     */
    private $format;

    /**
     * @param string $format
     */
    public function __construct($format='json') {
        if (isset($format)) {
            switch ($format) {
                case 'json':
                    $this->options['format'] = 'json';
                    break;
            }
        }
    }

    /**
     *  See https://developer.yahoo.com/yql/guide/select.html for documentation
     *
     * @param   $symbol string, valid ticker symbol
     * @param   $startDate string
     * @param   $endDate  string
     * @return  string json
     */
    public function getHistoricalData($symbol, $startDate, $endDate) {

        $startDate = new dateTime($startDate);
        $endDate = new dateTime($endDate);
        $startDate = $this->dateToDBString($startDate);
        $endDate = $this->dateToDBString($endDate);
        $options = $this->options;
        $options['q'] = "select * from yahoo.finance.historicaldata where startDate='{$startDate}' and endDate='{$endDate}' and symbol='{$symbol}' | sort(field='Date',descending='false')";
        return $this->execQuery($options);
    }

    /**
     * @param   $symbols
     * @return  mixed
     */
    public function getQuotes($symbols) {
        if (is_string($symbols)) {
            $symbols = array($symbols);
        }
        $options = $this->options;
        $options['q'] = "select * from yahoo.finance.quotes where symbol in ('" . implode("','", $symbols) . "')";
        return $this->execQuery($options);
    }

    /**
     * @param   $symbols
     * @return  mixed
     */
    public function getQuotesList($symbols) {
        if (is_string($symbols)) {
            $symbols = array($symbols);
        }
        $options = $this->options;
        $options['q'] = "select * from yahoo.finance.quoteslist where symbol in ('" . implode("','", $symbols) . "')";
        return $this->execQuery($options);
    }

    /**
     * @param   $options
     * @return  mixed
     */
    private function execQuery($options) {
        $yql_query_url = $this->getUrl($options);
        $session = curl_init($yql_query_url);
        curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
        return curl_exec($session);
    }

    /**
     * @param $options
     * @return string
     */
    private function getUrl($options) {
        $url = $this->yqlUrl;
        $i=0;
        foreach ($options as $k => $qstring) {
            if ($i==0) {
                $url .= '?';
            } else {
                $url .= '&';
            }
            $url .= "$k=" . urlencode($qstring);
            $i++;
        }
        return $url;
    }

    /**
     * @param $date
     * @return mixed
     */
    private function dateToDBString($date) {
        assert('is_object($date) && get_class($date) == "DateTime"');

        return $date->format('Y-m-d');
    }
}