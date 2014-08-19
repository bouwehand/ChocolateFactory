<?php

class Clarc
{

    /**
     * The currency codes
     *
     * @var string
     */
    protected $currencyCode;
    const DEFAULT_CURRENCY_CODE = 'EUR';
    const TRADING_CURRENCY_CODE = 'USD';
    protected $_account = 100;


    protected $_data = array();

    /**
     * @var bool if we're in the buy
     */
    public $in = 0;

    /**
     * @var step number
     */
    protected $step;

    /**
     * @var the rate of the currency this step
     */
    public $currency;


    protected $_gen;

    /**
     * @param mixed $gen
     */
    public function setGen($gen)
    {
        $this->_gen = $gen;
    }

    /**
     * @return mixed
     */
    public function getGen()
    {
        return $this->_gen;
    }



    function __construct()
    {
        $this->setCurrencyCode($this::DEFAULT_CURRENCY_CODE);
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->_data;
    }

    public function addData($data)
    {
        $this->_data[] = $data;
    }

    /**
     * @param int $account
     */
    public function setAccount($account)
    {
        $this->_account = $account;
    }

    /**
     * @return int
     */
    public function getAccount()
    {
        return $this->_account;
    }

    /**
     * @return highest trend value
     */
    public function getLongestTrend()
    {
        return max($this->trendlengths);
    }

    /**
     * @return \step
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @return string EU, US ex.
     */
    public function getCurrencyCode()
    {
        if (!$this->currencyCode) return $this::DEFAULT_CURRENCY_CODE;
        return $this->currencyCode;
    }

    /**
     * @param $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @param $step
     * @return $this
     */
    public function setStep($step)
    {
        $this->step = $step;
        return $this;
    }

    /**
     * Return the default currency code of the account
     *
     * @return string
     */
    public function getDefaultCurrencyCode()
    {
        return $this::DEFAULT_CURRENCY_CODE;
    }

    /**
     * Mule function for loading the instuments and clarc data
     *
     * @param $step
     */
    public function loadInstruments($step)
    {

        $this->setStep($step);
        $this->loadCurrency($this::TRADING_CURRENCY_CODE, $this->getStep());

        // reset the instruments
        ClarcInstrument::reset();

        // create the currency
        $dataHandler = new DataHandler();
        $lastCurrency = $dataHandler->getStepForCurrency($this::TRADING_CURRENCY_CODE, $step -1);
        $instrument = ClarcInstrument::create('currency');
        $instrument->setStep($step);
        $instrument->setRate($this->currency);
        $instrument->setDelta($this->currency, $lastCurrency);
        ClarcInstrument::createRelations($instrument);

        // create the lip
        $instrument = ClarcInstrument::create('lip');
        $instrument->setStep($step);
        $instrument->setTrendLength(5);
        $instrument->setOffset(5);
        $trend     = $instrument->loadData($step);
        $lastTrend = $instrument->loadData($step -1);
        $instrument->setDelta($trend, $lastTrend);
        ClarcInstrument::createRelations($instrument);

        // create the Teeth
        $instrument = ClarcInstrument::create('teeth');
        $instrument->setStep($step);
        $instrument->setTrendLength(8);
        $instrument->setOffset(8);
        $trend     = $instrument->loadData($step);
        $lastTrend = $instrument->loadData($step -1);
        $instrument->setDelta($trend, $lastTrend);
        ClarcInstrument::createRelations($instrument);

        // create the jaw
        $instrument = ClarcInstrument::create('jaw');
        $instrument->setStep($step);
        $instrument->setTrendLength(13);
        $instrument->setOffset(13);
        $trend     = $instrument->loadData($step);
        $lastTrend = $instrument->loadData($step -1);
        $instrument->setDelta($trend, $lastTrend);
        ClarcInstrument::createRelations($instrument);
    }

    /**
     * Calculate the rates for the data
     *
     * @param $current
     * @param $last
     * @return float
     */
    public function calculateRate($current, $last)
    {
        return ($current - $last) / $current;
    }

    public function infuse($gen) {
        $this->_gen = $gen;
    }

    /**
     * @param mixed $instruments
     */
    public function setInstruments(ClarcInstrument $instruments = null)
    {
        $this->_instruments = $instruments;
    }

    /**
     * @return mixed
     */
    public function getInstruments()
    {
        return ClarcInstrument::getInstruments();
    }

    /**
     * The logic for the clarc to identify a buy
     *
     * @return bool
     */
    public function identifyBuy()
    {
        // lock if we are in a buy
        if ($this->in) {
            return 0;
        }

        $relation = ClarcInstrument::getInstrumentByName('lip');
        if($relation->getStep() == 341) {
            //die(var_dump($relation));
        }
        if(! ($relation->getDelta() > 0 )) {
            return 0;
        }

        $relation = ClarcInstrument::getRelationByName('lipCurrency');
        if(! ($relation->getDelta() > 0 )) {
            return 0;
        }

        $relation = ClarcInstrument::getRelationByName('teethLip');
        if(! ($relation->getDelta() > 0 )) {
            return 0;
        }

        $relation = ClarcInstrument::getRelationByName('jawTeeth');
        if(! ($relation->getDelta() > 0 )) {
            return 0;
        }

        $this->in = 1;
        return 1;

    }

    /**
     * The logic for the Clarc to identify a sell
     * @return bool
     */
    public function indentifySell()
    {

        // cant sell if we're not i a buy
        if (!$this->in) {
            return 0;
        }

        $relation = ClarcInstrument::getRelationByName('lipCurrency');
        if(! ($relation->getDelta() < 0 )) {
            return 0;
        }

        $relation = ClarcInstrument::getRelationByName('teethCurrency');
        if(! ($relation->getDelta() < 0 )) {
            return 0;
        }

        $this->in = 0;
        return 1;
    }

    /**
     * Buy currency
     */
    public function buy()
    {

        $account = $this->getAccount();

        // pay fee
        $this->payFee();
        $account = $account / $this->currency;
        $this->setAccount($account);
        $this->setCurrencyCode($this::TRADING_CURRENCY_CODE);
    }

    /**
     * Pay the trade fee
     */
    public function payFee()
    {
        $account = $this->getAccount();
        $account -= ($account * 0.0140);
        $this->setAccount($account);
    }

    /**
     * Sell currency
     */
    public function sell()
    {
        $account = $this->getAccount();
        $account = $account * $this->currency;
        $this->setAccount($account);
        $this->setCurrencyCode($this::DEFAULT_CURRENCY_CODE);
    }

    /**
     * @param $currencyCode 'USD' ex.
     * @param $step
     */
    public function loadCurrency($currencyCode, $step)
    {

        $data = new DataHandler();
        $this->currency = $data->getStepForCurrency($currencyCode, $step);
    }

    public function setTrendlength($trendlength)
    {
        $this->trendlength = $trendlength;
    }
}