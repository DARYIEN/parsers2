<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParMetagor extends CParMain{
    var $city_id;
    static $name_parser = array(
        'metagor' => 'Метагор'
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
        $this->document_url = 'http://www.metagor.ru/docs/price-metagor.xls';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->price_id = 9370236;
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
                        'numeric' => 3,
                        'char' => 'D'),
                    'from' => array(
                        'numeric' => 1,
                        'char' => 'B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 1486),
                    'from' => array(
                        'numeric' => 8))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 3,
                        'cost' => 2,
                        'char' => 'D'),
                    'from' => array(
                        'numeric' => 1,
                        'char' => 'B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 1674   ),
                    'from' => array(
                        'numeric' => 1486))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing(2);
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 3,
                        'char' => 'D'),
                    'from' => array(
                        'numeric' => 1,
                        'char' => 'B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 1723),
                    'from' => array(
                        'numeric' => 1675))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing(3);
            $this->filter =  array(
                'horizontal' => array(
                    'to' => array(
                        'numeric' => 4,
                        'cost' => 3,
                        'char' => 'E'),
                    'from' => array(
                        'numeric' => 1,
                        'char' => 'B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 2701),
                    'from' => array(
                        'numeric' => 1741))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing(2);
            //p($this->sheet);
        }
        $this->save();
    }
    function documentParsing($type = 1,$multiplicate = false){
        switch($type){
            case 1:
                $name = '';
                $cost = '';
                foreach($this->sheet as $rows){
                    $rows = clear_array($rows);
                    foreach($rows as $num_row => $row){
                        if(!empty($row)){
                            if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                                $cost = (int) str_replace(' ','',preg_replace('/От\./iu', '',$row));
                                if(!is_numeric($cost) || is_null($cost)){
                                    $name = '';
                                    $cost = '';
                                    continue 2;
                                }
                                continue;
                            }

                            $name .= ' '.$row;
                        }
                    }
                    if(!empty($name)&& !empty($cost)){
                        if($multiplicate){
                            $cost = $cost*1000;
                        }
                        $this->items[] = array('name' => str_replace(array(', имп.','с ост.', 'Ø'),'',preg_replace('/\s+/iu',' ',preg_replace('/\?/iu','', preg_replace('/;/iu',',',  $name)))), 'cost' => $cost);
                    }
                    $name = '';
                    $cost = '';

                }
                break;
            case 2:
                $name = '';
                $cost = '';
                $header = '';
                $clear = true;
                foreach($this->sheet as $rows){
                    $rows = clear_array($rows);
                    //if(!empty())
                    $cost = isset($rows[$this->filter['horizontal']['to']['cost']]) ? $rows[$this->filter['horizontal']['to']['cost']] : '';
                    if(empty($cost)){
                        //$name = '';
                        if($clear)
                        {
                            $header = '';
                            $clear = false;
                        }
                        $cost = null;
                        $header = isset($rows[$this->filter['horizontal']['from']['numeric']]) ? $rows[$this->filter['horizontal']['from']['numeric']] : '';
                        continue;
                    }else{
                        $clear=true;
                    }

                    if($rows[$this->filter['horizontal']['to']['numeric']] == 'кг'){
                        $multiplicate = true;
                    }else{
                        $multiplicate = false;
                    }
                    foreach($rows as $num_row => $row){
                        if(!empty($row)){
                            if(in_array($num_row, array($this->filter['horizontal']['to']['cost']))){
                                continue;
                            }

                            $name .= ' '.$row;
                        }
                    }

                    if(!empty($header) && !empty($cost) && is_numeric($cost)){
                        if($multiplicate){
                            $cost = $cost*1000;
                        }
                        $this->items[] = array('name' => preg_replace('/;/iu','!',preg_replace('/\s+/iu',' ', $header.' '.$name)), 'cost' => $cost);
                    }

                    $name = '';
                    $cost = '';
                }
                break;
            case 3:
                $name = '';
                $cost = '';
                $header = '';
                $clear = true;
                foreach($this->sheet as $rows){
                    $rows = clear_array($rows);
                    //if(!empty())
                    $cost = isset($rows[$this->filter['horizontal']['to']['numeric']]) ? $rows[$this->filter['horizontal']['to']['numeric']] : '';
                    if(empty($cost)){
                        //$name = '';
                        if($clear)
                        {
                            $header = '';
                            $clear = false;
                        }
                        $cost = null;
                        $header = isset($rows[$this->filter['horizontal']['from']['numeric']]) ? $rows[$this->filter['horizontal']['from']['numeric']] : '';
                        continue;
                    }else{
                        $clear=true;
                    }

                    foreach($rows as $num_row => $row){
                        if(!empty($row)){
                            if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                                continue;
                            }
                            if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']-1))){
                                continue;
                            }

                            $name .= ' '.$row;
                        }
                    }

                    if(!empty($header) && !empty($cost) && is_numeric($cost)){
                        if($multiplicate){
                            $cost = $cost*1000;
                        }
                        $this->items[] = array('name' => preg_replace('/;/iu','!',preg_replace('/\s+/iu',' ', $header.' '.$name)), 'cost' => $cost);
                    }

                    $name = '';
                    $cost = '';
                }
                break;

        }
    }
}