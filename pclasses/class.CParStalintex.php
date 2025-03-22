<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParStalintex extends CParMain{
    var $city_id;
    static $name_parser = array(
        'stalintex' => 'СтальИнтекс'
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
        $this->price_id = 7492089;
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
            $this->filter = array('horizontal' => array('to' => array('numeric' => 3, 'char' => 'D'), 'from' => array('numeric' => 0, 'char' => 'A')), 'vertical' => array('to' => array('numeric' => 85), 'from' => array('numeric' => 20)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter = array('fake' => 'Балка ГОСТ 8239-89, ','horizontal' => array('to' => array('numeric' => 3, 'char' => 'D'), 'from' => array('numeric' => 0, 'char' => 'A')), 'vertical' => array('to' => array('numeric' => 180), 'from' => array('numeric' => 86)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter = array('fake' => 'Лист оцинкованный ХШ,Г, ','horizontal' => array('to' => array('numeric' => 3, 'char' => 'D'), 'from' => array('numeric' => 0, 'char' => 'A')), 'vertical' => array('to' => array('numeric' => 234), 'from' => array('numeric' => 183)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter = array('horizontal' => array('to' => array('numeric' => 3, 'char' => 'D'), 'from' => array('numeric' => 0, 'char' => 'A')), 'vertical' => array('to' => array('numeric' => null), 'from' => array('numeric' => 235)));
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();
        }
        $this->save();
    }
    function startParse($path){
        $this->documentLoad($path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();
    }
    function documentParsing(){
        $name = '';
        $name_items = '';
        $type_item = '';
        if(isset($this->filter['fake'])){
            $type_item = $this->filter['fake'];
        }
        $cost = '';
        $flag_of_non_continued_type_item = false;
        $i = 0;
        foreach($this->sheet as $col => $rows){
            $rows = clear_array($rows);
            if(current(array_keys($rows)) == $this->filter['horizontal']['from']['numeric'] && preg_replace('/\s+/iu','', $rows[0]) != ''){
                if($flag_of_non_continued_type_item){
                    $type_item = current($rows);
                }
                if((!empty($this->sheet[$col + 2][$this->filter['horizontal']['from']['numeric']]) && preg_replace('/\s+/iu','', $this->sheet[$col + 2][$this->filter['horizontal']['from']['numeric']]) != '')){
                    $flag_of_non_continued_type_item = false;
                    $type_item .= ' '.$this->sheet[$col + 2][$this->filter['horizontal']['from']['numeric']];
                }
                if((!empty($this->sheet[$col + 1][$this->filter['horizontal']['from']['numeric']]) && preg_replace('/\s+/iu','', $this->sheet[$col + 1][$this->filter['horizontal']['from']['numeric']]) != '' )){
                    $flag_of_non_continued_type_item = false;
                    $type_item .= ' '.$this->sheet[$col + 1][$this->filter['horizontal']['from']['numeric']];
                }else{
                    $flag_of_non_continued_type_item = true;
                }
            }
            //&& !in_array($this->sheet[$col + 1][$this->filter['horizontal']['from']['numeric']], array('Балка ГОСТ 8239-89,'))
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['from']['numeric']))){
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = $row;
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
            if((!empty($type_item)) && !empty($cost)){
                $this->items[] = array('name' => preg_replace('/;/iu',',',preg_replace('/\s+/iu',' ', $type_item.' '.$name)), 'cost' => $cost);
            }
            $name = '';
            $cost = '';
        }
        //echo $i;
    }
    public function getUrl(){
        $parse = file_get_html('http://www.stalinteks.ru/download_prays-list');
        $link = $parse->find('td.column2 p a', 0);
        return 'http://www.stalinteks.ru/'.$link->href;
    }
}
class CParGlavSnabStalintex extends CParStalintex{
    static $name_parser = array(
        'glavsnabstalintex' => 'ГлавснабСтальинтекс'
    );
    function __construct(){
        parent::__construct();
        $this->price_id = 13386960;
        $this->coef = 1.01;

        //$this->document_name= 'MTKSPB'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}