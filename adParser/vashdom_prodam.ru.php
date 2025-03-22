<?php
    //define('ROOT', dirname(__FILE__));
    require_once '../adMainer.php';
    //require_once './inc/CreateAd.php';
    $type = 'stroim100';
    $urls=array("SELL" => "http://board.vashdom.ru/board2-1.htm", "SELL_SERVICES" => "http://board.vashdom.ru/board4-1.htm" , "BUY" => "http://board.vashdom.ru/board1-1.htm");
    $ads = array();
    /*if(preg_match("/[А-я]*,\s(?=([А-я-]*)\s[\s]*Телефон\:)/ui","Компания Лесоторговая база предлагает пиломатериалы из лиственных и хвойных пород, погонажные и столярные изделия.
Лесоторговая база, Челябинск
Телефон: (351) 259-35-24
Url: http://www.stroyka74.ru/articles/kachestvennyie-pilomaterialyi-ot-lesotorgovoy-bazyi", $org)){
        //if(preg_match("/\s[^\.][\d]((8|\+7)[\- ]?)?(\(?\d{3,4}\)?[\- ]?)?[\d\- ]{5,10}[\d]\b\s/",$adText, $telephones)){
        //  print_r($telephones);
        /*if(empty($org) || empty($org[0])){
            continue;
        }
        print_r($org);
    }*/
    foreach($urls as $typeAds => $divisionLink){
        $html = file_get_html($divisionLink);
        foreach($html->find("table.bgroups tbody tr td.a a") as $i => $link){
            $boardHtml = file_get_html($link->href);
            foreach($boardHtml->find("a.board_title") as $linkAd){
                $ad = array();
                $createAd = new CreateAd($ad,$type);
                $createAd->userId = STROY_NET_USER_ID;
                $createAd->connectionDB($type);
                $adHtml = file_get_html($linkAd->href);
                $tempData = $adHtml->find("div#content table tbody tr td",0);
                $ad['title'] = $tempData->find("h2",0)->plaintext;
                $ad['source'] = $linkAd->href;
                foreach(array("style","img","table","div","h1","hr","i","a", "h2","p","form","script") as $tag){
                    foreach($tempData->find($tag) as $temp){
                        $temp->outertext = '';
                    }
                }
                $adTextHtml = str_get_html('<jopa>'.$tempData.'</jopa>');
                $adText = str_replace("()","", $adTextHtml->plaintext);
                //print($adText);
                if(preg_match("/([Телефон\:])\s((8|\+7)[\- ]?)?(\(?\d{3,4}\)?[\- ]?)?[\d\- ]{5,10}[\d]\b/ui",$adText, $telephones)){
                //if(preg_match("/\s[^\.][\d]((8|\+7)[\- ]?)?(\(?\d{3,4}\)?[\- ]?)?[\d\- ]{5,10}[\d]\b\s/",$adText, $telephones)){
                  //  print_r($telephones);
                    if(empty($telephones) || empty($telephones[0])){
                        continue;
                    }
                    $ad['phone'] = str_replace(":","",$telephones[0]);
                }

                if(preg_match("/[А-я \-\.A-z0-9\"]*,\s(?=(([А-я-]*)\s[\s]*Телефон\:))/ui",$adText, $org)){
                //if(preg_match("/\s[^\.][\d]((8|\+7)[\- ]?)?(\(?\d{3,4}\)?[\- ]?)?[\d\- ]{5,10}[\d]\b\s/",$adText, $telephones)){
                  //  print_r($telephones);
                    if(empty($org) || empty($org[0])){
                        continue;
                    }
                    $ad['org'] = str_replace(", ","",$org[0]);
                }
                if(preg_match("/(0[1-9]|[12][0-9]|3[01])[- \.](0[1-9]|1[012])[- \.](19|20)\d\d\s-\s([0-1]\d|2[0-3])(:[0-5]\d)/",$adText, $date)){
                    if(empty($date) || empty($date[0])){
                        continue;
                    }
                    //echo str_replace(array(".",",","-"),array("-","",""),$date[0]);
                    $ad['date'] = strtotime(str_replace(array(".",","),array("-","",),str_replace("-","",$date[0])));
                    $adText = str_replace(array("&nbsp;",$date[0]),"",$adText);
                }
                if(empty($ad['phone']) || empty($ad['date'])){
                    continue;
                }
                $ad['type'] = $typeAds;
                $ad['content'] = $adText;
                $ad["cities"] = $createAd->getCitiesFromAd(array("address" => $adText));
                if(empty($ad["cities"])) continue;
                $city = array_values($ad["cities"]);
                $ad['address'] = $city[0];
                //print_r($ad);
                $createAd->ad = $ad;
                $createAd->userId = VASHDOM_ID;
                $createAd->init();
            }
        }
    }





    /*foreach ($board_content = $html->find('div.item-line') as $i=>$short_ad) {
        //if($i < 10)
        $full_ad = $short_ad->find('div.column-board-desc',0);
        $full_ad_link = 'http://www.stroynet.ru'.$full_ad->find('a',0)->getAttribute('href');
        //print_r($full_ad_link);
        $full_ad_html = file_get_html($full_ad_link);
        //$full_ad_html->find('div.description',0)->find('tr',1)->find('td',1)->find('table',1)->find('td',0);
        //$full_ad = $full_ad_html->find('div[id=message_block] > table',0)->find('tr',1)->find('td',1)->find('table',3)->find('td',0);
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
        $ads[$i]['phone'] = iconv('windows-1251','UTF-8',file_get_contents("http://www.stroynet.ru/php/getphone_board.php?id=".$linkId));
        $ads[$i]['email'] = iconv('windows-1251','UTF-8',file_get_contents("http://www.stroynet.ru/php/getemail_board.php?id=".$linkId));
       /* preg_match('|Организация:</span>(.*)<br>|U', iconv('windows-1251','UTF-8',$full_ad->innertext), $ads[$i]['org']);
        $ads[$i]['org'] = trim(str_replace('.', '', $ads[$i]['org'][1]));
        //Phone
        preg_match('|Телефон:</span>(.*)<br>|U', iconv('windows-1251','UTF-8',$full_ad->innertext), $ads[$i]['phone']);
        //Publication date

        $createAd = new CreateAd($ads[$i],$type);
        $createAd->userId = STROY_NET_USER_ID;
        $createAd->init();
            //
            //print_r($ads);
       // }
    }*/
?>