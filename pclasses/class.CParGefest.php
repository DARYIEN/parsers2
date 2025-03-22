<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParGefest extends CParMain{
    var $city_id;
    static $name_parser = array(
        'gefest' => 'Гефест'
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
        $this->document_url = 'http://gefest-tula.ru/assets/files/price.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 10000882;
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
            'horizontal' => array(
                'to' => array(
                    'numeric' => 3,
                    'char' => 'D'),
                'from' => array(
                    'numeric' => 0,
                    'char' => 'A')),
            'vertical' => array(
                'to' => array(
                    'numeric' => null),
                'from' => array(
                    'numeric' => 9))
        );

        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        foreach($this->document_list as $path){
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
        }
        $this->save();
    }
    function getXLS($xls){
        $objPHPExcel = PHPExcel_IOFactory::load($xls);
        $objPHPExcel->setActiveSheetIndex(0);
        $aSheet = $objPHPExcel->getActiveSheet();

        //этот массив будет содержать массивы содержащие в себе значения ячеек каждой строки
        $array = array();
        //получим итератор строки и пройдемся по нему циклом
        foreach($aSheet->getRowIterator() as $row){
            //получим итератор ячеек текущей строки
            $cellIterator = $row->getCellIterator();
            //пройдемся циклом по ячейкам строки
            //этот массив будет содержать значения каждой отдельной строки
            $item = array();
            foreach($cellIterator as $cell){
                //заносим значения ячеек одной строки в отдельный массив
                array_push($item, iconv('utf-8', 'cp1251', $cell->getCalculatedValue()));
            }
            //заносим массив со значениями ячеек отдельной строки в "общий массв строк"
            array_push($array, $item);
        }
        return $array;
    }
    function documentParsing(){
        $name = '';
        $cost = array();
        //p($this->sheet);
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric'],$this->filter['horizontal']['to']['numeric']-1))){
                        $cost[] = (int) str_replace(' ','',preg_replace('/От\./iu', '',$row));
                        if(empty($cost)){
                            $name = '';
                            $cost = array();
                            continue 2;
                        }
                        continue;
                    }
                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($cost)){
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu',',', preg_replace('/\s+/iu',' ', $name))), 'cost' => $cost);
            }
            $name = '';
            $cost = array();

        }
    }
}