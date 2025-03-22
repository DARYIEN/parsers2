<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParMetallTransGruz extends CParMain{
    var $city_id;
    static $name_parser = array(
        'MetallTransGruz' => 'Металл Транс Груз '
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
        $this->document_urls['main'] = 'http://www.ksst.ru/files/price/arm.xls';
        $this->document_urls['ugol'] = 'http://www.ksst.ru/files/price/ugol.xls';
        $this->document_urls['krug'] = 'http://www.ksst.ru/files/price/krug.xls';
        $this->document_urls['kvadrat'] = 'http://www.ksst.ru/files/price/kvadrat.xls';
        $this->document_urls['polosa'] = 'http://www.ksst.ru/files/price/polosa.xls';
        $this->document_urls['shweller'] = 'http://www.ksst.ru/files/price/shweller.xls';
        $this->document_urls['list'] = 'http://www.ksst.ru/files/price/listovojprokat.xls';
        $this->document_urls['tprof'] = 'http://www.ksst.ru/files/price/trubypROF.xls';
        $this->document_urls['tbu'] = 'http://www.ksst.ru/files/price/truby_bu.xls';
        $this->document_urls['relsi'] = 'http://www.ksst.ru/files/price/relsi.xls';


        $this->document_urls['truby'] = 'http://www.ksst.ru/files/price/truby.xls';
        $this->document_urls['balka'] = 'http://www.ksst.ru/files/price/balka.xls';
        ////$this->document_urls['six'] = 'http://www.ksst.ru/files/price/six.xls';
        $this->document_urls['katanka'] = 'http://www.ksst.ru/files/price/katanka.xls';
        $this->document_urls['provoloka'] = 'http://www.ksst.ru/files/price/provoloka.xls';
        $this->document_urls['setka'] = 'http://www.ksst.ru/files/price/setka.xls';
       // $this->document_urls['pokovka'] = 'http://ksst.ru/files/price/pokovka.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 11521108;
        $this->author = 'Феликс';
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
            switch($key){
                case 'main':
                    $this->filter =  array(
                        'propusk' => $this->num('DC'),
                        'cost1' => 1,
                        'cost2' => 0,
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
                            'to' => column('E'),
                            'from' => column('B')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'ugol':
                    $this->filter =  array(
                        'propusk' => $this->num('BDGE'),
                        'cost1' => 2,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('H'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 4))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'krug':
                    $this->filter =  array(
                        'propusk' => $this->num('DE'),
                        'cost1' => false,
                        'cost2' => 0,
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
                            'to' => column('F'),
                            'from' => column('C')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 7))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'kvadrat':
                    $this->filter =  array(
                        'propusk' => $this->num('DGE'),
                        'cost1' => 2,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('H'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 7))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'polosa':
                    $this->filter =  array(
                        'propusk' => $this->num('DGE'),
                        'cost1' => 2,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('H'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 4))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'shweller':
                    $this->filter =  array(
                        'propusk' => $this->num('BDGE'),
                        'cost1' => 2,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('H'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 7))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'list':
                    $this->filter =  array(
                        'propusk' => $this->num('BC'),
                        'cost1' => 1,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('E'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'tprof':
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
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('E'),
                            'from' => column('B')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'tbu':
                    $this->filter =  array(
                        'propusk' => $this->num('E'),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => 'Труба Б/У ',
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('F'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;

                case 'nerzav':
                    $this->filter =  array(
                        'propusk' => $this->num('C'),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('D'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 5))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
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
                        'header' => 'ПРОВОЛОКА НЕРЖАВЕЮЩАЯ ',
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('I'),
                            'from' => column('F')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 5))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'relsi':
                    $this->filter =  array(
                        'propusk' => $this->num('CDF'),
                        'cost1' => 2,
                        'cost2' => 0,
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
                                'numeric' => 8))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;

                case 'truby':
                    $this->filter =  array(
                        'propusk' => array(),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('B'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'propusk' => array(),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('D'),
                            'from' => column('C')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => 20),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'propusk' => array(),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => 'Трубы бесшовные общего назначения ГОСТ 8732-78 ',
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('D'),
                            'from' => column('C')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => 64),
                            'from' => array(
                                'numeric' => 27))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'propusk' => array(),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => 'Трубы СПИРАЛЬНОШОВНЫЕ производтва ОАО "Волжский Трубный Завод " ГОСТ 8696-74, 20295-85, ТУ 13.03-011-00212179-2003, ТУ 14-3-954-2001 ',
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('D'),
                            'from' => column('C')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 66))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'propusk' => array(),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('F'),
                            'from' => column('E')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => 58),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'propusk' => array(),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => 'Трубы СПИРАЛЬНОШОВНЫЕ производтва ОАО "Волжский Трубный Завод " ГОСТ 8696-74, 20295-85, ТУ 13.03-011-00212179-2003, ТУ 14-3-954-2001 ',
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('F'),
                            'from' => column('E')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 66))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'balka':
                    $this->filter =  array(
                        'propusk' => $this->num('EF'),
                        'cost1' => 3,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => false,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => $this->num('B'),
                        'baseOfFixCell' => $this->num('C'),
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('G'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 7))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;

                case 'katanka':
                    $this->filter =  array(
                        'propusk' => $this->num('G'),
                        'cost1' => 1,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('G'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 7))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;

                case 'provoloka':
                    $this->filter =  array(
                        'propusk' => $this->num('C'),
                        'cost1' => 2,
                        'cost2' => 0,
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
                            'to' => column('D'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 7))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;

                case 'setka':
                    $this->filter =  array(
                        'propusk' => $this->num('DEF'),
                        'cost1' => 1,
                        'cost2' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => '',
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('H'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 7))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
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
            // p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){

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
    public function getUrl(){
        $parse = file_get_html('http://www.tdnovosib.ru/prays_obschiy');
        $link = $parse->find('td.body ul li a', 0);
        return 'http://www.tdnovosib.ru'.$link->href;
    }
}