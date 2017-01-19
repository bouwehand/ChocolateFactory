<?php
$market = new Market();
$market->run();
$marketData = $market->getMarketData();
echo "\n\n Running market \n\n";
foreach($marketData as $i => $marketState) {
    if($i == 0){
        foreach($marketState as $key => $value) {
            echo $key . "\t";
        }
        echo "\n";
    }
    foreach($marketState as $key => $value) {
        echo $value . "\t";
    }
    echo "\n";
}