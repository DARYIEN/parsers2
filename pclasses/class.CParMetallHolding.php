<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParMetallHolding extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $company_name;
    static $name_parser = array(
        'metholding' => 'МеталлХолдинг Санкт-Петерберг'
    );
    function start(){
        $this->getDocuments();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
        return $mail;
    }
    function __construct(){
        //print_r(self::$name_parser);
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        $this->document_url = $this->getUrl();
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->company_name = current(array_values(self::$name_parser));
        $this->price_type = 'web';
        $this->price_id = 8680183;
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
            $this->save();
            $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->document_url.')</h4>';
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
            if(!empty($this->to_save_new)){
                $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
            }
            //unlink($path);
        }
        //$this->save();
    }
    function getDocuments(){
        if(!empty($this->document_url)){
            $document_name = 'temp_'.md5(time()).'_'.$this->price_id.'_'.$this->document_extended;
            //echo $document_name;
            $this->getDocument($document_name);
        }
        return $this;
    }
    function documentParsing($path){
        $this->items = array();
        $parse = file_get_html($path);
        $table = $parse->find('table.tablepress tbody tr');
        if(!empty($table)){
            $result = array();
            foreach($table as $tr){
                $temp = array();
                foreach($tr->find('td') as $td){
                    $temp['name'][] = !empty($td->innertext) ? strip_tags($td->innertext) : '';
                }
                if(empty($temp)) continue;
                $temp['cost'] = array_pop($temp['name']);
                if(empty($temp['cost'])) continue;
                $temp['cost'] = str_replace(' ', '', $temp['cost']);
                $temp['name'] = implode(' ',$temp['name']);
                $temp['name'] = preg_replace('/\s+/iu',' ', $temp['name']);
                //$temp['cost'] = implode(';',$temp['cost']);
                $result[] = $temp;
            }
            $this->items = $result;
        }
        $parse->clear();
        unset($parse);
    }
    public function getUrl(){
        return 'metallholding-spb.ru/general-price/';
    }
}