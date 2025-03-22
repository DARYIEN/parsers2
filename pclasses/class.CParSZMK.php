<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParSZMK extends CParMain{
    var $city_id;
    static $name_parser = array(
        'szmk' => 'СЗМК'
    );
    public $items = array();
    function start(){
        $this->getDocuments()->processParsing();
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
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_urls['truba'] = 'http://www.expertmetall.ru/truby-price.xlsx';
        $this->document_urls['sort'] = 'http://www.expertmetall.ru/general-price.xlsx';
        $this->document_urls['ozink'] = 'http://www.expertmetall.ru/ozink.xlsx';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->price_id = 10621251;
    }
    function formDirsArray(){
        $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
        return $this;
    }
    function processParsing(){
        $this->header = '';
        foreach($this->document_list as $key => $path){
            if($key == 'truba'){
                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('B'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 106),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('D')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('J')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

            }else if($key == 'sort'){
                    $this->filter =  array(
                        'horizontal' => array(
                            'to' => column('B'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => 60),
                            'from' => array(
                                'numeric' => 13))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'horizontal' => array(
                            'to' => column('E'),
                            'from' => column('D')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 13))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'horizontal' => array(
                            'to' => column('H'),
                            'from' => column('G')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 13))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'horizontal' => array(
                            'to' => column('K'),
                            'from' => column('J')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 13))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

            }else if($key == 'ozink'){
                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('B'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 60),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('D')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('J')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

            }
        }
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = (int) str_replace(' ','',preg_replace('~[^0-9]+~','',$row));
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        continue;
                    }
                    if($num_row == 3){
                       // continue;
                    }
                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if(count($rows) == 1 && $num_row == $this->filter['horizontal']['from']['numeric']){
                        $header = $row;
                        $this->header = $header;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']){
                        if(empty($header)) $header = $this->header;
                        $name =str_replace('(продолжение)','',$header);
                    }
                    if(empty($name) && $num_row == $this->filter['horizontal']['from']['numeric']+1) $name =str_replace('(продолжение)','',$header);
                    if(count($rows) == 1 && $num_row == $this->filter['horizontal']['from']['numeric']+1){ // охуенная фишка
                        $arr = array_pop($this->items);
                        $name = $arr['name'];
                        $cost = $arr['cost'];
                    }
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($cost)){
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', $name))), 'cost' => $cost);
            }
            $name = '';
            $cost = '';

        }
    }
}