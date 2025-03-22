<?php
    //define('ROOT', dirname(__FILE__));
    require_once '../adMainer.php';
    require_once '../extension/phpQuery/phpQuery/phpQuery.php';
    require_once "../extension/proxy_connector/proxyConnector.class.php";;
    //require_once './inc/CreateAd.php';

    error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);
    
    $url="http://www.armaturshiki.ru/board/index.php?entry=0";
    if(!ISSERVER){
        $curl = curl_init($url);
        $cookie = tempnam ("/tmp", "CURLCOOKIE");
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        $result = curl_exec($curl);
        if($result === false){
            echo ('Ошибка: скачивания файла.'.curl_error($curl).' '.$url.' ');
            die(0);
        }
        $htmlAd = phpQuery::newDocumentHTML($result);
    }else{
        $connection = proxyConnector::getIstance();
        $connection->launch($url, null);
        $result = $connection->getProxyData();
        $htmlAd = phpQuery::newDocumentHTML($result['return']);
    }
    $ads = array();
    foreach ($htmlAd->find('table#msgtable tr') as $i=>$short_ad) {
        if($i==0) continue;
        $secondInfo = pq($short_ad);
        $tds = $secondInfo->find("td");
        //echo $i.'<br />';
        if((($i<20) && ($i % 2)!=0) || (($i>20) && ($i % 2)==0)){
            foreach($tds as $j => $td){
                $el = pq($td);
                /*if($j == 0){
                    $ads[$i]['date'] = strtotime($el->text());
                }*/
                if($j == 2){
                    $ads[$i]['type'] = $el->text() == 'Реализуем' ? "SELL" : "BUY";
                }
                //echo $el->text()."<br />";
            }
        }else{
            foreach($tds as $j => $td){
                $el = pq($td);
                $ads[$i-1]['org'] = $el->find("ul li.company")->text();
                $ads[$i-1]['address'] = $el->find("ul li.city")->text();
                $ads[$i-1]['email'] = $el->find("ul li.email")->text();
                $ads[$i-1]['phone'] = $el->find("ul li.phone")->text();
                $ads[$i-1]['title'] = $el->find("h5")->text();
                $adBody = $el->find("div.clearfix");
                $adBody->find("ul")->remove();
                $adBody->find("li")->remove();
                $adBody->find("hr")->remove();
                $adBody->find("h5")->remove();
                $adBody->find("a")->remove();
                $adBody->find("img")->remove();
                $ads[$i-1]['content'] = iconv("windows-1251","utf-8",trim($adBody->html()));
                $ads[$i-1]['content'] .= " ".$ads[$i-1]['address'];
            }
            $ads[$i-1]['date'] = time();
            print_r($ads[$i-1]);
            $createAd = new CreateAd($ads[$i-1]);
            $createAd->userId = ARMATURSHIKI_METALL_ID;
            $createAd->init();

            $createAd = new CreateAd($ads[$i-1],'stroim100');
            $createAd->userId = ARMATURSHIKI_STROIM_ID;
            $createAd->init();
        }
    }
    phpQuery::unloadDocuments();
?>