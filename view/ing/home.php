<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 3/13/15
 * Time: 11:44 AM
 */
echo $this->lava->render('LineChart', 'Stocks', 'stocks-div', array('width'=>2048, 'height'=>768));
?>
<div id="stocks-div"></div>