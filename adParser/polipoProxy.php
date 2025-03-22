<?php
require_once '../adMainer.php';
require_once '../extension/phpQuery/phpQuery/phpQuery.php';
require_once "../extension/proxy_connector/proxyConnector.class.php";;

function fill_array_of_proxies ($url = 'https://api.getproxylist.com/proxy?protocol=http&country=RU', $array_count = 10) {
    
    return array ('localhost:8123');
    //return array('178.237.180.34:57307', '194.226.61.18:51310', '46.235.71.241:8080', '158.58.133.187:34128', '95.156.102.34:46583');
    
    /*
    $result = array();
    $json = null;
    stream_context_set_default(['http'=>['proxy'=>'178.237.180.34:57307']]);
    for ($i = 0; $i < $array_count; $i++) {
        $json = json_decode(file_get_contents('https://api.getproxylist.com/proxy?protocol=http&country=RU'));
        if ($json->ip != null) {
            array_push($result, $json->ip.':'.$json->port);
        } else {
            $i--;
        }
        sleep(30);
    }
    return $result;*/    
}

function get_html_via_proxy($url, $proxy_array = null) {
    $htmlAd = false;
    while (!$htmlAd) {
        $connection = proxyConnector::getIstance();
        $connection->launch($url, null);
        $result = $connection->getProxyData();
        $htmlAd = str_get_html($result['return']);
    }
    return $htmlAd;
}

function get_content_via_proxy($url, $proxy_array = null) {
    $htmlAd = false;
    while (!$htmlAd) {
        $connection = proxyConnector::getIstance();
        $connection->launch($url, null);
        $result = $connection->getProxyData();
        $htmlAd = str_get_html($result['return']);
    }
    return $htmlAd;
}
?>