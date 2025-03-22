<?php
define('ROOT', dirname(__FILE__));
define('ISSERVER', true);
define('TEST_RECOGNIZE', false);
define('LOCAL_RUN_PARSER_PT', true);
include_once ROOT.'/function.php';
include_once ROOT . '/mainclasses/class.WorkDb.php';
include_once ROOT . '/mainclasses/Recognizer.php';
include_once ROOT . '/mainclasses/class.Option.php';
include_once ROOT . '/mainclasses/class.CParMain.php';
include_once(ROOT."/extension/proxy_connector/proxyConnector.class.php");


