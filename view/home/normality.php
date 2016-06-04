<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 4-6-16
 * Time: 23:33
 */
$lava = $this->lava;
echo $lava->render('LineChart', 'Stocks', 'stocks-div', array('width'=>1024, 'height'=>768));