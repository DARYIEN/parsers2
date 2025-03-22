<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParRyazanskiyTrubniyZavod extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'rtz' => ''
    );
    public $cityName = '';
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
        $this->company_name = 'Рязанский трубный завод '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = false;
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
        }
        $this->save();
        $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
        $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
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
        $array = array();
        $parse = file_get_html($path);
        $item  = array();
        $table = $parse->find('table tr');
        if(!empty($table)){
            $result = array();
            foreach($table as $index => $tr){
                foreach($tr->find('td') as  $td){
                    $str =trim(/*strip_tags(*/$td->plaintext)/*)*/;
                    $array[$index][] = $str;
                }
            }
            foreach($array as $priceRow){
                if(end($priceRow)==$this->cityName){
                    $item['name'] = iconv('UTF-8','windows-1251',preg_replace('/;/u','!',preg_replace('/\s+/u',' ',implode(' ', array_slice($priceRow,0,3)))));
                    $item['cost'] = str_replace(array(' ','руб.','р.','&#160;','&nbsp;', chr(160),'по','от'),'',$priceRow[4]);
                    $result[]= $item;
                }
                unset($item);
            }
            $this->items = array_merge($this->items,$result);
            unset($result);
        }
        $parse->clear();
        unset($parse);
    }
    public function getUrl(){
        $numbers=array();
        $hrefs=array();
        $url = 'http://www.td-rtz.ru/pricelist/';
        foreach(file_get_html($url)->find('body .newspagenav a') as $link){
            $number = $link->plaintext;
            if(is_numeric($number)) $numbers[] = $number;
        }
        $countPages=end($numbers);
        for($i=1;$i<=$countPages;$i++){
            $hrefs[]='http://www.td-rtz.ru/pricelist/?PAGEN_1='.$i;
        }
        return $hrefs;
    }
}
