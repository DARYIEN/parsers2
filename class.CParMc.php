<?php

  /*/**
   * Created by ~[M!sterX@#]~.
   * Date: 08.07.13
   * Time: 12:44

*/

  class CParMc extends CParMainMC{
      var $city_id;
      var $message = '';
      var $document_urls;
      var $home_url;
      var $company_name;
      static $name_parser = array(
          'mc' => ''
      );
      function start(){
          $this->getDocuments();
          $this->processParsing();
          $mail[$this->city_id] = $this->message;
          return $mail;
      }
      function __construct(){
          $this->iconv = false;
          $this->items = array();
          foreach($this->list_parsers as $city_id => $parser){
              if(in_array(get_class($this), $parser)){
                  $this->city_id = $city_id;
                  break;
              }
          }
          $this->company_name = 'МеталлСервис '.current(array_values($this->cities_list[$this->city_id]));
          $this->formDirsArray()->createDirs();
          $this->document_extended = '.html';

          //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
          $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
          $this->price_type = ISSERVER ? 'safe' : 'safe2';
          $this->dual_cost = true;
      }
      function formDirsArray(){
          $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
          $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
          $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
          $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
          return $this;
      }
      function processParsing(){
          foreach($this->document_list as $path){
              $this->documentParsing($path);
              //unlink($path);
          }
          if(isset($this->prices)){
              foreach($this->prices as $coef=>$price_info){
                  //p($price_info);
                  $this->price_id = $price_info['price_id'];
                  $this->coef = $coef;
                  $this->company_name = $price_info['company_name'];
                  $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
                  $this->save();
                  $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
                  $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
                  if(!empty($this->to_save_new)){
                      $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
                  }

              }
          }else{
              $this->save();
              $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
              $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
              if(!empty($this->to_save_new)){
                  $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
              }
          }


          //$this->save();
      }
      function getDocuments(){
          if(!empty($this->document_urls)){
              foreach($this->document_urls as $url){
                  $this->document_url = $url;
                  $document_name = 'temp_'.md5(time()).'_0_'.$this->document_extended;
                  //echo $document_name;
                  $this->getDocument($document_name);
              }
          }
          return $this;
      }
      function documentParsing($path){
          //gjy$this->items = array();
         // p($path);
          $parse = file_get_html($path);
          $table = $parse->find('div#gen_price table tbody tr');
          if(!empty($table)){
              $result = array();
              foreach($table as $tr){
                  $temp = array();
                  $i = 0;
                  $govnokod = array();
                  $tr->plaintext;
                  foreach($tr->find('td') as $td){
                      if(!empty($td->innertext)){
                          $govnokod[] = $td->innertext;
                      }
                      //$item = preg_replace('/;/i','', $td->innertext);
                      //echo $item;
                      /*if(preg_match('/(\d)(\s|&nbsp;)(\d)/', (int)$td->innertext)){
                          $temp['cost'][] = !empty($td->innertext) ? strip_tags($td->innertext) : 0;
                          die();
                          //continue;
                      }
                      $temp['name'][] = !empty($td->innertext) ? strip_tags($td->innertext) : '';
                      $i++;*/
                  }
                  for($i = 0; $i<=count($govnokod); $i++){
                      if(in_array($i, array(count($govnokod)-1,count($govnokod)-2,count($govnokod)-4))){
                          $temp['cost'][] = !empty($govnokod[$i]) ? preg_replace('/(\s|'.chr(160).')/', '', strip_tags($govnokod[$i])) : '';
                          if(count($temp['cost']) < 3 && (!is_numeric(end($temp['cost'])) || end($temp['cost']) == 0)){
                              //p($temp['cost']);
                              continue 2;
                          }
                          continue;
                      }
                      if(in_array($i, array(count($govnokod)-6,count($govnokod)-5,count($govnokod)-3, ))){
                          continue;
                      }
                      $temp['name'][] = !empty($govnokod[$i]) ? strip_tags($govnokod[$i]) : '';

                  }
                  if(empty($temp)) continue;
                  if(!empty($temp['name'][1])){
                      $size = $temp['name'][1];
                      $temp['name'][1] = $temp['name'][2];
                      $temp['name'][2] = $size;
                  }
                  $temp['name'] = implode(' ',$temp['name']);
                  if(empty($temp['name'])){
                      continue;
                  }
                  $temp['name'] = preg_replace('/\s+/i',' ', $temp['name']);
                  //$temp['cost'] = implode(';',$temp['cost']);
                  $temp['cost'] = clear_array($temp['cost']);
                  //p($temp['cost']);
                  $result[] = $temp;
              }
              $this->items = array_merge($this->items,$result);
              //p($this->items);
              //$match = array();
              //preg_match('/(?<=_)[0-9]+(?=_)/', $path, $match);
              //$this->price_id = current($match);
              //$this->company_name = $parse->find('#page_title h1', 0)->innertext;
              //$this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.time().'.csv';
          }
          $parse->clear();
          unset($parse);
      }
      public function getUrl(){
          if(ISSERVER){$connection = proxyConnector::getIstance();
              $connection->launch($this->home_url, null);
              $result = $connection->getProxyData();
              $parse = str_get_html($result['return']);
              if(!empty($parse)){
                  $links = $parse->find('li#second_tree_level li#third_tree_level a');
                  //p($links);
                  unset($this->curl);
                  $href = array();
                  foreach($links as $link){
                      //p($link->href);
                      $connection->launch('http://mc.ru:8080'.$link->href, null);
                      $result = $connection->getProxyData();
                      $new_parse = str_get_html($result['return']);
                      if(!empty($new_parse) && is_object($new_parse)){
                          $new_links = $new_parse->find('div#products_nav_list ul li a');
                          foreach($new_links as $new_link){
                              $href[] = 'mc.ru:8080'.$new_link->href;
                          }
                      }
                  }
              }
          }else{
              $this->curl = curl_init($this->home_url);
              curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
              //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
              curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
              $result = curl_exec_follow($this->curl);

              $parse = str_get_html($result);
              $links = $parse->find('li#second_tree_level li#third_tree_level a');
              //p($links);
              unset($this->curl);
              $href = array();
              foreach($links as $link){
                  //p($link->href);
                  $this->curl = curl_init('http://mc.ru:8080'.$link->href);
                  curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                  //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
                  curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                  $result = curl_exec_follow($this->curl);
                  $new_parse = str_get_html($result);
                  $new_links = $new_parse->find('div#products_nav_list ul li a');
                  foreach($new_links as $new_link){
                      $href[] = 'mc.ru:8080'.$new_link->href;
                  }
                  //if(count($href) == 10) break;
              }
          }
          //p($href);
          //die();
          /*
          $context = stream_context_create(['http' => ['max_redirects' => 50]]);
          $parse = file_get_html($this->home_url, false, $context);
          $links = $parse->find('li#second_tree_level li#third_tree_level a');
          //p($links);
          $href = array();
          foreach($links as $link){
              $new_parse = file_get_html('http://mc.ru:8080'.$link->href);
              $new_links = $new_parse->find('div#products_nav_list ul li a');
              foreach($new_links as $new_link){
                  $href[] = 'mc.ru:8080'.$new_link->href;
              }
          }
          p($href);
          die();*/
          return $href;
      }
  }

class CParMcCities extends CParMainMC{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    public $stringForFind = 'table tbody tr';
    public $temp = array();
    static $name_parser = array(
        'mc' => ''
    );
    function start(){
        $this->getDocuments();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
        return $mail;
    }
    function __construct(){
        $this->iconv = false;
        $this->items = array();
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }
        $this->company_name = 'МеталлСервис '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';

        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = ISSERVER ? 'safe' : 'safe2';
        $this->dual_cost = true;
        $this->iconv = true;
    }
    function formDirsArray(){
        $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
        return $this;
    }
    function processParsing(){
        foreach($this->document_list as $path){
            $this->filter = array(
                'propusk' => array(4,5,9),
                'cost' => array(6,7,8),
            );
            $this->getTemp($path);
            $this->documentParsing();
            //unlink($path);
        }
        if(isset($this->prices)){
            foreach($this->prices as $coef=>$price_info){
                //p($price_info);
                $this->price_id = $price_info['price_id'];
                $this->coef = $coef;
                $this->company_name = $price_info['company_name'];
                $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
                $this->save();
                $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
                $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
                if(!empty($this->to_save_new)){
                    $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
                }

            }
        }else{
            $this->save();
            $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
            if(!empty($this->to_save_new)){
                $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
            }
        }


        //$this->save();
    }
    function getDocuments(){
        if(!empty($this->document_urls)){
            foreach($this->document_urls as $url){
                $this->document_url = $url;
                $document_name = 'temp_'.md5(time()).'_0_'.$this->document_extended;
                //echo $document_name;
                $this->getDocument($document_name);
            }
        }
        return $this;
    }
    function getTemp($path){
        $parse = file_get_html($path);
        $table = $parse->find($this->stringForFind);
        if(!empty($table)){
            foreach($table as $tr){
                $temp = array();
                foreach($tr->parent->find('td') as $td){
                    //p($td->plaintext);
                    //echo iconv('cp1251','utf-8',$td->innertext);
                    $temp[] =  trim($td->plaintext);
                }
                $this->temp[] = $temp;
            }
        }

        $rukozopy_full = array();
        foreach($this->temp as $t){
            $rukozopy = array();
            foreach($t as $name){
                if($name==''){
                    if(empty($rukozopy)) continue;
                    $rukozopy_full[] = $rukozopy;
                    $rukozopy = array();
                    continue;
                }
                $rukozopy[] = $name;
            }
        }
        $this->temp = $rukozopy_full;
        $parse->clear();
        unset($parse);
        return $this;
        //die();
    }
    function documentParsing(){
        $result = array();
        foreach($this->temp as $items){
            $temp = array();
            $name = '';
            $cost = array();
            //p($items);
            foreach($items as $key => $item){
                if(empty($item)){
                    continue;
                }
                if(in_array($key, $this->filter['propusk'])){
                    //p($item);
                    continue;
                }
                if(in_array($key, $this->filter['cost'])){
                    $cost[] = preg_replace('/(\s)/u', '', $item);
                    if(!is_numeric(end($cost)) || end($cost) == 0){
                        continue;
                    }
                    continue;
                }
                $name .= ' '.$item;
            }
            if(empty($name) || empty($cost)){
                continue;
            }
            $temp['cost'] = $cost;
            $temp['name'] = preg_replace('/\s+/i',' ', $name);
            //$temp['name'] = str_replace(';','', $temp['name']);
            $result[] = $temp;
        }
        $this->items = array_merge($this->items,$result);
    }
    public function getUrl(){
        if(ISSERVER){$connection = proxyConnector::getIstance();
            $connection->launch($this->home_url, null);
            $result = $connection->getProxyData();
            $parse = str_get_html($result['return']);
            $links = $parse->find('li#second_tree_level li#third_tree_level a');
            //p($links);
            unset($this->curl);
            $href = array();
            foreach($links as $link){
                //p($link->href);
                $connection->launch('http://mc.ru:8080'.$link->href, null);
                $result = $connection->getProxyData();
                $new_parse = str_get_html($result['return']);
                $new_links = $new_parse->find('div#products_nav_list ul li a');
                foreach($new_links as $new_link){
                    $href[] = 'mc.ru:8080'.$new_link->href;
                }
            }
        }else{
            $this->curl = curl_init($this->home_url);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
            $result = curl_exec_follow($this->curl);

            $parse = str_get_html($result);
            $links = $parse->find('li#second_tree_level li#third_tree_level a');
            //p($links);
            unset($this->curl);
            $href = array();
            foreach($links as $link){
                //p($link->href);
                $this->curl = curl_init('http://mc.ru:8080'.$link->href);
                curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                $result = curl_exec_follow($this->curl);
                $new_parse = str_get_html($result);
                $new_links = $new_parse->find('div#products_nav_list ul li a');
                foreach($new_links as $new_link){
                    $href[] = 'mc.ru:8080'.$new_link->href;
                }
                break;
            }
        }
        return $href;
    }
}
