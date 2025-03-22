<?php
    require_once '../adMainer.php';
    
    $url = "http://www.metalinfo.ru/ru/board/";
	$html = file_get_html($url);
    
    $board_content = $html->getElementById('bulletinList');
    $ads = array();
    foreach ($board_content->find('li[class=bulletin]') as $i=>$short_ad) {
       // if($i < 5) {
            $full_ad_link = $short_ad->find('span[class=title] a',0)->getAttribute('href');
            $full_ad_html = file_get_html('http://www.metalinfo.ru' . $full_ad_link);
            $full_ad = $full_ad_html->find('td[class=page_content]',0);

            if(!empty($full_ad)) {
                //Source        
                $ads[$i]['source'] = 'http://www.metalinfo.ru' . $full_ad_link;
                //Title
                $ads[$i]['title'] = $short_ad->find('span[class=title] a',0)->getAttribute('title');
                //Organization
                $ads[$i]['org'] = $short_ad->find('span[class=company]',0)->getAttribute('title');                
                //Address
                $ads[$i]['address'] = $short_ad->find('span[class=region]',0)->getAttribute('title');                
                //Type
                switch(trim($short_ad->find('span[class=cat]',0)->plaintext)) {
                    case 'Продам':
                        $ads[$i]['type'] = 'SELL';
                        break; 
                    case 'Куплю':
                        $ads[$i]['type'] = 'BUY';
                        break;
                    case 'Прочее':
                        $ads[$i]['type'] = 'OTHER';
                        break;
                } 
                //Content            
                $ads[$i]['content'] = trim($full_ad->find('span[itemprop=text]',0)->innertext);
                //Phone        
                preg_match('|<strong>Телефон:</strong>(.*)<br>|U', $full_ad->find('small[class=transperent]',0)->innertext, $ads[$i]['phone']);
                $ads[$i]['phone'] = $ads[$i]['phone'][1];
                //Publication date
                $ads[$i]['date'] = strtotime(date("Y").'-'.date("m").'-'.date("d").' '.trim($short_ad->find('span[class=time]',0)->plaintext));
                $creatieAd = new createAd($ads[$i]);
		print_r($ads[i]);
                $createAd->userId = METALINFO_USER_ID;
                $createAd->init();
                //createAd($ads[$i]); 
            }
        //}
    }

    //header("Content-Type: text/html; charset=windows-1251");    
    //require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
    //$saxi=$_GET['sax'];

    //$url="http://www.metalinfo.ru/ru/board/";
    //$curl = curl_init($url);
    //curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //$text = curl_exec($curl);
    //curl_close($curl);
    //preg_match_all('|<li class="row bulletin">(.*)</li>|is', $text, $result);
    //preg_match_all('|<span class="time">(.*)</span>|U', $result[1][0], $time, PREG_PATTERN_ORDER);
    //preg_match_all('|<span class="cat">(.*)</span>|U', $result[1][0], $typo, PREG_PATTERN_ORDER);
    //preg_match_all('|href="(.*)">|U', $result[1][0], $link, PREG_PATTERN_ORDER);
    //foreach($link[1] as $i=>$l)
    //{
    //	$search_w="board"; 
    //	if(preg_match("/$search_w/i",$l)){ 
    //		
    //	}else{ 
    //		unset($link[1][$i]);
    //	} 
    //}
    //$link[1]=array_values($link[1]);
    //preg_match_all('|html">(.*)</a></span>|U', $result[1][0], $title, PREG_PATTERN_ORDER);
    //preg_match_all('|<span class="company"(.*)</span>|U', $result[1][0], $company, PREG_PATTERN_ORDER);
    //foreach($company[1] as $i=>$com)
    //{
    //	$company[1][$i] = str_replace('title="', "", $com);
    //}
    //preg_match_all('|<span class="region" title="(.*)">|U', $result[1][0], $city, PREG_PATTERN_ORDER);
    //$nn=count($city[1]);
    //foreach($link[1] as $i=>$ll)
    //{
    //	$link[1][$i] = "http://www.metalinfo.ru/{$ll}";
    //}
    //for($i=$saxi; $i<$saxi+1; $i++)
    //{
    //	$qq1="_name = '$title[1][$i]'";
    //	$query1 = R('DJEM')->GetForeach();
    //	$query1->Path('main.metal.Advertisement.$')->Where($qq1)->Limit(1);
    //	$u=0;
    //	foreach ($query1 as $item1 ) {
    //     	$u++;
    //	}
    //	if($u==0)
    //	{
    //		$url=$link[1][$i];
    //		$curl = curl_init($url);
    //		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //		$text = curl_exec($curl);
    //		curl_close($curl);
    //		preg_match('|<span itemprop="text"><p >(.*)</p></span>|is', $text, $obya);
    //		preg_match('|</script><BR>(.*)</SMALL>|is', $text, $phone);
    //		$newDoc = new DJEMDocument($djem);
    //		$newDoc->_parent_id = 387286;
    //		$newDoc->_type = 7647734;
    //		$newDoc->_name = iconv('windows-1251','UTF-8',$title[1][$i]);
    //		$newDoc->org = iconv('windows-1251','UTF-8',$company[1][$i]);
    //		$newDoc->adres = iconv('windows-1251','UTF-8',$city[1][$i]);
    //		$newDoc->txt = iconv('windows-1251','UTF-8',$obya[1]);
    //		if($typo[1][$i] == iconv('UTF-8','Windows-1251',"Продам"))
    //		{
    //			$newDoc->typo = 'prodam';
    //		} else {
    //			$newDoc->typo = 'kuplu';
    //		}
    //		$newDoc->number = iconv('windows-1251','UTF-8',$phone[1]);
    //		$newDocId = $newDoc->Store();
    //	}
    //}
?>
