<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParSevZapMetallTrubaNerj extends CParSevZapMetall{
    var $city_id;
    function __construct($path){
        $this->path = $path;

    }
    function processParsing(){
            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 8))
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
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = Round( str_replace(' ','',$row))* 1000;
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        continue;
                    }
                    if($num_row == 3||$num_row == 2){
                        continue;
                    }

                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }

                    if(count($rows) == 1 ){
                        $header = $row;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric'] ) $name =str_replace('ВГП, ЭСВ','',$header);

                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($cost)){
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu',',', preg_replace('/\s+/iu',' ', $name))), 'cost' => $cost);
            }
            $name = '';
            $cost = '';

        }
    }
}