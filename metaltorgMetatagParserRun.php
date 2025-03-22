<?php
define('ROOT', dirname(__FILE__));
include_once ROOT.'/function.php';
include_once ROOT."/extension/proxy_connector/proxyConnector.class.php";
include_once ROOT.'/mainclasses/MetaltorgMetategParser.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
$metaltorgMetategParser = new MetaltorgMetategParser();
$metaltorgMetategParser->start();
