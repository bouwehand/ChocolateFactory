<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/21/14
 * Time: 2:15 PM
 */
$market = new Market();
$clarc  = new Clarc();
//$worm   = new Worm(1);
//$clarc->infuse($worm->getGen());
$market->setClarc($clarc);
$market->run();

$graph = new Graph();
$graph->setXaxis(100, 400, 1);
$graph->setYaxis('1.2720', '1.4000', '0.0120');

// add the rate
$lines = null;
foreach($market->getMarketData() as $marketData) {
    $line = new stdClass();
    $line->step = $marketData->step;
    $line->rate = $marketData->rate;
    $lines['name'] = 'rate';
    $lines['color'] = '00CED1';
    $lines['data'][] = $line;
}
$graph->addLine($lines);

// add the lip
$lines = null;
foreach($market->getMarketData() as $marketData) {
    $line = new stdClass();
    $line->step = $marketData->step;
    $line->rate = $marketData->lip;
    $lines['name'] = 'lip';
    $lines['color'] = 'FF0000';
    $lines['data'][] = $line;
}
$graph->addLine($lines);

// add the teeth
$lines = null;
foreach($market->getMarketData() as $marketData) {
    $line = new stdClass();
    $line->step = $marketData->step;
    $line->rate = $marketData->teeth;
    $lines['name'] = 'teeth';
    $lines['color'] = 'FF6600';
    $lines['data'][] = $line;
}
$graph->addLine($lines);

// add the jaw
$lines = null;
foreach($market->getMarketData() as $marketData) {
    $line = new stdClass();
    $line->step = $marketData->step;
    $line->rate = $marketData->jaw;
    $lines['name'] = 'jaw';
    $lines['color'] = '2C6700';
    $lines['data'][] = $line;
}
$graph->addLine($lines);

$graph->render();
