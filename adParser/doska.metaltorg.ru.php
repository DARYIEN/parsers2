<?php
    //define('ROOT', dirname(__FILE__));
    require_once '../adMainer.php';
    //require_once './inc/CreateAd.php';
    
    //$url="http://doska.metaltorg.ru/?page=1&metal=1&SMDeclarDivision=-1&nearby_regions=checked&nearby_countries=checked&without_exact_fo=checked";
    $url = "https://doska.metaltorg.ru//?page=1";
    $context = stream_context_create(array(
        'https' => array(
            'header' => array('Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0'),
        ),
    ));
    ini_set("user_agent","Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0");
    $html = file_get_html($url, false, $context);
    $ads = array();
    

    foreach ($board_content = $html->find('div[id=offers-list] div[class=list] div[class=offer]') as $i=>$short_ad) {
        //if($i < 10) {
            $full_ad_link = $short_ad->find('a',0)->getAttribute('href');
            $full_ad_html = file_get_html($full_ad_link);
            $full_ad = $full_ad_html->find('div[class=offer_text]',0);
            //Source
            $ads[$i]['source'] = $full_ad_link;
            //Title
            $ads[$i]['title'] = trim($short_ad->find('a',0)->innertext);
            //Type
            switch(trim($short_ad->find('div[class=type-column]',0)->plaintext)) {
                case 'П':
                    $ads[$i]['type'] = 'SELL';
                    break;
                case 'К':
                    $ads[$i]['type'] = 'BUY';
                    break;
                case '=':
                    $ads[$i]['type'] = 'OTHER';
                    break;
                case 'У':
                    if($short_ad->find('div[class=type-column]',0)->class == 'red') {
                        $ads[$i]['type'] = 'BUY_SERVICES';
                    } else {
                        $ads[$i]['type'] = 'SELL_SERVICES';
                    }
                    break;
            }

            //Address
            preg_match('|Регионы:</span>(.*)</div>|U', $full_ad_html->find('div[id=offer]',0)->find('div[class=flex flex-justify-space-between]',0)->innertext, $ads[$i]['address']);
            $ads[$i]['address'] = trim(str_replace('.', '', $ads[$i]['address'][1]));
            //Content
            $ads[$i]['content'] = $full_ad->innertext;
            //Organization
            $ads[$i]['org'] = trim($short_ad->find('div[class=author-column]',0)->plaintext);
            //Phone
            preg_match('|Телефон:</span>(.*)</div>|U', $full_ad_html->find('div[id=offer]',0)->find('div[class=flex flex-justify-space-between]',0), $ads[$i]['phone']);
            $ads[$i]['phone'] = trim(str_replace('.', '', $ads[$i]['phone'][1]));
            //Publication date
            $ads[$i]['date'] = strtotime(date("Y").'-'.date("m").'-'.date("d").' '.trim($short_ad->find('div[class=time-column]',0)->plaintext));

            $createAd = new CreateAd($ads[$i]);
            $createAd->userId = METALTORG_USER_ID;
            $createAd->init();

       // }
    }

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
