<?php
    require_once '../adMainer.php';

    error_reporting(E_ALL ^ E_STRICT);
    
    $base_url = "https://www.stroi-baza.ru/newmessages/";
    $url = $base_url."index.php?rid=3";
    $html = file_get_html($url);
    $ads = array();
    $buying_substrings = array('купим', 'куплю', 'купаем', 'купаю');
    
    foreach ($html->find('div[class=mes_center]') as $i=>$short_ad) {
        $full_ad_link = $short_ad->find('a',0)->getAttribute('href');
        $full_ad_link = $base_url.$full_ad_link;
        
        $full_ad_html = file_get_html($full_ad_link);
        
        $full_ad = $full_ad_html->find('div[class=mes_center]',0);
        //Source
        $ads[$i]['source'] = $full_ad_link;
        //Title
        $ads[$i]['title'] = trim($short_ad->find('a',0)->getAttribute('title'));
        //Type
        $ads[$i]['type'] = 'SELL';
        foreach ($buying_substrings as $buying_substring)
            if (strpos($ads[$i]['title'], $buying_substring) !== FALSE) { 
                $ads[$i]['type'] = 'BUY';
            }
        //Address
        $ads[$i]['address']= $full_ad->find('div[class=kont_inf]',0)->find('div',1)->find('span', 0)->plaintext;
        $ads[$i]['address'] = trim($ads[$i]['address'].' '.$full_ad->find('div[class=kont_inf]',0)->find('div',1)->find('span', 1)->plaintext);
        //Content
        $ads[$i]['content'] = '';
        $first_line_flag = true;
        foreach($full_ad->find('p') as $content) {
            if (!$first_line_flag && !strpos($content, 'Объявление размещено(редактировано):')) $ads[$i]['content'] .= $content->plaintext;
            $first_line_flag = false;
        }
        //Organization
        $ads[$i]['org'] = $full_ad->find('div[class=kont_inf]',0)->find('div',0)->find('span', 0)->plaintext;
        //Phone
        $ads[$i]['phone'] = $full_ad->find('div[class=kont_inf]',0)->find('div',1)->find('span', 2)->plaintext;
        //Email
        $ads[$i]['email'] = $full_ad->find('div[class=kont_inf]',0)->find('div',1)->find('span', 3)->plaintext;
        //Publication date
        $parsed_date = str_replace('в ', '', trim($full_ad_html->find('div[class=comment] p strong',0)->plaintext));
        $ads[$i]['date'] = strtotime(DateTime::createFromFormat('d/m/Y H:i',$parsed_date)->format('Y-m-d H:i:s'));

        $createAd = new CreateAd($ads[$i], 'stroim100');
        $createAd->userId = STROI_BAZA_RU_USER_ID;
        $createAd->init();
    }
    
?>
