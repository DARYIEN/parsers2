<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParDemir extends CParMain{
    var $city_id;
    static $name_parser = array(
        'demir' => 'Демир'
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
        $this->document_url = 'http://pkf-demir.ru/wp-content/uploads/PKF_DEMIR_price_list_pkf-demir.ru.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 8095072;
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
            //лист0
            $this->filter =  array(
               // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 10))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('G'),
                    'from' => column('E')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 10))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('J'),
                    'from' => column('H')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 10))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();
            //лист0 конец

            //лист1
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('F'),
                    'from' => column('D')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('G')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('L'),
                    'from' => column('J')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('O'),
                    'from' => column('M')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();
            //лист1 конец

            //лист2
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('F'),
                    'from' => column('D')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('G')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
            //лист2 конец

            //лист3
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('F'),
                    'from' => column('D')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('G')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('L'),
                    'from' => column('J')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('P'),
                    'from' => column('M')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();
            //лист3 конец

            //лист4
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('F'),
                    'from' => column('D')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();
            //лист4 конец

            //лист5
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('F'),
                    'from' => column('D')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
            $this->documentParsing();
            $this->filter =  array(
                // 'dermolist' => 1,
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('G')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 9))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
            $this->documentParsing();
            //лист4 конец
        }
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = Round( str_replace(' ','',$row));
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost==0) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']-/*$this->filter['dermolist']*/1))){
                        $cost = (int) str_replace(' ','',$row);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost==0) continue;
                        $costs[] = $cost;
                        continue;
                    }

                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if(in_array($row, array('Марка стали'))){
                        continue 2;
                    }
                    if(count($rows) == 1 ){
                        $header = $row;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric'] ) $name =str_replace('ВГП, ЭСВ','',$header);

                    if($num_row == $this->filter['horizontal']['from']['numeric'] && $row == 'Диаметр мм'){
                         continue;
                    }

                    $name .= ' '.trim($row);
                }
            }
            if(!empty($name)&& !empty($costs)){
                $name = str_replace(array('Æ','º'),'',$name);
                $name = str_replace('  ',' ',$name);
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', $name))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);

        }
        //p($this->items);
    }
}