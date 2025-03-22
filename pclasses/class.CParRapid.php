<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParCParRapid extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'rapid' => 'РАПИД'
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
        $this->price_type = 'web';
        $this->dual_cost = false;
        $this->document_urls = array(   'http://www.tk-rapid.ru/katalog-trub/',
                                    );
        $this->home_url = current($this->document_urls);
        $this->price_id = 8410389;
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
        ///p($this->document_list);
        foreach($this->document_list as $path){
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
        $parse = file_get_html($path);
        $headers = $parse->find('div.b-more');
        $tables = $parse->find('table');
        if(!empty($headers)){
            foreach($headers as $header){
                foreach($header->find('h2') as $content){
                    $text[]=trim(str_replace('Прайс-лист |', '', $content->innertext));

                }
            }
            //p($text);
        }

        $cell=array(
            'standart'=>array('stop_cell'=>6, 'cost_cell1'=>7,),
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

                    if (count($tr->find('td')) == 4 && $step != 1) $step = 2;
                    foreach($tr->find('td') as $key => $td){
                        //echo iconv('cp1251','utf-8',$td->innertext);
                        //$temp['cost'] =  trim(strip_tags(str_replace(' ', '', $td->innertext)));
                        //echo $key." ".$td->innertext."<br />";
                        if($key == 1){
                            //echo $name;
                            $name .= " ".$td->innertext;
                        }
                        if($step == 4){
                            $step++;
                            continue;
                        }

                        if($step == 1){
                            //$name = $td->innertext;
                        }else if ($step == 3){
                            $name .= /*$text[$key].' '.*/$td->innertext;
                        }else if ($step > 3 && $step <= $end_cell['stop_cell']){
                           // echo $name." ".$step."<br />";
                            $name .= ' '.$td->innertext;
                        }else if ($step == $end_cell['cost_cell1']){
                            if (!empty($td->innertext))
                                $cost = trim(strip_tags(str_replace('руб.', '', $td->innertext)));
                            //$cost = iconv('UTF-8','windows-1251',$cost);
                            //$cost = str_replace(chr(160), '', $cost);
                            //$cost = str_replace(array('&nbsp;', '&#160;'), array('', ''), $cost);
                            //$cost = preg_replace('/\s+/iu','', $cost);
                        }

                        $step++;

                    }
                    $step = 3;
                    // foreach($cost as $value){
                    if (empty($cost)) continue ;
                    // }
                    //$name .= ' '.$td->innertext;
                    $name = str_replace('&ndash','-', $name);
                    $name = strip_tags($name);
                    $name = html_entity_decode($name);
                    $name = trim($name,'&nbsp;');
                    $name = trim($name);
                    $name = str_replace(';','!', $name);
                    $name = preg_replace('/\s+/iu',' ', $name);
                    $result[]=array('name'=>$name,'cost'=>$cost,);
                }


                //  die();

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