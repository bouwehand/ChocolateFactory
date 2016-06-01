<?php

/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/16/14
 * Time: 5:27 PM
 */
class Market
{

    /**
     * @var $_closeValue Final value of the brokers account on market close
     */
    protected $_closeValue;

    /**
     * @var array of market data
     */
    protected $_marketData = array();

    protected $_clarc;

    /**
     * @param mixed $clarc
     */
    public function setClarc($clarc)
    {
        $this->_clarc = $clarc;
    }

    /**
     * @return mixed
     */
    public function getClarc()
    {
        return $this->_clarc;
    }

    /**
     * @param array $marketData
     */
    public function setMarketData($marketData)
    {
        $this->_marketData = $marketData;
    }

    /**
     * @return array
     */
    public function getMarketData()
    {
        return $this->_marketData;
    }

    /**
     * @param mixed $closeValue
     */
    public function setCloseValue($closeValue)
    {
        $this->_closeValue = $closeValue;
    }

    /**
     * @return mixed
     */
    public function getCloseValue()
    {
        return $this->_closeValue;
    }

    /**
     * @param MarketData $marketData
     * @internal param \Add $data a data object to the data array
     */
    public function addMarketData(MarketData $marketData)
    {

        $this->_marketData[] = $marketData;
    }

    /**
     * Mule function that runs the market
     */
    public function run()
    {
        $dataHandler    = new DataHandler();
        $clarc          = $this->getClarc();
        $account        = $clarc->getAccount();

        $step       = $dataHandler::FIRST_STEP_NUM;
        $max        = $dataHandler::MAX_STEP_NUM;

        while ($step <= $max) {

            // set the step
            $marketData = new MarketData();
            $marketData->step = $step;

            // set the currency rate
            $currency         = $dataHandler->getStepForCurrency($clarc::TRADING_CURRENCY_CODE, $step);
            $marketData->rate = number_format($currency, 8);
            $marketData->lip  = $currency;
            $marketData->beak = $currency;

            // add instrument data
            $clarc->loadInstruments($step);
            $marketData = $this->addInstrumentData($clarc, $marketData);

            // add trade data
            if ($clarc->identifyBuy()) {
                $clarc->buy();
                $marketData->buy = 1;
            } elseif ($clarc->indentifySell()) {
                $clarc->sell();
                $marketData->sell = 1;
            }

            $marketData->currency   = $clarc->getCurrencyCode();
            $marketData->in         = $clarc->in;
            $marketData->account    = $clarc->getAccount();
            $this->addMarketData($marketData);
            $step = $step + 1;
        }
        $this->setCloseValue($account);
    }

    /**
     * Adds the instrument data to the market data
     *
     * @param Clarc $clarc
     * @param MarketData $marketData
     * @return MarketData
     */
    public function addInstrumentData (Clarc $clarc, MarketData $marketData) {
        if( $clarc->getInstruments() ) {
            foreach($clarc->getInstruments() as $instrument) {
                $name = $instrument->getName();
                $instrumentNames[] = $name;
                $marketData->$name = $instrument->getRate();

                // add the trend flags
                $highlow = ClarcInstrument::getHighLowByName($name);
                $marketData->{$name . 'Trend'} = 0;
                $marketData->{$name .'TAnn'} = 'null';
                if(!empty($highlow)) {
                    $marketData->{$name .'tAnn'} = $instrument->getName() . ' ' . $instrument->getStep() . ' ' . $highlow->direction;
                    $marketData->{$name .'Trend'} = $highlow->newTrend;
                }

            }
            $marketData->instrumentNames = implode(';', $instrumentNames);
        }
        return $marketData;
    }

}