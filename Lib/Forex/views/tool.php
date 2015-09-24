<?php
/**
 * Tool.php
 *
 * Demonstration of the distribution of stock returns
 *
 * @category Youwe Development
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 9/24/15
 *
 */
$lava = $this->lava;
echo $lava->render('LineChart', 'Stocks', 'stocks-div', array('width'=>1024, 'height'=>768));
$test = new Test_Statistical();
$test->normality(183, 10);