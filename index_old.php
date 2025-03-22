#!/usr/bin/php
<?php
include_once('main.php');
include_once ROOT.'/extension/phpDJEM/config.php';
error_reporting(0);

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

h8();
setlocale(LC_ALL, 'C');
/*$options['n'] = 'CParAgruppMetalloprokat';
$options['p'] = 11;
$options['l'] = 12322;
$options['c'] = 'metal100';*/
$parser = new CParMain('CParMCPlus10');
$parser->start();