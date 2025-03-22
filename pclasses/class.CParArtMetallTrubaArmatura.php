<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParArtMetallTrubaArmatura extends CParArtMetall{
    var $city_id;
    function __construct($path){
        $this->path = $path;

    }
    function processParsing(){
            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 1))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($this->path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('G')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 1))
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
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = (int) str_replace(' ','',preg_replace('~[^0-9]+~','',str_replace('/17500','',$row)));
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        $costs[]=$cost;
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']-1))){
                        $cost = (int) str_replace(' ','',preg_replace('~[^0-9]+~','',str_replace('/17500','',preg_replace('/"/iu','',$name))));
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if(empty($cost)) continue;
                        //$costs[]=$cost;
                        continue;
                    }

                    if($num_row == 3){
                       // continue;
                    }
                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if(count($rows) == 1){
                        $header = $row;
                        continue;
                    }else if(count($rows) == 0){
                        $header = '';
                    }
                    if($row == 'Балка 30 ш2 3сп'){
                        $header = '';
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric'] ) $name =str_replace('Балка, ст.3сп','',$header);

                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu',',', preg_replace('/\s+/iu',' ', $name))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);

        }
    }
}