<?php
require_once '../adMainer.php';
require_once 'polipoProxy.php';

    error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);

    $type = 'stroim100';
    $base_url = 'http://xn--80abbembcyvesfij3at4loa4ff.xn--p1ai';
    $url = $base_url."/?qact=search_adv&Folder=44";
    $proxies_list = fill_array_of_proxies ('https://api.getproxylist.com/proxy?protocol=http&country=RU', 2);
    $html = get_html_via_proxy($url, $proxies_list);    
    $ads = array();
    foreach ($html->find('table[class=advert-list-table] tr[class=vatop]') as $i=>$short_ad) {
        sleep (10);
        $full_ad = $short_ad->find('div[class=advert-header-in-list]',0);
        if (!$full_ad) continue;
        $full_ad_link = $base_url.$full_ad->find('a',0)->getAttribute('href');
        $full_ad_html = get_html_via_proxy($full_ad_link, $proxies_list);
        //Source
        if(empty($full_ad_html)){
            continue;
        }
        $ads[$i]['source'] = $full_ad_link;
        //Title
        $ads[$i]['title'] = trim($full_ad->find('a',0)->plaintext);
        
        //Content
        $ads[$i]['content'] = trim($full_ad_html->find('div[itemprop=description]',0)->innertext);
        
        //Address
        $table = $full_ad_html->find('div[class=adaptive-main] div[itemtype="https://schema.org/Product"] table[class=trheight22]',0);
        //Type
        $ads[$i]['type'] = 'SELL';
        $type_string = trim($table->find('tr td', 3)->plaintext);
        if (strlen($table->find('tr td', 0)->plaintext)==10)
            $type_string = trim($table->find('tr td', 5)->plaintext);
        if (ord($type_string[1]) == 0x9a) {
            $ads[$i]['type'] = 'BUY';
        }
        //$ads[$i]['type'] = trim($short_ad->find('span',1)->plaintext);
        
        $city_name = html_entity_decode (trim($table->find('tr td div',0)->plaintext));
        if (strlen($table->find('tr td', 0)->plaintext)==10)
            $city_name = html_entity_decode (trim($table->find('tr', 1)->find('td div',0)->plaintext));
        $ads[$i]['address'] = str_replace(')','',str_replace('(','', $city_name));
        //Organization
        $ads[$i]['org'] = trim(html_entity_decode ($table->find('div[class=user_profile] table tr td span', 0)->innertext));
        $ads[$i]['date'] = strtotime(trim($table->find('td[data-giraff=date]',0)->plaintext));
        if ($table->find('tr[class=mo-hide] td', 1))
            $ads[$i]['phone'] =  $table->find('tr[class=mo-hide] td', 1)->find('a',0)->plaintext;
        //$ads[$i]['email'] = iconv('windows-1251','UTF-8',get_content_via_proxy("http://www.stroynet.ru/php/getemail_board.php?id=".$linkId, $proxies_list));
        print_r ($ads[$i]);
        $createAd = new CreateAd($ads[$i],$type);
        $createAd->userId = FREE_ADS_RF_USER_ID;
        $createAd->init();
        $full_ad_html->clear();
        unset($full_ad_html);
        sleep (5);
    }
    $html->clear();
    unset($html);
?>
