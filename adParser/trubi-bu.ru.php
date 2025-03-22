<?php
    require_once '../adMainer.php';
    $url = "http://trubi-bu.ru/";
    $html = file_get_html($url);

    $board_content = $html->getElementById('general_data');
    $ads = array();
    foreach ($board_content->find('tbody > tr[class=all_advert]') as $i=>$full_ad) {        
        $short_ad = $full_ad->previousSibling()->previousSibling();

        //Source        
        $ads[$i]['source'] = $url;
        //Title
        $ads[$i]['title'] = $short_ad->find('a[class=oa]',0)->plaintext;
        //Content
        $ads[$i]['content'] = trim($full_ad->find('div[class=offer_text]',0)->innertext);
        //Organization
        $ads[$i]['org'] = $full_ad->previousSibling()->find('div[class=offer_md_1_col] span',1)->plaintext;
        //Address
        $ads[$i]['address'] = trim($full_ad->find('div[class=offer_md_1_col] span',2)->plaintext);
        if($ads[$i]['address'] == '') {
           $ads[$i]['address'] = trim($full_ad->find('div[class=offer_md_1_col] span',0)->plaintext);
        }
        //Phone
        $ads[$i]['phone'] = trim($full_ad->find('div[class=offer_md_2_col] span',1)->plaintext);
        //Type
        switch(trim($short_ad->find('td[class=adv_type]',0)->plaintext)) {
            case 'П':
                $ads[$i]['type'] = 'SELL';
                break;
            case 'К':
                $ads[$i]['type'] = 'BUY';
                break;
            case 'Р':
                $ads[$i]['type'] = 'OTHER';
                break;
        }     
        //Publication date
        $ads[$i]['date'] = strtotime(date("Y").'-'.date("m").'-'.date("d").' '.$short_ad->find('td[class=first] strong',0)->plaintext);
        $createAd = new CreateAd($ads[$i]);
        $createAd->userId = TRUBI_BU_USER_ID;
        $createAd->init();
    }
    
?>
