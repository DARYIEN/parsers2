#!/usr/bin/php
<?php
include_once('main.php');
include_once ROOT.'/extension/phpDJEM/config.php';
error_reporting(0);
if($argv[2]){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
h8();
setlocale(LC_ALL, 'C');
$name_parser = 1;
if(isset($argv[1]) && !empty($argv[1])) $name_parser = $argv[1];
$parser = new CParMain($name_parser);//хуй
$parser->start();
