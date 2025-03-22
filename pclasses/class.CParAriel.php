<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParArielMet extends CParMain{
    var $city_id;
    static $name_parser = array(
        'arielmet' => 'Ариель Металл'
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
        $this->document_extended = '.csv';
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_url = 'http://www.arielmetall.ru/mtprice.csv';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->iconv = false;
        $this->price_id = 8438003;
    }
    function formDirsArray(){
        $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
        return $this;
    }
    function processParsing(){
        $this->filter =  array(
            'propusk' => $this->num("KJ"),
            'horizontal' => array(
                'to' => array(
                    'numeric' => 7,
                    'cost' => 1,
                    'char' => 'H'),
                'from' => array(
                    'numeric' => 1,
                    'char' => 'B')),
            'vertical' => array(
                'to' => array(
                    'numeric' => null),
                'from' => array(
                    'numeric' => 9))
        );
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        foreach($this->document_list as $path){
            //$this->documentLoad($path);
            //PHPExcel_Settings::setLocale('ru');
            $csv = new CSV($path);
            $this->sheet = $csv->getCSV();
            //p($this->sheet);
            $this->documentParsing();
        }
        $this->save();
    }
    function documentParsing(){
        $name = '';
        $cost = array();
        foreach($this->sheet as $num => $rows){
            $rows = clear_array($rows);
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, $this->filter["propusk"])) continue;
                    if(in_array($num_row, array($this->filter['horizontal']['to']['cost']))){
                        $cost = str_replace(chr(160),'', $row);
                        //p($cost);
                        if(empty($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['cost']-1,$this->filter['horizontal']['to']['cost']-2,$this->filter['horizontal']['to']['cost']+1))) continue;
                    $name .= ' '.$row;

                }
            }
            if(!empty($name)&& !empty($cost)){
                $this->items[] = array('name' => preg_replace('/;/i',',',preg_replace('/руб\./i',' ',preg_replace('/\s+/i',' ',  $name))), 'cost' => $cost);
            }
            $name = '';
            $cost = array();
        }
       // p($this->items);
        //die();
    }
}