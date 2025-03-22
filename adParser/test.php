<?php
/**
 * Created by PhpStorm.
 * User: Феликс
 * Date: 16.11.2015
 * Time: 14:28
 */
require_once '../adMainer.php';
require_once '../extension/phpQuery/phpQuery/phpQuery.php';
require_once "../extension/proxy_connector/proxyConnector.class.php";
require_once "inc/CreateAd.php";
$ad['content'] = "Кран шаровой Faro = Ду15 м/м рыч # шт";
$createAd = new CreateAd($ad,"metal100");
$createAd->pathToParsingTool = "https://parsingtool.stroim.ru:8080";
$createAd->recognizeCategories($ad['content']);
$ad['title'] = preg_replace('/(=)+/', '=',preg_replace('/\s+/', ' ',$createAd::cutRestSymbols(strip_tags(trim(html_entity_decode($ad['title'], ENT_COMPAT | 48, 'UTF-8'))))));
print_r($ad);

