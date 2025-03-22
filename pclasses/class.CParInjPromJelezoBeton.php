<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParInjPromJelezoBeton extends CParMain{
    var $city_id;
    static $name_parser = array(
        'injpromjelezobeton' => 'ИНЖПРОМЖЕЛЕЗОБЕТОН'
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
        $this->document_urls['main']= 'http://betonshop.ru/content/files/12_02_2016.xls';
        //$this->document_urls['krepezh'] = 'http://www.zitar.ru/files/price/64/%CA%F0%E5%EF%E5%E6%ED%E0%FF%20%F2%E5%F5%ED%E8%EA%E0.XLS';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 9912191;
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
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 69),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 69),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 102),
                        'from' => array(
                            'numeric' => 96))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 102),
                        'from' => array(
                            'numeric' => 96))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 145),
                        'from' => array(
                            'numeric' => 131))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 145),
                        'from' => array(
                            'numeric' => 131))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 84),
                        'from' => array(
                            'numeric' => 75))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('H'),
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
                        'to' => column('I'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 118),
                        'from' => array(
                            'numeric' => 108))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('H'),
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
                        'to' => column('I'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 159),
                        'from' => array(
                            'numeric' => 149))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
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
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
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
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 10))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                $this->documentParsing();

                /*$this->filter =  array(
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
                        'from' => column('D')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 8))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();*/
/*
                $this->filter =  array(
                    'propusk' => $this->num('AF'),
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
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('AF'),
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
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('AF'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Ступени',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('A'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Бордюры',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 10),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'руб./м.кв',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('C'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 11))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('C'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'руб./м.кв кр.-корич.',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('D'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 11))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('AF'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Перемычка',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('E'),
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
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('E'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Плиты перекрытий',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('F'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('AF'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Плиты покрытий коллекторов',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'defaultfixcell' => '',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                $this->documentParsing();*/

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
        $fixCell = $this->filter['defaultfixcell'];
        $new_header=false;
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){

                    if(in_array($row, array('Кирпич ручной формовки (Эстония)'))){
                        $this->filter['dualheader'] = false;
                    }

                    if(count($rows) == 1 && $this->filter['header']){
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