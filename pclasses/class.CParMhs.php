<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParMhs extends CParMain{
    var $city_id;
    static $name_parser = array(
        'mhs' => 'Металл Холдинг Строй'
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
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_url = $this->getUrl();
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->price_id = 8180791;
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
            $this->n_sheets = 2;
            $this->filter = array('horizontal' => array('to' => array('numeric' => 4, 'char' => 'E'), 'from' => array('numeric' => 1, 'char' => 'B')), 'vertical' => array('to' => array('numeric' => null), 'from' => array('numeric' => 4)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = false;
            $this->startParse($path);
            $this->filter = array('horizontal' => array('to' => array('numeric' => 9, 'char' => 'J'), 'from' => array('numeric' => 6, 'char' => 'G')), 'vertical' => array('to' => array('numeric' => null), 'from' => array('numeric' => 4)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->startParse($path);

            /*$this->filter = array('horizontal' => array('to' => array('numeric' => 1, 'char' => 'B'), 'from' => array('numeric' => 0, 'char' => 'A')), 'vertical' => array('to' => array('numeric' => null), 'from' => array('numeric' => 18)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = false;
            $this->startParse($path);
            $this->filter = array('horizontal' => array('to' => array('numeric' => 4, 'char' => 'E'), 'from' => array('numeric' => 3, 'char' => 'D')), 'vertical' => array('to' => array('numeric' => null), 'from' => array('numeric' => 18)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->startParse($path);
            $this->filter = array('horizontal' => array('to' => array('numeric' => 7, 'char' => 'H'), 'from' => array('numeric' => 6, 'char' => 'G')), 'vertical' => array('to' => array('numeric' => null), 'from' => array('numeric' => 18)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->startParse($path);*/
        }
        $this->save();
    }
    function startParse($path){
        $this->documentLoad($path);
        for($i = 0; $i<=$this->n_sheets; $i++){
            unset($this->aSheet);
            $this->sheet = $this->objPHPExcel->getSheet($i)->toArray();
            $this->documentParsing();
        }
    }
    function documentParsing(){
        $name = '';
        $name_items = '';
        $type_item = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            if(current(array_keys($rows)) == $this->filter['horizontal']['from']['numeric']){
                if(in_array('Цена', $rows)){
                    $type_item = '';
                    //echo 'FELIX';
                    continue;
                }
                if(count($rows) == 1){
                    $name_items = current($rows);
                    continue;
                }
                //костылек
                /*if($this->nonHead && $name_items != 'Круг AISI 304'){
                    $name_items = '';
                }*/
                $type_item = current($rows);
            }
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($row, array('т', 'м')) || $num_row == $this->filter['horizontal']['from']['numeric']){
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['to']['numeric']){
                        $cost = $row;
                        if(!is_numeric($cost) || is_null($cost)){
                            continue 2;
                        }
                        continue;
                    }
                    $name .= ' '.$row;
                }
            }
            if(!is_array($this->sheet)){
                if(!empty($name) && !empty($cost) ){
                    $this->items[] = array('name' => preg_replace('/;/iu',',',preg_replace('/\s+/iu',' ',$name_items.' '.$type_item.' '.$name)), 'cost'=>$cost);
                }
            }else{
                if((!empty($type_item) || $this->head_non) && !empty($cost)){
                    $this->items[] = array('name' => preg_replace('/;/iu',',',preg_replace('/\s+/iu',' ',$name_items.' '.$type_item.' '.$name)), 'cost'=>$cost);
                }
            }
            $name = '';
            $cost = '';

        }
    }
    public function getUrl(){
        return 'http://m-h-s.ru/d/476777/d/prays1.xlsx';
    }
}