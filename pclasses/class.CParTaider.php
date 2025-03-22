<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParTaider extends CParMain{
    var $city_id;
    static $name_parser = array(
        'taider' => 'Тайдер'
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
        $this->document_extended = '.xls';
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_url = 'http://taider.ru/Prais/Prais.xls';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->price_id = 7560195;
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
            $this->filter = array('horizontal' => array('to' => array('numeric' => 2, 'char' => 'C'), 'from' => array('numeric' => 0, 'char' => 'A')), 'vertical' => array('to' => array('numeric' => null), 'from' => array('numeric' => 11)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = false;
            $this->startParse($path);
        }
        $this->save();
    }
    function startParse($path){
        $this->documentLoad($path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();
    }
    function documentParsing(){
        $name = '';
        $type_item = '';
        $cost = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            if(current(array_keys($rows)) == $this->filter['horizontal']['from']['numeric']){
                $type_item = current($rows);
            }
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($row, array('метр')) || $num_row == $this->filter['horizontal']['from']['numeric']){
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['to']['numeric'] - 1){
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = $row;
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            $type_item = '';
                            continue 2;
                        }
                        continue;
                    }
                    $name .= ' '.$row;
                }
            }
            if(!empty($type_item) && !empty($cost)){
                $this->items[] = array('name' => preg_replace('/;/iu',',',preg_replace('/\s+/iu',' ', $type_item.' '.$name)), 'cost' => $cost);
            }
            $name = '';
            $cost = '';
            $type_item = '';

        }
    }
    public function getUrl(){
        $parse = file_get_html('http://www.stalinteks.ru/download_prays-list');
        $link = $parse->find('td.column2 p a', 0);
        return 'http://www.stalinteks.ru/'.$link->href;
    }
}