<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('ROOT', dirname(__FILE__));
define('PATH_TO_PARSINGTOOL', "https://pt.stroim100.ru");
define('METALTORG_USER_ID', 3602);
define('ISSERVER', true);
include_once ROOT . '/extension/html_dom/simple_html_dom.php';
include_once ROOT . '/extension/htmlpurifier-4.5.0/library/HTMLPurifier.auto.php';
include_once ROOT . '/mainclasses/class.WorkDb.php';
include_once ROOT . '/adParser/inc/CreateAd.php';
include_once ROOT . '/adParser/inc/Ad.php';
include_once ROOT.'/function.php';
