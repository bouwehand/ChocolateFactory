<?php
/**
 * dogeQuestion.php
 *
 * Given the financial market of the dogeCoin on 2 sept. What is the change of making a profit?
 *
 * @category Wolf
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 9/2/15
 *
 *
 */

$csvFilePath = '/home/bas/vhosts/darwin/Lib/Tool/docs/dogeSmilloions.csv';
if (!file_exists($csvFilePath)) {
    throw new Exception('no csv file: ' . $csvFilePath);
}
$csv = array_map('str_getcsv', file($csvFilePath));
$keys = array_shift($csv);
foreach ($csv as $key => $data) {
    if(! ($csv[$key] = array_combine($keys, $data)) ) {
        throw new Exception("CSV row length is inconsistent on line " . $key);
    };
}
die(var_dump($csv));