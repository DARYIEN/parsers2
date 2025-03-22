<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParThyssenKruppKrug extends CParThyssenKrupp{
    var $city_id;
    function __construct($path){
        $this->path = $path;

    }
    function processParsing(){
            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 13))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($this->path);
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
            $this->documentLoad($this->path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('G'),
                    'from' => column('F')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 13))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($this->path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('H')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 53),
                    'from' => array(
                        'numeric' => 13))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($this->path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();
            return $this->items;
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $coef = 1;
        $secondHeader=false;
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){

                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        if(trim($row)=='р/мп') $coef = 1;
                        if(trim($row)=='р/кг') $coef = 1000;
                        $cost = ((int) str_replace(' ','',preg_replace('~[^0-9]+~','',$row)))*$coef;
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        $secondHeader=false;
                        continue;
                    }
                    if($num_row == 3){
                       // continue;
                    }

                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if(count($rows) == 1 && $secondHeader==true){
                        $header .= ' '.$row;
                        continue;
                    }
                    if(count($rows) == 1 ){
                        $header = $row;
                        $secondHeader=true;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric'] ) $name =str_replace('ВГП, ЭСВ','',$header);
                    if($header=='Цена руб/кг')continue 2;
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