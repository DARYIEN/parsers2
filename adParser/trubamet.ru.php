<?php
    require_once '../adMainer.php';
	$url = "http://trubamet.ru/";
	$html = file_get_html($url);
    $board_content = $html->getElementById('doska');
    $ads = array();
    foreach ($board_content->find('> div[id]') as $i=>$full_ad) {               
        $short_ad = $full_ad->previousSibling();

        //Source        
        $ads[$i]['source'] = $url;
        //Title
        $ads[$i]['title'] = strtok($full_ad->find('td[colspan]',0)->plaintext, "\n");
	//Content
        $ads[$i]['content'] = $full_ad->find('td[colspan]',0)->plaintext;        
        //Organization
        $ads[$i]['org'] = $short_ad->find('td',3)->plaintext;        
        //Address
        $ads[$i]['address'] = $short_ad->find('td',4)->plaintext;    
        //Phone
        if(preg_match('/http:\/\/([^\s]+)/', $full_ad->innertext) > 0) {
            preg_match('|<noindex>Тел.:</noindex><td>(.*)<tr><td>|U', $full_ad->innertext, $ads[$i]['phone']);
            $ads[$i]['phone'] = $ads[$i]['phone'][1];
        } else {
            preg_match('|<noindex>Тел.:</noindex><td>(.*)</table>|U', $full_ad->innertext, $ads[$i]['phone']);
            $ads[$i]['phone'] = $ads[$i]['phone'][1];
        }        
        //Type
        switch(trim($short_ad->find('td',0)->plaintext)) {
            case 'П':
                $ads[$i]['type'] = 'SELL';
                break;
            case 'К':
                $ads[$i]['type'] = 'BUY';
                break;
        }   
        //Publication date
        $ads[$i]['date'] = strtotime(date("Y").'-'.date("m").'-'.date("d").' '.trim($short_ad->find('td',1)->plaintext));                      
        
        $createAd = new createAd($ads[$i]);
        $createAd->userId = TRUBAMET_USER_ID;
        $createAd->init();
    }             
?>
