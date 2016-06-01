<?php
/**
 * Created by PhpStorm.
 * User: B.ouwehand
 * Date: 11/15/15
 * Time: 8:22 PM
 */

//1, 2, 3, 5, 6, 7, 11, 13, 15, 18, 19, 24, 28, 31, 34, 36
$array = array (
    1,3,5,7,9,11,21,23,25,26,27,28,29,38,39,40,42,44,46,48,50,52,54,56,58,60,62,64,66,68,70,72,74,76,78,80,82,84,86,88,90,92,94,96,98,100,102,104,106,108,110,112,114,116,118,122,124,126,128,130,132,134,136,188,190,192,194,196,198,202,204,206,208,210,212,214,216,218,220,222,224,226,228,230,234,240,244,246,248,250,252,254,256,258,260,262,264,266,268,270
);

/**
 * @param $value
 * @return string
 */
function parity($value) {
    if ($value %2 == 0) {
        return  'even';
    } else {
        return  'oneven';
    }
}

$output     = false;
$last       = false;
$init       = false;
$parity     = false;
$output     = false;
$result     = array();

foreach( $array as $key => $value) {

    if(!$init) {
        $init       = $value;
        $last       = $value;
        $lastParity = parity($value);
        continue;
    }

    if (!$parity) {
        $parity = parity($value);
    }

    $diff = $value - $last;
    if ($parity == 'beide'  && $diff > 1 || $diff > 2) {

        if (!$output) {
            $output = $init;
            $parity = $lastParity;
        }

        $result[] = array (
            'range' => $output,
            'type'  => $parity
        );

        $init       = $value;
        $last       = $value;
        $lastParity = parity($value);
        $parity     = false;
        $output     = false;
    }

    if($parity != $lastParity) {
        $parity = 'beide';
    }

    $output     = $init . '-'. $value ;
    $last       = $value;
    $lastParity = parity($value);
}

$result[] = array (
    'range' => $output,
    'type'  => $parity
);

print_r($result);