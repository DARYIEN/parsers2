<?php
require_once '../adMainer.php';
require_once 'polipoProxy.php';

    error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);

    $type = 'stroim100';
    $base_url = 'http://www.stroybest.ru';
    $url = $base_url.'/do/';
    $proxies_list = fill_array_of_proxies ('https://api.getproxylist.com/proxy?protocol=http&country=RU', 2);
    $html = get_html_via_proxy($url, $proxies_list);
    $ads = array();
    $buying_substrings = array('купим', 'куплю', 'купаем', 'купаю');
    
    foreach ($html->find('div[class=subcat] a') as $category) {
        $category_url = $base_url.'/'.($category->getAttribute('href'));
        $category_html = get_html_via_proxy($category_url, $proxies_list);
        echo 'Working through category page: '.$category_url;
        
        foreach ($category_html->find('tr td a') as $i=>$short_ad) {
            if (!$short_ad || !strpos($short_ad->getAttribute('href'), 'do/view')) continue;
            //if (DateTime::createFromFormat('d.m.Y',trim($short_ad->parent->children[0]->plaintext)) < new DateTime('yesterday')) continue;
            echo 'Working through ad page: '.$short_ad->getAttribute('href');
            $full_ad = $short_ad;
            $full_ad_link = $base_url.$full_ad->getAttribute('href');
            $full_ad_html = get_html_via_proxy($full_ad_link, $proxies_list);
            //Source
            if(empty($full_ad_html)){
                continue;
            }
            $ads[$i]['source'] = $full_ad_link;
            //Title
            $ads[$i]['title'] = trim($full_ad->plaintext);
            
            //Content
            $ads[$i]['content'] = trim($full_ad_html->find('div[class=blue]',0)->plaintext);
            
            //Address
            $table = $full_ad_html->find('table[class=block]',0);
            //Type
            $ads[$i]['type'] = 'SELL';
            foreach ($buying_substrings as $buying_substring)
                if (strpos($ads[$i]['title'], $buying_substring) !== FALSE) {
                    $ads[$i]['type'] = 'BUY';
                }
            
            $details = preg_split ('/<br>/', trim($table->find('span[class=smalltext2]',0)));
            $ads[$i]['address'] = $details[2];
            //Organization
            $ads[$i]['org'] = trim(html_entity_decode ($table->find('span[class=smalltext2] a',0)->plaintext));
            $ads[$i]['date'] = strtotime(str_replace('<span class=smalltext2>', '', $details[0]));
            
            
            $company_page = get_html_via_proxy($base_url.'/'.$table->find('span[class=smalltext2] a',0)->getAttribute('href'), $proxies_list);
            $ads[$i]['phone'] =  preg_split('/[:]+/',$company_page->find('table table table table tr td', 10)->plaintext)[1];
            //$ads[$i]['email'] = iconv('windows-1251','UTF-8',get_content_via_proxy("http://www.stroynet.ru/php/getemail_board.php?id=".$linkId, $proxies_list));
            print_r ($ads[$i]);
            $createAd = new CreateAd($ads[$i], $type);
            $createAd->userId = STROY_BEST_RU_USER_ID;
            $createAd->init();
            $full_ad_html->clear();
            unset($full_ad_html);
            sleep (5);
        }
        $category_html->clear();
        unset($category_html);
    }
    $html->clear();
    unset($html);
?>
