<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParMetSouz extends CParMain{
    var $city_id;
    static $name_parser = array(
        'msouz' => 'Метсоюз'
    );
    function start(){
        $this->getDocument()->processParsing();
        $mail[$this->city_id] = '<br /><h4>'.current(array_values(self::$name_parser)).'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->document_url.')</h4>';
        $mail[$this->city_id] .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $mail[$this->city_id] .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
        return $mail;
    }
    function __construct(){
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }
        $this->formDirsArray()->createDirs();
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_url = 'http://www.metunion.ru/price/print/';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->price_type = 'web';
        $this->price_id = 8845776;
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
        //$this->save();
    }
    function documentParsing($path){
        $this->items = array();
        $parse = file_get_html($path);
        $table = $parse->find('table.goods tbody tr');
        if(!empty($table)){
            $result = array();
            foreach($table as $tr){
                $temp = array();
                /*foreach( as $td){
                    $temp['name'][] = $td->innertext;
                }*/
                //echo iconv('cp1251', 'utf-8', $tr->find('td[!colspan]',0)->innertext);

                $temp['name'] = !empty($tr->find('td[style="text-align:left;"]',0)->innertext) ? $tr->find('td[style="text-align:left;"]',0)->innertext : '';
                $temp['cost'] = !empty($tr->find('td[width="15%"]',0)->innertext) ? $tr->find('td[width="15%"]',0)->innertext : 0;

                if(empty($temp['name'])) continue;
                //$temp['name'] = implode(' ',$temp['name']);
                $temp['name'] = preg_replace('/\s+/iu',' ', $temp['name']);
                $temp['name'] = str_replace(';',' ', $temp['name']);
                //$temp['cost'] = implode(';',$temp['cost']);
                $result[] = $temp;
            }

            $this->items = $result;
            //$this->company_name = $parse->find('#page_title h1', 0)->innertext;
            //$this->document_name = rus2translit(preg_replace('/[^a-z�-��0-9]+/iu','',$this->company_name )).'_'.time().'.csv';
        }
        $parse->clear();
        unset($parse);
    }
}