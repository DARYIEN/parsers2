<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParPsbasa extends CParMain{
    var $city_id;
    static $name_parser = array(
        'psbaza' => 'Первая Строительная База'
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
        $this->document_extended = '.xlsx';
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_url = 'http://psbaza.ru/d/169463/d/prays-list-pervaya-stroitelnaya-baza-20.01.xlsx';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->price_id = 7322622;
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

        foreach($this->document_list as $path){
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 6,
                        'ignored' => 5,
                        'char' => 'G'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 635),
                    'from' => array(
                        'numeric' => 16))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 5,
                        'ignored' => 6,
                        'char' => 'F'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 406),
                    'from' => array(
                        'numeric' => 17))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 5,
                        'ignored' => 6,
                        'char' => 'F'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 495),
                    'from' => array(
                        'numeric' => 478))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 5,
                        'ignored' => 6,
                        'char' => 'F'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 475),
                    'from' => array(
                        'numeric' => 410))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing(1);

            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 5,
                        'ignored' => 6,
                        'char' => 'F'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 544),
                    'from' => array(
                        'numeric' => 499))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing(1);

            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 5,
                        'ignored' => 6,
                        'char' => 'F'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 604),
                    'from' => array(
                        'numeric' => 548))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 3,
                        'ignored' => 6,
                        'char' => 'D'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 79),
                    'from' => array(
                        'numeric' => 17))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 3,
                        'ignored' => 6,
                        'char' => 'D'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 140),
                    'from' => array(
                        'numeric' => 81))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing(0,1);
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 3,
                        'ignored' => 6,
                        'char' => 'D'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 143))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();
        }
        //p($this->items);
        $this->save();
    }
    function documentParsing($multiple = false,$type = 0){
        switch($type){
            case 0:
                $name = '';
                $cost = array();
                foreach($this->sheet as $rows){
                    $rows = clear_array($rows);
                    //p($rows);
                    foreach($rows as $num_row => $row){
                        if(!empty($row)){
                            if(in_array($num_row, array($this->filter['horizontal']['to']['ignored']))){
                                continue;
                            }
                            $name[] = $row;
                        }
                    }
                    if(!empty($name)){
                        $cost = (int) array_pop($name);
                    }
                    if(!is_numeric($cost) || is_null($cost)){
                        $name = array();
                        $cost = '';
                        continue;
                    }
                    if($multiple && is_numeric($cost)){
                        $cost = $cost*1000;
                    }
                    if(!empty($name)&& !empty($cost)){
                        $this->items[] = array('name' => preg_replace('/;/i','!',preg_replace('/\s+/',' ',implode(' ',$name))), 'cost' => $cost);
                    }
                    $name = array();
                    $cost = '';
                }
            break;
            case 1:
                $name = '';
                $cost = '';
                $header = '';
                $clear = true;
                foreach($this->sheet as $rows){
                    $rows = clear_array($rows);
                    //if(!empty())
                    $cost = isset($rows[$this->filter['horizontal']['to']['numeric']]) ? $rows[$this->filter['horizontal']['to']['numeric']] : '';
                    if(!is_numeric($cost) || empty($cost)){
                        //$name = '';
                        if($clear)
                        {
                            $header = '';
                            $clear = false;
                        }
                        $cost = null;
                        $header .= isset($rows[$this->filter['horizontal']['from']['numeric']]) ? $rows[$this->filter['horizontal']['from']['numeric']] : '';
                        continue;
                    }else{
                        $clear=true;
                    }

                    foreach($rows as $num_row => $row){
                        if(!empty($row)){
                            if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                                continue;
                            }

                            $name .= ' '.$row;
                        }
                    }
                    $header = 'Лист алюминевый ';
                    if(!empty($header) && !empty($cost)){
                        $this->items[] = array('name' => preg_replace('/;/i','!',preg_replace('/\s+/i',' ', $header.' '.$name)), 'cost' => $cost);
                    }

                    $name = '';
                    $cost = '';
                }
                break;
        //p($this->items);
        }
       // die();

    }
}