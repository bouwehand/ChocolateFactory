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


    protected $_instruments;

    /**
     * The length of the different trends used by the clarc
     * @var logest trendlength
     */
    protected $trendlengths = array(8, 13);

    /**
     * @var array of trend
     */
    protected $trend = array();

    /**
     * @var array of moving avarages
     */
    public $movingAvarages = array();

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
        $this->setInstruments();

        // create the jaw
        $jaw = new ClarcInstrument();
        $jaw->setName('jaw');
        $jaw->setStep($step);
        $jaw->setTrendLength(13);
        $jaw->setOffset(13);
        $jaw->loadData();
        $this->addInstrument($jaw);

        // create the Teeth
        $teeth = new ClarcInstrument();
        $teeth->setName('teeth');
        $teeth->setStep($step);
        $teeth->setTrendLength(8);
        $teeth->setOffset(8);
        $teeth->loadData();
        $this->addInstrument($teeth);

        // create the lip
        $lip = new ClarcInstrument();
        $lip->setName('lip');
        $lip->setStep($step);
        $lip->setTrendLength(5);
        $lip->setOffset(5);
        $lip->loadData();
        $this->addInstrument($lip);

        // set the clarc data
        $clarcData = new ClarcData();
        $dataHandler = new DataHandler();

        $clarcData->step = $step;

        $lastCurrency = $dataHandler->getStepForCurrency($this::TRADING_CURRENCY_CODE, $step -1);
        $clarcData->lastCurrencyRate = ($this->currency - $lastCurrency) / $this->currency;

        $clarcData->currencyLipRate = ($this->currency - $lip->getRate()) / $this->currency;

        // create the lastlip
        $lastStep = $this->getStep() - 1;
        $lastLip = new ClarcInstrument();
        $lastLip->setStep($lastStep);
        $lastLip->setTrendLength($lip->getTrendLength());
        $lastLip->setOffset($lip->getOffset());
        $lastLip->loadData();

        // check if the lip stands up
        $clarcData->lastLipRate = ($lip->getRate() - $lastLip->getRate()) / $lip->getRate();

        $clarcData->lipJawRate = ($lip->getRate() - $jaw->getRate()) / $lip->getRate();

        // check if lip is above teeth
        $clarcData->lipTeethRate = ($lip->getRate() - $teeth->getRate()) / $lip->getRate();

        // create last teeth
        $lastTeeth = new ClarcInstrument();
        $lastTeeth->setStep($lastStep);
        $lastTeeth->setTrendLength($teeth->getTrendLength());
        $lastTeeth->setOffset($teeth->getOffset());
        $lastLip->loadData();

        // check if the teeth stand up
        $clarcData->lastTeethRate = ($teeth->getRate() - $lastTeeth->getRate()) / $teeth->getRate();

        // start checking the jaw
        $jaw = $this->getInstrumentByName('jaw');

        // check if the teeth are above the jaw
        $clarcData->teethJawRate = ($teeth->getRate() - $jaw->getRate()) / $teeth->getRate();


        // create last jaw
        $lastJaw = new ClarcInstrument();
        $lastJaw->setStep($lastStep);
        $lastJaw->setTrendLength($jaw->getTrendLength());
        $lastJaw->setOffset($jaw->getOffset());
        $lastJaw->loadData();

        $clarcData->currencyJawRate = ($this->currency - $jaw->getRate()) / $this->currency;
        $clarcData->lastJawRate = ($jaw->getRate() - $lastJaw->getRate()) / $jaw->getRate();
        $this->addData($clarcData);
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
        return $this->_instruments;
    }

    /**
     * Returns an instrument by name
     *
     * @param $name
     * @return null
     * @throws Exception
     */
    public function getInstrumentByName($name)
    {

        $instruments = $this->getInstruments();

        if (empty($instruments)) {
            throw new Exception('no instuments!');
        }

        foreach ($instruments as $instrument) {
            if ($instrument->getName() == $name) {
                return $instrument;
            }
        }
        return null;
    }

    /**
     * @param ClarcInstrument $instrument
     */
    public function addInstrument(ClarcInstrument $instrument)
    {
        $this->_instruments[] = $instrument;
    }

    /**
     * The logic for the clarc to identify a buy
     *
     * @return bool
     */
    public function identifyBuy()
    {
        $buy = true;
        $data = $this->getData();
        $clarcData = end($data);


        // lock if we are in a buy
        if ($this->in) {
            $buy = false;
        }

        // check if the rate is above the lip
        if (!($clarcData->currencyLipRate >= 0)) $buy = false;
        if (!($clarcData->lastLipRate > 0)) $buy = false;
       // if (!($clarcData->lipTeethRate > 0)) $buy = false;
       // if (!($clarcData->lastTeethRate > 0)) $buy = false;
       // if (!($clarcData->teethJawRate > 0)) $buy = false;
        //if (!($clarcData->currencyJawRate >0)) $buy = false;
        if(!($clarcData->lipJawRate > 0)) $buy = false;

        if ($buy) {
            // all conditions met. We have a buy
            $this->in = 1;
            return 1;
        }
        return 0;

    }

    /**
     * The logic for the Clarc to identify a sell
     * @return bool
     */
    public function indentifySell()
    {
        $clarcData = $this->getData();
        $clarcData = end($clarcData);

        // cant sell if we're not i a buy
        if (!$this->in) {
            return 0;
        }

        $lip = $this->getInstrumentByName('lip');
        $teeth = $this->getInstrumentByName('teeth');

        if (!($clarcData->lipTeethRate <= 0)) {
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