<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/21/14
 * Time: 2:25 PM
 */
class PNG {

    /**
     * @var The image holder
     */
    protected $_png;

    protected $_xImageSize = 1024;
    protected $_yImageSize = 768;

    /**
     * @param \The $png
     */
    public function setPng($png)
    {
        $this->_png = $png;
    }

    /**
     * @return \The
     */
    public function getPng()
    {
        return $this->_png;
    }

    /**
     * @param int $xImageSize
     */
    public function setXImageSize($xImageSize)
    {
        $this->_xImageSize = $xImageSize;
    }

    /**
     * @return int
     */
    public function getXImageSize()
    {
        return $this->_xImageSize;
    }

    /**
     * @param int $yImageSize
     */
    public function setYImageSize($yImageSize)
    {
        $this->_yImageSize = $yImageSize;
    }

    /**
     * @return int
     */
    public function getYImageSize()
    {
        return $this->_yImageSize;
    }

    public function __construct($imageXSize = null, $imageYSize = null)
    {
        if($imageXSize != null) {
            $this->setXImageSize($imageXSize);
        }

        if($imageYSize != null ) {
            $this->setYImageSize($imageYSize);
        }

        $imageXSize = $this->getXImageSize();
        $imageYSize = $this->getYImageSize();

        $png = imagecreatetruecolor($imageXSize, $imageYSize);
        $this->setPng($png);
        imagesavealpha($this->getPng(), true);

        $trans_colour = imagecolorallocatealpha($this->getPng(), 0, 0, 0, 127);
        imagefill($this->getPng(), 0, 0, $trans_colour);
    }

    /**
     * Transforms a hexidecimal color to a rgb array
     *
     * http://nl1.php.net/manual/en/function.imagecolorallocate.php#57536
     *
     * @param $color
     * @return array
     */
    public function HEX2RGB($color){
        $color_array = array();
        $hex_color = strtoupper($color);
        for($i = 0; $i < 6; $i++){
            $hex = substr($hex_color,$i,1);
            switch($hex){
                case "A": $num = 10; break;
                case "B": $num = 11; break;
                case "C": $num = 12; break;
                case "D": $num = 13; break;
                case "E": $num = 14; break;
                case "F": $num = 15; break;
                default: $num = $hex; break;
            }
            array_push($color_array,$num);
        }
        $R = (($color_array[0] * 16) + $color_array[1]);
        $G = (($color_array[2] * 16) + $color_array[3]);
        $B = (($color_array[4] * 16) + $color_array[5]);
        return array($R,$G,$B);
    }

    /**
     * Transform the grid we are drawing
     *
     * @param $xx
     * @param $xy
     * @param $yx
     * @param $yy
     * @return stdClass
     */
    public function transformGrid($xx, $xy , $yx , $yy) {

        $grid = new stdClass();
        $grid->xx = $xx;
        $grid->xy = ($this->getYImageSize() - $xy);
        $grid->yx = $yx;
        $grid->yy = ($this->getYImageSize() - $yy);
        return $grid;

    }

    public function drawLine($xx, $xy, $yx, $yy, $hex)
    {
        $rgb = $this->HEX2RGB($hex);
        $color = imagecolorallocate($this->getPng(), $rgb[0], $rgb[1], $rgb[2]);
        $grid = $this->transformGrid($xx, $xy , $yx , $yy);
        if(!imageline($this->getPng() , $grid->xx , $grid->xy , $grid->yx , $grid->yy , $color )) {
            throw new Exception('line not valid!');
        };
    }

    public function drawText( $fontSize, $angle, $x , $y , $colorHex, $text)
    {
        // Path to our ttf font file
        $fontFile = APP_LIB . '/Draw/tmp/arialbd.ttf';
        $im = $this->getPng();
        $rgb = $this->HEX2RGB($colorHex);
        $color = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
        $grid = $this->transformGrid($x, $y , $x , $y);
        imagefttext($im, $fontSize, $angle, $grid->xx, $grid->yy, $color, $fontFile, $text);
    }

    public function render()
    {
        header("Content-type: image/png");
        imagepng($this->getPng());
    }


}