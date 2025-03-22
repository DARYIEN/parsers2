<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParDiposTula extends CParMain{
    var $city_id;
    static $name_parser = array(
        'Dipos' => 'DiposTula'
    );
    public $items = array();
    public $fixPrice = array(
        'armatura' => 2400,
        'balka' => 1200,
        'krug' => 1200,
        'hk' => 1800,
        'gk' => 2100,
        'ocink' => 2400,
        'rifl' => 1500,
        '09' => 1200,
        'polosa' => 1500,
        'stalk' => 900,
        'truba76' => 1500,
        'trubaes' => 1500,
        'ush' => 1500,
        'prosech' => 1500,
        'trubaprof' => 2400,
        'shest' => 1200,

    );
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
        $this->document_urls['main'] = 'http://dimeta.org/price.xls';
        //$this->document_urls['truba'] = 'http://metallosort.ru/z1/2.xls';
        //$this->document_urls['prof'] = 'http://metallosort.ru/z1/3.xls';
        //$this->document_urls['prof'] = 'http://metallosort.ru/z1/3.xls';
        //$this->document_urls['krepezh'] = 'http://www.zitar.ru/files/price/64/%CA%F0%E5%EF%E5%E6%ED%E0%FF%20%F2%E5%F5%ED%E8%EA%E0.XLS';
        //$this->coef = 1000;
        $this->decimal = true;
        $this->price_id = 1;
        $this->author = 'Феликс';
        $this->price_type = "safe2";
        $this->iconv = true;
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
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['armatura'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 55),
                        'from' => array(
                            'numeric' => 23))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['balka'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 65),
                        'from' => array(
                            'numeric' => 56))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 66),
                        'from' => array(
                            'numeric' => 66))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['stalk'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 71),
                        'from' => array(
                            'numeric' => 68))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['krug'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 72),
                        'from' => array(
                            'numeric' => 72))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['hk'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 82),
                        'from' => array(
                            'numeric' => 73))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['ocink'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 108),
                        'from' => array(
                            'numeric' => 103))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['rifl'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 117),
                        'from' => array(
                            'numeric' => 109))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['prosech'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 119),
                        'from' => array(
                            'numeric' => 118))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 132),
                        'from' => array(
                            'numeric' => 120))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['polosa'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 143),
                        'from' => array(
                            'numeric' => 133))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 144),
                        'from' => array(
                            'numeric' => 144))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 145),
                        'from' => array(
                            'numeric' => 145))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 152),
                        'from' => array(
                            'numeric' => 146))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 161),
                        'from' => array(
                            'numeric' => 153))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['truba76'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 168),
                        'from' => array(
                            'numeric' => 162))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['trubaes'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 174),
                        'from' => array(
                            'numeric' => 169))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['trubaprof'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 201),
                        'from' => array(
                            'numeric' => 175))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['ush'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 236),
                        'from' => array(
                            'numeric' => 202))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => $this->fixPrice['shest'],
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 237),
                        'from' => array(
                            'numeric' => 237))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
                $this->filter =  array(
                    'propusk' => $this->num("EF"),
                    'cost1' => 3,
                    'cost2' => 0,
                    'costplus' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => '',
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 238))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();



            }
            //$kid = new $key($path);
            //$this->items = array_merge($this->items,$kid->processParsing());
        }
        //p($this->items);
        $this->save();
    }

    private function clear_array($array){
        $new_array = array();
        if(empty($array)){
            return array();
        }
        foreach($array as $key => $value){
            if($value === NULL || $value === '' || trim($value) === '' || trim($value,'&nbsp;') === '') continue;
            $new_array[$key] = $value;

        }
        return $new_array;
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $treeString = '';
        $fixCell = $this->filter['defaultfixcell'];
        $new_header=false;
        $header =$this->filter['header'];
        //print_r($this->sheet);
        foreach($this->sheet as $rows){
            $rows = $this->clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){

                    if(in_array($row, array('Кирпич ручной формовки (Эстония)'))){
                        $this->filter['dualheader'] = false;
                    }

                    if($this->filter['header']  && count($rows) == 1 && $this->filter['horizontal']['from']['numeric'] == $num_row){
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
                    if($this->filter['fixcell']!== false && in_array($num_row, $this->filter['baseOfFixCell'])){
                        $name .= ' '.$fixCell;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
                        continue;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                        $cost = str_replace(array(' ','руб.',',','р.','&#160;'),'',$row) * $this->filter['coef'];
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
                        //$row = iconv('UTF-8','windows-1251',$row);
                        $cost = str_replace(array(' ','руб.','р.','&#160;','&nbsp;', chr(160),),'',$row) * $this->filter['coef'];
                        if(!is_numeric($cost) || is_null($cost) || $cost == 100){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        if($this->filter['costplus']) $cost += $this->filter['costplus'];
                        $costs[] = $cost;
                        continue;
                    }
                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }
                    if(in_array($row, array('тн','тн.', 'шт.', ))){
                        continue;
                    }
                    /*if(in_array($row, array('Кирпич ручной формовки (Эстония)'))){
                        $this->filter['header'] = true;
                    }*/
                    $new_header=false;
                    $name .= ' '.$row;
                    //echo $this->filter['horizontal']['from']['numeric'];
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','', preg_replace('/½/iu','',preg_replace('/\?/iu','', preg_replace('/_/iu',' ', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($header.' '.$treeString.' '.$name.' '.$this->filter['end']))))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
}