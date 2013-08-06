<?php 
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('error_reporting', E_ALL);

require 'Fwk/Fwk.php';
$configFile = 'esperluette.ini';


$app = new Fwk\Fwk($configFile);

$app->run();
