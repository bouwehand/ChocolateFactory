<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/18/14
 * Time: 12:30 PM
 */
class ClarcInstrument {
    /**
     * @var
     */
    protected $_name;

    protected $_step;

    protected $_rate;

    /**
     * @var
     */
    protected $_trendLength;


    protected $_offset;

    /**
     * @var
     */
    protected $_trend;

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->_offset = $offset;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->_offset;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->_rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->_rate;
    }

    /**
     * @param mixed $step
     */
    public function setStep($step)
    {
        $this->_step = $step;
    }

    /**
     * @return mixed
     */
    public function getStep()
    {
        return $this->_step;
    }

    /**
     * @param mixed $trend
     */
    public function setTrend($trend)
    {
        $this->_trend = $trend;
    }

    /**
     * @return mixed
     */
    public function getTrend()
    {
        return $this->_trend;
    }

    /**
     * @param $trendLength
     * @internal param mixed $tendLength
     */
    public function setTrendLength($trendLength)
    {
        $this->_trendLength = $trendLength;
    }

    /**
     * @return mixed
     */
    public function getTrendLength()
    {
        return $this->_trendLength;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }

    public function loadData()
    {
        $step = $this->getStep();
        if($step >= $this->getTrendLength() + $this->getOffset()) {
            $data = new DataHandler();
            $trend = $data->getTrend(
                clarc::TRADING_CURRENCY_CODE,
                $this->getStep(),
                $this->getTrendLength(),
                $this->getOffset()
            );
            $rate = Tools::movingAverage($trend, $this->getTrendLength());
        } else $rate = 0;
        $this->setRate($rate);
    }

}