<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParOtmag extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'Otmag' => 'Otmag'
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
        $this->dual_cost = true;
        $this->author = 'Михаил';
        $this->document_urls = array(   'http://www.otmag.ru/pages/price',
                                    );
        $this->home_url = current($this->document_urls);
        $this->price_id = 1;
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
        $headers = $parse->find('div');
        $tables = $parse->find('#contentD table');
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
                   'standart'=>array('stop_cell'=>2, 'cost_cell1'=>3, 'cost_cell2'=>7, 'cost_cell3'=>4,),
               );

        if(!empty($tables)){
            $result = array();
            $table_number  = 1;
            foreach($tables as $key=>$table){
                $step = 1;
                foreach($table->find('tr') as $tr){
                    $name = '';
                    $costs = array();
                    $end_cell= $cell['standart'];
                    foreach($tr->find('td') as $key =>  $td){
                        if(count($tr->find('td')) > 1 && $key == count($tr->find('td')) - 1) {
                            $cost = str_replace(array(' ', 'руб.', 'р.', '&#160;', '&nbsp;', chr(160), 'От', 'от'), '', $td->plaintext);
                            $cost = str_replace(',','.',$cost);
                            if(is_numeric($cost)){
                                $cost = str_replace('.',',',$cost);
                                $costs[] = $cost;
                            }

                            continue;
                        }
                        $name .= ' '.$td->plaintext;
                    }
                    if (empty($costs)) continue ;
                    $name = str_replace('&ndash','-', $name);
                    $name = str_replace(array('&nbsp;', '&#160;'),' ', $name);
                    //$name = strip_tags($name);
                    //$name = html_entity_decode($name);
                    $name = trim($name,'&nbsp;');
                    $name = trim($name);
                    $name = str_replace(';','!', $name);
                    $name = str_replace('×','х', $name);
                    $name = preg_replace('/\s+/iu',' ',$name);
                    $name = preg_replace('/[\(\)\[\]\'"]/iu',' ', $name);
                    $result[]=array('name'=>$name,'cost'=>$costs,);
                }
                $table_number++;
            }
            //p($result);
            $this->items = array_merge($this->items,$result);
        }
        $parse->clear();
        unset($parse);
        //die();
    }

}