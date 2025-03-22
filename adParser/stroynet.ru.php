<?php
    require_once '../adMainer.php';
    require_once 'polipoProxy.php';
    
    error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);
    
    $type = 'stroim100';
    $url="http://www.stroynet.ru/board/";
    $proxies_list = fill_array_of_proxies ('https://api.getproxylist.com/proxy?protocol=http&country=RU', 2);
    $html = get_html_via_proxy($url, $proxies_list);    
    $ads = array();
    foreach ($html->find('div.item-line') as $i=>$short_ad) {
        $full_ad = $short_ad->find('div.column-board-desc',0);
        $full_ad_link = 'http://www.stroynet.ru'.$full_ad->find('a',0)->getAttribute('href');
        $full_ad_html = get_html_via_proxy($full_ad_link, $proxies_list);
        //Source
        if(empty($full_ad_html)){
            continue;
        }
        if(iconv('windows-1251','UTF-8',$full_ad->find('a',0)->plaintext) == "VIP-объявление") continue;
        $ads[$i]['source'] = $full_ad_link;
            //Title
        $ads[$i]['title'] = iconv('windows-1251','UTF-8',$full_ad->find('a',0)->plaintext);
            //Type
        switch(iconv('windows-1251','UTF-8',trim($short_ad->find('.column-board-desc span',0)->plaintext))) {
            case 'КУПЛЮ':
                $ads[$i]['type'] = 'BUY';
                break;
            case '=':
                $ads[$i]['type'] = 'OTHER';
                break;
            case 'УСЛУГИ':
                $ads[$i]['type'] = 'SELL_SERVICES';
                break;
            default:
                $ads[$i]['type'] = 'SELL';
                break;
        }
        //Address
        $table = $full_ad_html->find("div.foto-info div.padding25 table tbody",0);
        $ads[$i]['address'] = str_replace('-',' ',iconv('windows-1251','UTF-8',$table->find("tr",0)->find("td",1)->innertext));
        //Content
        $ads[$i]['content'] = str_replace('Описание','',iconv('windows-1251','UTF-8',$full_ad_html->find('.description',0)->innertext));
        //Organization
        $ads[$i]['org'] = iconv('windows-1251','UTF-8',$table->find("tr",1)->find("td",1)->innertext);
        $ads[$i]['date'] = strtotime(iconv('windows-1251','UTF-8',trim($table->find("tr",2)->find("td",1)->plaintext)));
        $linkId = $table->find("tr",4)->find("td",1)->find('a',0)->id;
        $phone = iconv('windows-1251','UTF-8',get_content_via_proxy("http://www.stroynet.ru/php/getphone_board.php?id=".$linkId, $proxies_list)->plaintext);
        $phone = trim(preg_replace('/.* Connection: keep-alive/', '', $phone));
        $ads[$i]['phone'] = $phone;
        $email = iconv('windows-1251','UTF-8',get_content_via_proxy("http://www.stroynet.ru/php/getemail_board.php?id=".$linkId, $proxies_list)->plaintext);
        $email = trim(preg_replace('/.* Connection: keep-alive/', '', $email));
        $ads[$i]['email'] = $email;
        print_r ($ads[$i]);
        $createAd = new CreateAd($ads[$i],$type);
        $createAd->userId = STROY_NET_USER_ID;
        $createAd->init();
        $full_ad_html->clear();
        unset($full_ad_html);
        sleep(5);
    }
    $html->clear();
    unset($html);
?>