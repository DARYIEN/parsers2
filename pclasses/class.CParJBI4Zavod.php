<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParJBI4Zavod extends CParMain{
    var $city_id;
    static $name_parser = array(
        'jbi4zavod' => 'Завод ЖБИ 4'
    );
    public $items = array();
    function start(){
        $this->getDocuments()->processParsing();
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
        $this->document_urls['main'] = 'http://gbi4.ru/price.xls';
        //$this->document_urls['krepezh'] = 'http://www.zitar.ru/files/price/64/%CA%F0%E5%EF%E5%E6%ED%E0%FF%20%F2%E5%F5%ED%E8%EA%E0.XLS';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 9555559;
        $this->author = 'Михаил';
    }
    function formDirsArray(){
        $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
        return $this;
    }
    function processParsing(){
        foreach($this->document_list as $key => $path){
            if($key == 'main'){
                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 111),
                        'from' => array(
                            'numeric' => 8))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 149),
                        'from' => array(
                            'numeric' => 113))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 182),
                        'from' => array(
                            'numeric' => 152))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('F'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 187),
                        'from' => array(
                            'numeric' => 183))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 216),
                        'from' => array(
                            'numeric' => 189))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('FG'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 230),
                        'from' => array(
                            'numeric' => 224))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 238),
                        'from' => array(
                            'numeric' => 231))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('GI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 54),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('FI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 74),
                        'from' => array(
                            'numeric' => 57))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('FI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 93),
                        'from' => array(
                            'numeric' => 91))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('EI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('F'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 113),
                        'from' => array(
                            'numeric' => 100))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('GI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 144),
                        'from' => array(
                            'numeric' => 118))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('FI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 158),
                        'from' => array(
                            'numeric' => 153))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('GHI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('J'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 222),
                        'from' => array(
                            'numeric' => 170))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => $this->num('C'),
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 41),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('H'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 86),
                        'from' => array(
                            'numeric' => 43))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 122),
                        'from' => array(
                            'numeric' => 97))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('H'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 141),
                        'from' => array(
                            'numeric' => 132))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('IJ'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('L'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 172),
                        'from' => array(
                            'numeric' => 154))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 188))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 63),
                        'from' => array(
                            'numeric' => 6))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 107),
                        'from' => array(
                            'numeric' => 65))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 219),
                        'from' => array(
                            'numeric' => 109))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 21),
                        'from' => array(
                            'numeric' => 6))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 65),
                        'from' => array(
                            'numeric' => 25))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 100),
                        'from' => array(
                            'numeric' => 67))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 148),
                        'from' => array(
                            'numeric' => 103))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 166),
                        'from' => array(
                            'numeric' => 150))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 66),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 86),
                        'from' => array(
                            'numeric' => 68))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Труба азбестоцементная напорная',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 103),
                        'from' => array(
                            'numeric' => 97))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('FG'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Муфта азбестоцементная напорная',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 103),
                        'from' => array(
                            'numeric' => 97))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Труба азбестоцементная безнапорная',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 111),
                        'from' => array(
                            'numeric' => 105))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('FG'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Муфта азбестоцементная безнапорная',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 111),
                        'from' => array(
                            'numeric' => 105))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 5))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 150),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'м.п.',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 181),
                        'from' => array(
                            'numeric' => 152))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 191),
                        'from' => array(
                            'numeric' => 184))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>true,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 193))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 5))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 5))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 5))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(10)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '1м3',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('D'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 25),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '1м3 с добавками',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 25),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'Мешок 50 кг',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('D'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 32),
                        'from' => array(
                            'numeric' => 27))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                $this->documentParsing();

            }else if($key == 'krepezh'){

            }
            //$kid = new $key($path);
            //$this->items = array_merge($this->items,$kid->processParsing());
        }
        //p($this->items);
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $treeString = '';
        $headerRefresh=true;
        $fixCell = $this->filter['defaultfixcell'];
        $new_header=false;
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array(trim($row), array('ч - с четвертью'))){
                        $header='Кольца колодезные с четвертью';
                        continue;
                    }
                    if(in_array(trim($row), array('1 - отверстие'))){
                        $header='Кольца колодезные с днищем с четвертью';
                        continue;
                    }
                    if(in_array(trim($row), array('2 - ширина (м)'))){
                        $header='Плиты дорожные';
                        continue;
                    }
                    if(count($rows) == 0)$headerRefresh=true;
                    if($this->filter['dualheader'])$headerRefresh=true;
                    if($headerRefresh && count($rows) == 1 && $this->filter['horizontal']['from']['numeric'] == $num_row &&$this->filter['header']){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){
                            $header = $row;
                        }else {
                            if($this->filter['dualheader']){
                                $header = $this->filter['header'].' '.$row;
                            }else{
                                $header = $row;
                            }
                        }
                        $new_header=true;
                        $headerRefresh=false;
                        continue;
                    }
                    if($this->filter['fixcell']!== false && in_array($num_row, $this->filter['fixcell'])){
                        $fixCell=$row;
                        continue;
                    }
                    if($this->filter['fixcell']!== false && in_array($num_row-1, $this->filter['fixcell'])){
                        $name .= ' '.$fixCell;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
                        continue;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                        $cost = Round(str_replace(array(' ','руб.',',','р.'),'',$row) * $this->filter['coef']);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost2']))){
                        $cost = Round(str_replace(array(' ','руб.',',','р.'),'',$row) * $this->filter['coef']);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }
                    if(in_array($row, array('тн','тн.', 'шт.', 'кг',))){
                        continue;
                    }
                    /*if(in_array($row, array('Кирпич ручной формовки (Эстония)'))){
                        $this->filter['header'] = true;
                    }*/
                    $new_header=false;
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($header.' '.$treeString.' '.$name.' '.$this->filter['end']))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
}