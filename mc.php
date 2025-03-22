#!/usr/bin/php
<?php
include_once('main.php');
include_once ROOT.'/extension/phpDJEM/config.php';
error_reporting(E_WARNING);
ini_set('display_errors', 1);
h8();
setlocale(LC_ALL, 'C');
$parser = new CParMainMC();
$parser->start();