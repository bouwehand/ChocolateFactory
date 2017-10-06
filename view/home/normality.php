<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 4-6-16
 * Time: 23:33
 */
$lava = $this->lava;
?>
    <div id="poll_div"></div>
<?= $lava->render('BarChart', 'Votes', 'poll_div', array('width'=>1000, 'height'=>1000)) ?>