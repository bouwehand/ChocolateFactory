<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/21/14
 * Time: 2:15 PM
 */
$first = 2;
$last = 1000;
$min = 11.1200;
$max = 63.2500;

//$dataHandler = new AAPL();
$dataHandler = new Performace();
//$max = $dataHandler->getMax($first, $last);
//$min = $dataHandler->getMin($first, $last);

$Yinterval = ($max - $min) / 20;
$Xinterval = ($last - $first) / 60;

//// set up the graph
$graph = new Graph();
$graph->setXaxis($first, $last, $Xinterval);
$graph->setYaxis($min, $max, $Yinterval);
//
$dataSet = $dataHandler->spin();

foreach($dataSet as $marketData) {
        $line = new stdClass();
        $line->step = $marketData['id'];
        $line->rate = $marketData['close'];
        $lines['name'] = 'rate';
        $lines['color'] = '000000';
        $lines['data'][] = $line;
        if($marketData['opendPosition'] == 1) {
            $graph->addOpen($marketData['id']);
        }

        if($marketData['closedPosition'] == 1) {
            $graph->addClose($marketData['id']);
        }

}
$graph->addLine($lines);


$graph->render();

//$buy = false;
//$openRate = 0;
//$trade = array();
//$i = 0;
//foreach($dataSet as $key => $data) {
//    if ($data['id'] > 5 && !empty($lastData)) {
//        $data['range'] = $data['high'] - $data['low'];
//        $data['mfi'] = '';
//
//        if($data['volume']  > $lastData['volume']) {
//            if($data['range'] > $lastData['range']) {
//                $data['mfi'] = 'green';
//            }
//            if($data['range'] < $lastData['range']) {
//                $data['mfi'] = 'crunch';
//            }
//        }
//
//        // sell
//        if(
//            ($buy == true
//                && ($data['mfi'] == 'green' || $data['mfi'] == 'crunch')
//                && $data['close'] < $data['open']
//            )
//
//            ||
//
//            $buy == true && $data['open'] <= $openRate
//        ){
//            $data['entry'] = 'sell';
//            $buy = false;
//            $trade[$i]['closed'] = $data['id'];
//            $trade[$i]['openrate'] = $openRate;
//            $trade[$i]['closerate'] = $data['close'];
//            $trade[$i]['pips'] = $data['close'] - $openRate;
//
//            $i++;
//        }
//
//        // buy
//        if($buy == false) {
//            if(
//                $data['volume'] > ($lastData['volume'] * 1.30)
//                && ($data['mfi'] == 'green' || $data['mfi'] == 'crunch')
//                && $data['open'] < ($data['high'] - ($data['range'] /3 ) * 2 )
//                //&& $data['close'] > ($data['low'] + ($data['range'] /3 ) * 2 )
//
//                //&& $lastData['open'] > $data['open']
//            ) {
//                $data['entry'] = 'buy';
//                $buy = true;
//                $openRate = $data['open'];
//                $trade[$i]['opened'] = $data['id'];
//            }
//        }
//    }
//    $dataSet[$i] = $data;
//    $lastData = $data;
//}
//
//$array = array();
//foreach($trade as $entry) {
//    $array[] = $entry['pips'];
//}
//
//die(var_dump(array_sum($array)));