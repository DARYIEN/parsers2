<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParUralMet extends CParMain{
    var $city_id;
    static $name_parser = array(
        'uralmet' => 'УралМетГрупп'
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
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_url = 'http://www.uralmetgrupp.ru/price.xlsx';
        //$this->coef = 1000;
        //$this->dual_cost = false;
        $this->price_id = 8852874;
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
                        'numeric' => 1,
                        'char' => 'B'),
                    'from' => array(
                        'numeric' => 0,
                        'char' => 'A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 4,
                        'char' => 'E'),
                    'from' => array(
                        'numeric' => 3,
                        'char' => 'D')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 7,
                        'char' => 'H'),
                    'from' => array(
                        'numeric' => 6,
                        'char' => 'G')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 11,
                        'char' => 'L'),
                    'from' => array(
                        'numeric' => 8,
                        'char' => 'I')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();
        }
        $this->save();
    }
    function documentParsing(){
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

            if(!empty($header) && !empty($cost)){
                $this->items[] = array('name' => preg_replace('/;/iu','!',preg_replace('/\s+/iu',' ', $header.' '.$name)), 'cost' => $cost);
            }

            $name = '';
            $cost = '';

           /*// p($rows);

            if(current(array_keys($rows)) == $this->filter['horizontal']['from']['numeric']){
                if(count($rows) == 1){
                    $name_items = current($rows);
                    //p($name_items);
                    continue;
                }

                $type_item = current($rows);
            }
        //  p($type_item.'<br>');



            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = $row;
                        if(!is_numeric($cost) || empty($cost)){
                            //$name = '';
                            $cost = null;
                            //$header = $type_item;
                            continue 2;
                        }
                        continue;
                    }
                    $name .= ' '.$row;
                }
            }
            if(!empty($type_item) && !empty($cost)){
                $this->items[] = array('name' => preg_replace('/;/iu',',',preg_replace('/\s+/iu',' ', $type_item.' '.$name_items.' '.$name)), 'cost' => $cost);
            }
            $name = '';
            $cost = '';
            $type_item = '';
            */
        }
    }
}