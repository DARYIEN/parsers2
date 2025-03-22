<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParMetalobazaUral extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'metalobazaural' => 'Металлобаза Урал -Полевской'
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
        $this->document_urls = /*array(   'http://metallobaza-ural.ru/product_list',
                                    )*/$this->getUrl();
        $this->home_url = current($this->document_urls);
        $this->price_id = 13955400;
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
        $tables = $parse->find('.b-product-line__details-panel');
        if(!empty($headers)){
            foreach($headers as $header){
                foreach($header->find('em strong.section') as $content){
                    $text[]=trim(str_replace('Прайс-лист |', '', $content->plaintext));

                }
            }
            //p($text);
        }
        if(!empty($tables)){
            $result = array();
            $table_number  = 1;
            foreach($tables as $key=>$table){
                $name = $table->find('.b-product-line__product-name span',0)->plaintext;
                $cost = trim(str_replace(array(' ','руб.',',','р.','От','от','&#160;','&nbsp;','/т'),'',$table->find('.b-product-line__price',0)->plaintext));
                $cost = iconv('UTF-8','windows-1251',$cost);
                $cost = str_replace(chr(160), '', $cost);
                $cost = str_replace(array('&nbsp;', '&#160;'), array('', ''), $cost);
                $cost = preg_replace('/\s+/iu','', $cost);
                $name = preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($name)))));
                $result[]=array('name'=>$name,'cost'=>array($cost));
            }
            //p($result);
            //die();
            $this->items = array_merge($this->items,$result);
        }
        $parse->clear();
        unset($parse);
        //die();
    }

    public function getUrl(){
        $parse = file_get_html('http://metallobaza-ural.ru/product_list');
        $info_all = $parse->find('div.b-pager a.b-pager__link ');
        $hrefs = array();
        $pager = array();
        foreach($info_all as $info_first){
            $temp = explode('/product_list/page_',$info_first->href);
            $pager[] = end($temp);

        }
        $hrefs = range(min($pager),max($pager));
        foreach($hrefs as $key => $href){
            $hrefs[$key] = 'http://metallobaza-ural.ru/product_list/page_'.$href;
        }
        $hrefs[] = 'http://metallobaza-ural.ru/product_list/';
        //p($hrefs);
        //die();
        return $hrefs;
    }

}