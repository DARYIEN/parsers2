<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
include_once('main.php');
include_once ROOT.'/extension/phpDJEM/config.php';
ini_set('error_reporting', E_ERROR);
ini_set('display_errors', 1);

setlocale(LC_ALL, 'C');
$options = getopt("n:p:l:c:");
$parser = new CParMain($options);
$parser->startPT();
echo json_encode($parser->info);
