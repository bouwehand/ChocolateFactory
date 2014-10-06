<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/21/14
 * Time: 3:44 PM
 */
class Graph{

    /**
     * @var $_PNG PNG class wrap for the image constructor
     */
    protected $_PNG;

    protected $_padding     = 10;
    protected $_maxY        = 700;
    protected $_maxX        = 1000;
    protected $_textWidth   = 50;
    protected $_textHeight  = 30;
    protected $_gridSizeY   = 50;
    protected $_gridSizeX   = 25;
    protected $_gridColor   = 'DDDDDD';
    protected $_axisColor   = '000000';

    // xAxis specific
    protected $_xAxisStart;
    protected $_xAxisEnd;
    protected $_xAxisInterval;

    // yAxis specific
    protected $_yAxisStart;
    protected $_yAxisEnd;
    protected $_yAxisInterval;

    protected $_lines = array();

    /**
     * @param mixed $yAxisEnd
     */
    public function setYAxisEnd($yAxisEnd)
    {
        $this->_yAxisEnd = $yAxisEnd;
    }

    /**
     * @return mixed
     */
    public function getYAxisEnd()
    {
        return $this->_yAxisEnd;
    }

    /**
     * @param mixed $yAxisInterval
     */
    public function setYAxisInterval($yAxisInterval)
    {
        $this->_yAxisInterval = $yAxisInterval;
    }

    /**
     * @return mixed
     */
    public function getYAxisInterval()
    {
        return $this->_yAxisInterval;
    }

    /**
     * @param mixed $yAxisStart
     */
    public function setYAxisStart($yAxisStart)
    {
        $this->_yAxisStart = $yAxisStart;
    }

    /**
     * @return mixed
     */
    public function getYAxisStart()
    {
        return $this->_yAxisStart;
    }


    /**
     * @param mixed $xAxisEnd
     */
    public function setXAxisEnd($xAxisEnd)
    {
        $this->_xAxisEnd = $xAxisEnd;
    }

    /**
     * @return mixed
     */
    public function getXAxisEnd()
    {
        return $this->_xAxisEnd;
    }

    /**
     * @param mixed $xAxisInterval
     */
    public function setXAxisInterval($xAxisInterval)
    {
        $this->_xAxisInterval = $xAxisInterval;
    }

    /**
     * @return mixed
     */
    public function getXAxisInterval()
    {
        return $this->_xAxisInterval;
    }

    /**
     * @param mixed $xAxisStart
     */
    public function setXAxisStart($xAxisStart)
    {
        $this->_xAxisStart = $xAxisStart;
    }

    /**
     * @return mixed
     */
    public function getXAxisStart()
    {
        return $this->_xAxisStart;
    }



    /**
     * @param string $gridColor
     */
    public function setGridColor($gridColor)
    {
        $this->_gridColor = $gridColor;
    }

    /**
     * @return string
     */
    public function getGridColor()
    {
        return $this->_gridColor;
    }


    /**
     * @param int $gridSizeX
     */
    public function setGridSizeX($gridSizeX)
    {
        $this->_gridSizeX = $gridSizeX;
    }

    /**
     * @return int
     */
    public function getGridSizeX()
    {
        return $this->_gridSizeX;
    }


    /**
     * @param int $maxX
     */
    public function setMaxX($maxX)
    {
        $this->_maxX = $maxX;
    }

    /**
     * @return int
     */
    public function getMaxX()
    {
        return $this->_maxX;
    }



    /**
     * @param int $textHeight
     */
    public function setTextHeight($textHeight)
    {
        $this->_textHeight = $textHeight;
    }

    /**
     * @return int
     */
    public function getTextHeight()
    {
        return $this->_textHeight;
    }

    /**
     * @param int $gridSizeY
     */
    public function setGridSizeY($gridSizeY)
    {
        $this->_gridSizeY = $gridSizeY;
    }

    /**
     * @return int
     */
    public function getGridSizeY()
    {
        return $this->_gridSizeY;
    }

    /**
     * @param int $padding
     */
    public function setPadding($padding)
    {
        $this->_padding = $padding;
    }

    /**
     * @return int
     */
    public function getPadding()
    {
        return $this->_padding;
    }

    /**
     * @param int $maxY
     */
    public function setMaxY($maxY)
    {
        $this->_maxY = $maxY;
    }

    /**
     * @return int
     */
    public function getMaxY()
    {
        return $this->_maxY;
    }

    /**
     * @param string $axisColor
     */
    public function setAxisColor($axisColor)
    {
        $this->_axisColor = $axisColor;
    }

    /**
     * @return string
     */
    public function getAxisColor()
    {
        return $this->_axisColor;
    }

    /**
     * @param int $textWidth
     */
    public function setTextWidth($textWidth)
    {
        $this->_textWidth = $textWidth;
    }

    /**
     * @return int
     */
    public function getTextWidth()
    {
        return $this->_textWidth;
    }

    /**
     * @param array $lines
     */
    public function setLines($lines)
    {
        $this->_lines = $lines;
    }

    /**
     * @return array
     */
    public function getLines()
    {
        return $this->_lines;
    }

    /**
     *
     */
    public function __construct()
    {

    }

    /**
     * @param \PNG $PNG
     */
    public function setPNG($PNG)
    {
        $this->_PNG = $PNG;
    }

    /**
     * @return \PNG
     */
    public function getPNG()
    {
        return $this->_PNG;
    }

    /**
     *
     */
    protected function _drawCartesianSpace(){

        $this->_drawYAxisBlock();
        $this->_drawXAxisBlock();
        $this->_drawGrid();
        $this->_drawLines();

        return $this;
    }

    protected function _drawLines() {
        $lines = $this->getLines();
        foreach($lines as $line) {
            $this->_drawLine($line);
        }
    }

    protected function _drawLine($line) {

        $png = $this->getPNG();
        $xAxisStart = $this->getXAxisStart();
        $xAxisInterval = $this->getXAxisInterval();
        $gridSizeX = $this->getGridSizeX();

        $yAxisStart = $this->getYAxisStart();
        $yAxisInterval = $this->getYAxisInterval();
        $gridSizeY = $this->getGridSizeY();

        $lasLinePoint = null;
        foreach($line['data'] as $linePoint) {
            if($lasLinePoint) {
                $xx = ((($lasLinePoint->step - $xAxisStart) / $xAxisInterval ) * $gridSizeX) + $this->getTextWidth() + $this->getPadding();
                $xy = ((($lasLinePoint->rate - $yAxisStart) / $yAxisInterval ) * $gridSizeY) + $this->getTextHeight() + $this->getPadding();

                $yx = ((($linePoint->step - $xAxisStart) / $xAxisInterval ) * $gridSizeX) + $this->getTextWidth() + $this->getPadding();
                $yy = ((($linePoint->rate - $yAxisStart) / $yAxisInterval ) * $gridSizeY) + $this->getTextHeight() + $this->getPadding();
                $png->drawLine($xx, $xy, $yx, $yy, $line['color']);
            }
            $lasLinePoint = $linePoint;
        }
    }

    /**
     * Draw the block for the Y axis
     */
    protected function _drawYAxisBlock()
    {
        $png = $this->getPNG();
        $padding = $this->getPadding();
        $maxY = $this->getMaxY();
        $axisColor = $this->getAxisColor();
        $textWidth = $this->getTextWidth();
        $textHeight = $this->getTextHeight();
        $gridSizeY = $this->getGridSizeY();

        $x1 = $padding + $textWidth;
        $x2 = $padding + $textHeight;

        // draw y axis
        $png->drawLine($x1, $x2, $x1, $maxY + $x2, $axisColor);

        // draw along x axis
        $yAxisStart = $this->getYAxisStart();
        $yAxisInterval = $this->getYAxisInterval();

        for($i = $x2; $i <= $maxY + $x2; $i = $i+ $gridSizeY) {
            $png->drawText(11, 0, $padding, $i, $axisColor, $yAxisStart);
            $yAxisStart +=$yAxisInterval;
        }
    }

    /**
     * Draw block for Y axis
     */
    protected function _drawXAxisBlock()
    {
        $png = $this->getPNG();
        $padding = $this->getPadding();
        $maxX = $this->getMaxX();
        $axisColor = $this->getAxisColor();
        $textWidth = $this->getTextWidth();
        $textHeight = $this->getTextHeight();
        $gridSizeX = $this->getGridSizeX();

        $x1 = $padding + $textWidth;
        $x2 = $padding + $textHeight;

        // draw x axis
        $png->drawLine($x1, $x2, $maxX + $x1, $x2, $axisColor);

        // draw along x axis
        $xAxisStart = $this->getXAxisStart();
        $xAxisInterval = $this->getXAxisInterval();

        for($i = $x1; $i <= $maxX + $x1; $i = $i+ $gridSizeX) {
            $png->drawText(11, 0, $i, $padding, $axisColor, $xAxisStart);
            $xAxisStart += $xAxisInterval;
        }
    }

    /**
     *
     */
    protected function _drawGrid()
    {
        $png = $this->getPNG();
        $padding = $this->getPadding();
        $maxX = $this->getMaxX();
        $maxY = $this->getMaxY();
        $textWidth = $this->getTextWidth();
        $textHeight = $this->getTextHeight();
        $gridColor = $this->getGridColor();
        $gridSizeX = $this->getGridSizeX();
        $gridSizeY = $this->getGridSizeY();

        $x1 = $padding + $textWidth;
        $x2 = $padding + $textHeight;

        // draw along x axis
        for($i = $x1 + $gridSizeX; $i <= $maxX + $padding; $i = $i+ $gridSizeX) {
            $png->drawLine($i, $x2, $i, ($maxY + $x2), $gridColor);
        }

        // draw y axis
        for($i = $x2 + $gridSizeY ; $i <=($maxY + $x2); $i = $i + $gridSizeY) {
            $png->drawLine($x1, $i, ($maxX + $x1), $i, $gridColor);
        }
    }

    /**
     * @param $start
     * @param $end
     * @param $interval
     * @return $this
     */
    public function setXAxis($start, $end, $interval) {

        $gridSizeX = $this->getGridSizeX();
        $this->setXAxisStart($start);
        $this->setXAxisEnd($end);
        $this->setXAxisInterval($interval);

        $maxX = (($end - $start) / $interval) * $gridSizeX;
        $this->setMaxX($maxX);
        return $this;
    }

    /**
     * @param $start
     * @param $end
     * @param $interval
     * @return $this
     */
    public function setYAxis($start, $end, $interval) {

        $gridSizeY = $this->getGridSizeY();
        $this->setYAxisStart($start);
        $this->setYAxisEnd($end);
        $this->setYAxisInterval($interval);

        $maxY = (($end - $start) / $interval) * $gridSizeY;
        $this->setMaxY($maxY);
        return $this;
    }

    public function addLine($lines) {
        $this->_lines[] = $lines;
    }

    /**
     *
     */
    public function render()
    {

        // start render phase
        $imageXSize = $this->getMaxX() + ($this->getPadding() * 2) + $this->getTextWidth();
        $imageYSize = $this->getMaxY() + ($this->getPadding() * 2) + $this->getTextHeight();
        $png = new PNG($imageXSize, $imageYSize);
        $this->setPNG($png);
        $this->_drawCartesianSpace();
        $png = $this->getPNG();
        $png->render();
    }
}