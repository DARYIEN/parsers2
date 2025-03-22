<?php
include_once('main.php');
include_once ROOT.'/extension/phpDJEM/config.php';
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_WARNING);
///ini_set('display_errors', 1);
//h8();
setlocale(LC_ALL, 'C');
$options = getopt("n:p:l:c:");
/*$options['n'] = 'CParMetallocentrAstrahan';
$options['p'] = 11;
$options['l'] = 12322;*/
$parser = new CParMain($options);
$parser->startPT();
echo json_encode($parser->info);