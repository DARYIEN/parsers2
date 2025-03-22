<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParStalMet extends CParMain{
    var $city_id;
    static $name_parser = array(
        'stalmet' => 'СтальМет'
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
        $this->document_url = 'https://www.stalexport61.ru/images/prices/price.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 10703141;
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
                    'to' => column('I'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 1))
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
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = (int) str_replace(' ','',$row);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if(empty($cost)) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']-2))){
                        $cost = (int) str_replace(' ','',$row);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if(empty($cost)) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if($num_row == 5 || $num_row == $this->filter['horizontal']['to']['numeric']-1){
                        continue;
                    }

                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if($num_row  == 1 &&!empty($row)){
                        $header = $row;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 ) $name =str_replace('ВГП, ЭСВ','',$header);
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', $name))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);

        }
    }
}
