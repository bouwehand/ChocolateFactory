<?php
/**
 * Created by PhpStorm.
 * User: bas
 * Date: 10/6/14
 * Time: 3:55 PM
 */ 
echo " YAHOO API IMPORTER \n\n";

$yahoo = new Yahoo_Model_Import();
$yahoo->run();