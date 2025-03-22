<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

abstract class CParMetallotorg extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $company_name;
    static $name_parser = array(
        'Metallotorg' => ''
    );
    function start(){
        $this->getDocuments();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
        return $mail;
    }
    function __construct(){
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }
        $this->company_name = 'Металлоторг '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.time().'.csv';
        $this->price_type = 'web';
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
            $this->save();
            $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->document_urls[$this->price_id].')</h4>';
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
            if(!empty($this->to_save_new)){
                $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
            }
            //unlink($path);
        }
        //$this->save();
    }
    function getDocuments(){
        if(!empty($this->document_urls)){
            foreach($this->document_urls as $price_id => $url){
                $this->document_url = $url;
                $this->price_id = $price_id;
                $document_name = 'temp_'.md5(time()).'_'.$this->price_id.'_'.$this->document_extended;
                //echo $document_name;
                $this->getDocument($document_name);
            }
        }
        return $this;
    }
    function documentParsing($path){
        $this->items = array();
        $parse = file_get_html($path);
        $table = $parse->find('table #TheBody tr');
        if(!empty($table)){
            $result = array();
            foreach($table as $tr){
                $_temps = array();
                $temp = array();
                foreach($tr->find('td') as $item){
                    $_temps[] = !empty($item->innertext) ? strip_tags($item->innertext) : '';
                }
                $_temps = clear_array($_temps);
                foreach($_temps as $_temp){
                    if(preg_match('/^[0-9]{4,}+$/', $_temp)){
                        $temp['cost'][] = $_temp;
                    }elseif(preg_match('/(^([0-9]+[.]+[0-9]+))$/',$_temp)){
                        continue;
                    }else{
                        $temp['name'][] = $_temp;
                    }
                }
                if(empty($temp['name']) || empty($temp['cost'])) continue;
                $temp['cost'] = array_splice($temp['cost'], 0);
                $temp['name'] = implode(' ',$temp['name']);
                $temp['name'] = iconv('cp1251', 'utf-8', $temp['name']);
                $temp['name'] = preg_replace('/\s+/iu',' ', $temp['name']);
                $temp['name'] = str_replace(';',' ', $temp['name']);
                $temp['name'] = str_replace(array('&nbsp','Купить'),'', $temp['name']);
                //$temp['cost'] = implode(';',$temp['cost']);
                $result[] = $temp;
            }

            $this->items = $result;
            $match = array();
            preg_match('/(?<=_)[0-9]+(?=_)/', $path, $match);
            $this->price_id = current($match);
            //$this->company_name = $parse->find('#page_title h1', 0)->innertext;
            //$this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.time().'.csv';
        }
        $parse->clear();
        unset($parse);
    }
}
