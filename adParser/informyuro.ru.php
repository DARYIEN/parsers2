<?php
    //define('ROOT', dirname(__FILE__));
    require_once '../adMainer.php';
    //require_once './inc/CreateAd.php';
    $type = 'stroim100';
    $url="http://www.informbyuro.ru/board/stroitelstvo/147";
    $html = file_get_html($url);    
    $ads = array();
    foreach ($board_content = $html->find('div#allEntries table') as $i=>$short_ad) {
        //print_r($short_ad->find('tbody#eBlock tr td div.eTitle a',0)->plaintext);
        //if($i < 10)
        $aFull = $short_ad->find('tbody#eBlock tr td div.eTitle a',0);
        if(!$aFull) continue;
        $full_ad_link = $aFull->getAttribute('href');
        //print_r($full_ad_link);
        $full_ad_html = file_get_html($full_ad_link);
        //$full_ad_html->find('div.description',0)->find('tr',1)->find('td',1)->find('table',1)->find('td',0);
        //$full_ad = $full_ad_html->find('td.content');
            //Source
        $ads[$i]['source'] = $full_ad_link;
        $ads[$i]['title'] = $full_ad_html->find('div.eTitle1 h1',0)->plaintext;
        $ads[$i]['type'] = 'SELL';
        $ads[$i]['address'] = $full_ad_html->find('td.eDetails1 span',0)->innertext;
        $ads[$i]['phone'] = $full_ad_html->find('td.eDetails1 u',0)->innertext;
        $ads[$i]['org'] = $full_ad_html->find('td.eDetails1 u',1)->innertext;
        $ads[$i]['content'] = $full_ad_html->find('td.eText',0)->plaintext;
        $date = $full_ad_html->find('table.eBlock tbody tr td',1)->plaintext;
        if(preg_match('/Сегодня/',$date) || preg_match('/Вчера/',$date)){
            $date = str_replace(array('Сегодня','Вчера'),array(date('d-m-Y'),date('d-m-Y',time()-86400)),$date);
        }
        $ads[$i]['date'] = strtotime($date);
        $createAd = new CreateAd($ads[$i],$type);
        $createAd->userId = INFORM_BYURO_ID;
        $createAd->init();
        /*
        if(iconv('windows-1251','UTF-8',$full_ad->find('a',0)->plaintext) == "VIP-объявление") continue;
            //Title
            //Type
        switch(iconv('windows-1251','UTF-8',trim($short_ad->find('.column-board-desc span',0)->plaintext))) {
            case 'ПРОДАЮ':
                break;
            case 'КУПЛЮ':
                $ads[$i]['type'] = 'BUY';
                break;
            case '=':
                $ads[$i]['type'] = 'OTHER';
                break;
            case 'УСЛУГИ':
                $ads[$i]['type'] = 'SELL_SERVICES';
                break;
        }
            //Address
        $table = $full_ad_html->find("div.foto-info div.padding25 table tbody",0);
        //Content
        //Organization
        $linkId = $table->find("tr",4)->find("td",1)->find('a',0)->id;
        $ads[$i]['email'] = iconv('windows-1251','UTF-8',file_get_contents("http://www.stroynet.ru/php/getemail_board.php?id=".$linkId));
       /* preg_match('|Организация:</span>(.*)<br>|U', iconv('windows-1251','UTF-8',$full_ad->innertext), $ads[$i]['org']);
        $ads[$i]['org'] = trim(str_replace('.', '', $ads[$i]['org'][1]));
        //Phone
        preg_match('|Телефон:</span>(.*)<br>|U', iconv('windows-1251','UTF-8',$full_ad->innertext), $ads[$i]['phone']);
        //Publication date

        */
            //
            //print_r($ads);
       // }
    }
    print_r($ads);


//    header("Content-Type: text/html; charset=windows-1251");
//    require_once './inc/CreateAd.php';
//    require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
//    $sax=$_GET['sax'];
//
//    $url="http://doska.metaltorg.ru/?page=0&metal=1&SMDeclarDivision=-1&nearby_regions=checked&nearby_countries=checked&without_exact_fo=checked";
//    $curl = curl_init($url);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//    $text = curl_exec($curl);
//    curl_close($curl);
//    preg_match_all("|<tr valign=middle >(.*)</tr>|is", $text, $result);
//    $tempArr=explode("<div id='b2bcontext'>",$result[1][0],2);
//    preg_match_all('|<span style="padding-left : 4px;">(.*)</span>|U', $tempArr[0], $time, PREG_PATTERN_ORDER);
//    preg_match_all('|<span(.*)</span>&nbsp;|U', $tempArr[0], $ord, PREG_PATTERN_ORDER);
//    preg_match_all('|<a href="(.*)" title="|U', $tempArr[0], $link, PREG_PATTERN_ORDER);
//    preg_match_all('|html" title="(.*)" target="|U', $tempArr[0], $title, PREG_PATTERN_ORDER);
//    preg_match_all('|<td nowrap class="smd-content">(.*)&nbsp;</td>|U', $tempArr[0], $okr, PREG_PATTERN_ORDER);
//    preg_match_all('|&nbsp;(.*)</td>|U', $tempArr[0], $else, PREG_PATTERN_ORDER);
//    for($i=0; $i<300; $i=$i+3)
//    {
//        $cito[$i/3] = $else[1][$i+1];
//        $offer[$i/3] = $else[1][$i+2];
//    }
//    foreach($ord[1] as $i=>$or)
//    {
//        $ord[1][$i] = strip_tags($or);
//    }
//
//    $now_hour = date("G");
//    $now_minu = date("i");
//    $now = 60*$now_hour + $now_minu;
//
//    for($i=0; $i<100; $i++)
//    {
//        $last_query_hour = substr($time[1][$i],0,2); 
//        $last_query_mimu = substr($time[1][$i],3,2);
//        $last_query = 60*$last_query_hour + $last_query_mimu;
//        if($now - $last_query <= 60)
//        {
//            $stop = $i;
//        }
//    }
//
//    $query = R('DJEM')->GetForeach();
//    $query->Path('main.metal.Advertisement.$')
//               ->Sort('-_publish_time')
//               ->Limit(1);
//
//    foreach ($query as $item) {
//         $last = iconv('UTF-8','windows-1251',$item->_name);
//    }
//
//    $key = array_search($last, $title[1]);
//    if($key<$stop AND $key != 0)
//    {
//        $stop=$key;
//    }
//
//    for($i=$sax; $i<$sax+1; $i++)
//    {
//            $qq1="_name = '$title[1][$i]'";
//            $query1 = R('DJEM')->GetForeach();
//            $query1->Path('main.metal.Advertisement.$')
//               ->Where($qq1)->Limit(1);
//            $u=0;
//            foreach ($query1 as $item1 ) {
//                $u++;
//            }
//            if($u==0)
//            {
//                $url=$link[1][$i];
//                $curl = curl_init($url);
//                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//                $text = curl_exec($curl);
//                curl_close($curl);
//                preg_match("|:</span>(.*)<BR><span class|is", $text, $num);
//                preg_match_all("|<p class=txtj>(.*)\n|is", $text, $result);
//                $tempArr=explode('</td>',$result[1][0],2);
//                $last_query_hour = substr($time[1][$i],0,2); 
//                $last_query_mimu = substr($time[1][$i],3,2);
//                $ptime[$i] = strtotime(date("Y").'-'.date("m").'-'.date("d").' '.$last_query_hour.':'.$last_query_mimu)."<br />";
//                if($optime[$i]<$optime[$i-1])
//                {
//                    $day=date("d")-1;
//                }
//                if($optime[$i]<$optime[$i-1])
//                {
//                    $month=date("m")-1;
//                }
//                if($optime[$i]<$optime[$i-1])
//                {
//                    $year=date("Y")-1;
//                }
//                $a=explode('</a><br>',$num[1],2);
//                if(strstr($a[1], "<BR>"))
//                {
//                    preg_match("|.(.*)<BR>|is", $a[1], $num);
//                } else {
//                    preg_match("|.(.*)<br />|is", $a[1], $num);
//                }
//
//                $nums=strip_tags($num[1]);
//                if($nums!="")
//                {
//                    $ptime[$i] = strtotime($year.'-'.$month.'-'.$day.' '.$last_query_hour.':'.$last_query_mimu)."<br />";
//                    $newDoc = new DJEMDocument($djem);
//                    $newDoc->_parent_id = 387286;
//                    $newDoc->_type = 7647734;
//                    $newDoc->_name = iconv('windows-1251','UTF-8',$title[1][$i]);
//                    $newDoc->org = iconv('windows-1251','UTF-8',$offer[$i]);
//                    $newDoc->adres = iconv('windows-1251','UTF-8',"{$okr[1][$i]}, {$cito[$i]}");
//                    $newDoc->txt = iconv('windows-1251','UTF-8',$tempArr[0]);
//                    if($ord[1][$i] == iconv('UTF-8','Windows-1251'," >П"))
//                    {
//                        $newDoc->typo = 'prodam';
//                    } 
//                    if($ord[1][$i] == iconv('UTF-8','Windows-1251'," >К")) {
//                        $newDoc->typo = 'kuplu';
//                    } 
//                    if($ord[1][$i] == iconv('UTF-8','Windows-1251'," >У")) {
//                        $newDoc->typo = 'pred_uslugi';
//                    } 
//                    if($ord[1][$i] == iconv('UTF-8','Windows-1251'," >=")) {
//                        $newDoc->typo = 'other';
//                    }
//                    $newDoc->number = iconv('windows-1251','UTF-8',$nums);
//                    $newDoc->_publish_time = $ptime[$i];
//                    $newDocId = $newDoc->Store();
//                }
//            }
//    }
?>
