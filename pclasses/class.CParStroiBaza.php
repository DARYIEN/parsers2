<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */
include_once ROOT . '/extension/phpQuery/phpQuery/phpQuery.php';
class CParStroiBaza extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'MetaltorgMetalBaza' => 'Металлоторг-metal-baza'
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
        $this->company_name = current(array_values(self::$name_parser)).' '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        // $this->home_url = 'http://www.constali.ru/armatura-gladkaya-a1';
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'gzip';
        $this->dual_cost = false;
        $this->decimal = true;
        $this->document_urls = array(   'https://www.stroi-baza.ru/newmessages/index.php?rid=3',
                                    );
        $this->home_url = current($this->document_urls);
        $this->price_id = 13997775;
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
        //p($this->document_list);
        foreach($this->document_list as $path){
            $this->filter = array('cost' => array(1), 'hide' => array(), 'coef' => 1, 'selector' => 'div.');
            $this->documentParsing($path);
            //unlink($path);
        }
        $this->save();
        $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
        $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
        //$this->save();
    }
    function getDocuments(){
        if(!empty($this->document_urls)){
            foreach($this->document_urls as $url){
                $this->document_url = $url;
                //p($url);
                $this->getDocument();
            }
        }
        return $this;
    }
    function documentParsing($path){
        $parse = phpQuery::newDocumentFileHTML($path);
        $table = $parse->find($this->filter['selector']);
        if(!empty($table)){
            $result = array();
            $table_number  = 1;
            $el = pq($table);
            foreach ($el->find('tr') as $tr) {
                //echo 1;
                $name = '';
                $costs = array();
                $el_td = pq($tr)->find("td");
                foreach ($el_td as $key => $value) {
                    $td = pq($value);
                    //echo $key."=>".$td->text()."<br />";
                    if($td->text()=="Наименование") continue 2;
                    if(in_array($key, $this->filter['hide'])) continue;
                    if (in_array($key,$this->filter['cost'])) {
                        $costs = array();
                        $cost = preg_replace('~[^0-9\.]+~','', $td->text());
                        $cost = str_replace(',', '.', $cost);
                        //echo "cost - - - ".$cost."<br />";
                        if (is_numeric($cost) && $cost != 0) {
                            $cost = str_replace('.', ',', $cost);
                            $costs[] = $cost*$this->filter['coef'];
                        }
                        //print_r($costs);
                        continue;
                    }
                    $name .= ' ' . $td->text();
                }
                if (empty($costs)) continue;
                $name = str_replace('&ndash', '-', $name);
                $name = str_replace(array('&nbsp;', '&#160;'), ' ', $name);
                //$name = strip_tags($name);
                //$name = html_entity_decode($name);
                $name = trim($name, '&nbsp;');
                $name = trim($name);
                $name = str_replace(';', '!', $name);
                $name = str_replace('×', 'х', $name);
                $name = str_replace('б/у', 'б/у ', $name);
                $name = preg_replace('/\s+/iu', ' ', $name);
                $name = preg_replace('/[\(\)\[\]\'"]/iu', ' ', $name);
                if(isset($this->filter['dop'])) $name .= $this->filter['dop'];
                $result[] = array('name' => $name, 'cost' => $costs,);
            }
            $this->items = array_merge($this->items,$result);
            //p($this->items);
        }
        unset($parse);
        //die();
    }
    function documentParsingOld($path){
        $parse = file_get_html($path);
        $headers = $parse->find('div');
        $tables = $parse->find('table');
        $text = array();
//        if(!empty($headers)){
//            foreach($headers as $header){
//                foreach($header->find('em strong.section') as $content){
//                    $text[]=trim(str_replace('Прайс-лист |', '', $content->plaintext));
//
//                }
//            }
//            //p($text);
//        }

               $cell=array(
                   'standart'=>array('stop_cell'=>3,'cost_cell1'=>5,),
               );

        if(!empty($tables)){
            $result = array();
            $table_number  = 1;
            foreach($tables as $key=>$table){
                $step = 1;
                foreach($table->find('tr') as $tr){
                    $name = '';
                    $cost = '';
                    // p(count($tr->find('td')));
                    //if(!$end_cell= $cell[$table_number]){
                        $end_cell= $cell['standart'];
                    //}
                    if( count($tr->find('td')) == 2) continue;
                    if (count($tr->find('td')) == 4 && $step != 1) $step = 2;
                    foreach($tr->find('td') as $td){
                        if(in_array($td->plaintext,$text))$header=$td->plaintext;
                        //echo iconv('cp1251','utf-8',$td->innertext);
                        //$temp['cost'] =  trim(strip_tags(str_replace(' ', '', $td->innertext)));
                        if($td->innertext == '-'){
                            $step++;
                            continue;
                        }
                        if($step == 1){
                            //continue 2;
                        }else if ($step == 3){
                            $name =/*$header.' '.*/$td->plaintext;
                        }else if ($step >= 3 && $step <= $end_cell['stop_cell']){
                            $name .= ' '.$td->plaintext;
                        }else if ($step == $end_cell['cost_cell1']){
                            if (!empty($td->plaintext))
                            $cost[] = trim(strip_tags($td->plaintext));
                            //$cost = iconv('UTF-8','windows-1251',$cost);
                            //$cost = str_replace(chr(160), '', $cost);
                            //$cost = str_replace(array('&nbsp;', '&#160;'), array('', ''), $cost);
                            //$cost = preg_replace('/\s+/iu','', $cost);
//                        }else if ($step == $end_cell['cost_cell2']){
//                            if (!empty($td->plaintext))
//                                $cost[] = trim(strip_tags($td->plaintext));
//                            //$cost = iconv('UTF-8','windows-1251',$cost);
//                            //$cost = str_replace(chr(160), '', $cost);
//                            //$cost = str_replace(array('&nbsp;', '&#160;'), array('', ''), $cost);
//                            //$cost = preg_replace('/\s+/iu','', $cost);
//                        }else if ($step == $end_cell['cost_cell3'] && strripos($name,'Сетка')!==false){
//                            if (!empty($td->plaintext))
//                                $cost[] = trim(strip_tags($td->plaintext));
//                            //$cost = iconv('UTF-8','windows-1251',$cost);
//                            //$cost = str_replace(chr(160), '', $cost);
                            //$cost = str_replace(array('&nbsp;', '&#160;'), array('', ''), $cost);
                            //$cost = preg_replace('/\s+/iu','', $cost);
                        }

                        $step++;
                        $name = str_replace('  ',' ', $name);
                    }
                    $step = 3;
                   // foreach($cost as $value){
                        if (empty($cost)) continue ;
                   // }
                    //$name .= ' '.$td->innertext;
                    $name = str_replace('&ndash','-', $name);
                    $name = str_replace(array('&nbsp;', '&#160;'),' ', $name);
                    //$name = strip_tags($name);
                    //$name = html_entity_decode($name);
                    $name = trim($name,'&nbsp;');
                    $name = trim($name);
                    $name = str_replace(';','!', $name);
                    $name = str_replace('×','х', $name);
                    $result[]=array('name'=>$name,'cost'=>$cost,);
                }

//                    p($result);
//                  die();

                /*foreach($tr->find('td.pl_center div, td.pl_left') as $td){
                    //p($td->innertext);
                    //echo iconv('cp1251','utf-8',$td->innertext);
                    $temp['name'][] =  trim(str_replace('&nbsp;', ' ', $td->innertext));
                }
                if(!is_numeric($temp['cost'])){
                    //p($temp['cost']);
                    continue;
                }
                if(empty($temp)) continue;
                $temp['name'] = implode(' ',$temp['name']);
                if(empty($temp['name'])){
                    continue;
                }
                $temp['name'] = preg_replace('/\s+/i',' ', $temp['name']);

                //$temp['cost'] = implode(';',$temp['cost']);
                $result[] = $temp;*/
                $table_number++;
            }
            //p($result);
            $this->items = array_merge($this->items,$result);
        }
        $parse->clear();
        unset($parse);
        //die();
    }
    /*
    public function getUrl(){
        $cookie = tempnam ("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt($ch, CURLOPT_URL, $this->home_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 100 );
        $data = curl_exec($ch);
        curl_close($ch);
        $parse = str_get_html($data);
        $info_all = $parse->find('ul.msubmenu li');
        $href = array();
        foreach($info_all as $info_first){
            $info_second = $info_first->find('a',0);
            $href[] = 'http://www.constali.ru'.$info_second->href;
            //break;
        }
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        p($href);
        //die();
        return $href;
    }
*/
}