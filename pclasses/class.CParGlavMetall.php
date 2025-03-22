<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParGlavMetall extends CParMain{
    var $city_id;
    static $name_parser = array(
        'glavmetall' => 'ГлавМеталл'
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
        $this->document_urls = $this->getUrl();
        $this->document_urls['setka'] = 'http://metallross.ru/prayslist/setka_all.xlsx';
        $this->document_urls['ocinkovka'] = 'http://metallross.ru/prayslist/ocinkovka.xlsx';
        $this->document_urls['provoloka'] = 'http://metallross.ru/prayslist/provoloka.xlsx';
        $this->document_urls['listgk'] = 'http://metallross.ru/prayslist/listgk.xlsx';
        $this->document_urls['listhk'] = 'http://metallross.ru/prayslist/listhk.xlsx';
        $this->document_urls['listrifleniy'] = 'http://metallross.ru/prayslist/listrifleniy.xlsx';
        $this->document_urls['pvl'] = 'http://metallross.ru/prayslist/pvl.xlsx';
        $this->document_urls['kvadratgk'] = 'http://metallross.ru/prayslist/kvadratgk.xlsx';
        $this->document_urls['kruggk'] = 'http://metallross.ru/prayslist/kruggk.xlsx';
        $this->document_urls['truba'] = 'http://metallross.ru/prayslist/truba.xlsx';
        $this->document_urls['armatura'] = 'http://metallross.ru/prayslist/armatura.xlsx';
        $this->document_urls['balka'] = 'http://metallross.ru/prayslist/balka.xlsx';
        $this->document_urls['shveller'] = 'http://metallross.ru/prayslist/shveller.xlsx';
        $this->document_urls['polosa'] = 'http://metallross.ru/prayslist/polosa.xlsx';
        $this->document_urls['ugolokravnop'] = 'http://metallross.ru/prayslist/ugolokravnop.xlsx';
        $this->document_urls['ugolokneravnop'] = 'http://metallross.ru/prayslist/ugolokneravnop.xlsx';
        $this->document_urls['katanka'] = 'http://metallross.ru/prayslist/katanka.xlsx';
        $this->document_urls['fiting'] = 'http://metallross.ru/prayslist/fiting.xlsx';
        $this->document_urls['nerzhaveiyka'] = 'http://metallross.ru/prayslist/nerzhaveiyka.xlsx';
        $this->document_urls['trubabu'] = 'http://metallross.ru/prayslist/trubabu.xlsx';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 12366157;
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
            if($key == 'setka'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();
            }else if($key == 'ocinkovka'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('C'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('EG'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('D')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'provoloka'){
                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('M'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('N'),
                        'from' => column('I')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('M'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('N'),
                        'from' => column('I')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();
            }else if($key == 'listgk'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
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
            }else if($key == 'listhk'){
                $this->filter =  array(
                    'propusk' => $this->num('E'),
                    'cost1' => 1,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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
            }else if($key == 'listrifleniy'){
                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 1,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('H'),
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
            }else if($key == 'pvl'){
                $this->filter =  array(
                    'propusk' => $this->num('E'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('F'),
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
            }else if($key == 'kvadratgk'||$key == 'kruggk'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('D'),
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
            }else if($key == 'truba'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => true,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('D'),
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

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => true,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('F')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 53),
                        'from' => array(
                            'numeric' => 6))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => true,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('L'),
                        'from' => column('J')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 53),
                        'from' => array(
                            'numeric' => 6))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('BCDE'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 55))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('BCDEFGH'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('L'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 55))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('C'),
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
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('F'),
                        'from' => column('E')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 6))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('H')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 6))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

            }else if($key == 'armatura'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
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
            }else if($key == 'balka'){
                $this->filter =  array(
                    'propusk' => $this->num('B'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('F'),
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

                $this->filter =  array(
                    'propusk' => $this->num('G'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('G')),
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
            }else if($key == 'shveller'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
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
            }else if($key == 'polosa'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('D'),
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
            }else if($key == 'ugolokravnop'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('F'),
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
            }else if($key == 'ugolokneravnop'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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
            }else if($key == 'katanka'){
                $this->filter =  array(
                    'propusk' => $this->num('C'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('D'),
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
            }else if($key == 'fiting'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('D'),
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
            }else if($key == 'nerzhaveiyka'){
                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('F'),
                    'cost1' => 2,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();
            }else if($key == 'trubabu'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'begin' => '',
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'fixcell' => false,
                    'baseOfFixCell' => false,
                    'defaultfixcell' => false,
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('D'),
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
        if($this->filter['fixcell']!==false) {
            if($this->filter['defaultfixcell']!==false) {
                $fixCell = array_combine($this->filter['fixcell'] ,$this->filter['defaultfixcell']);
            }else{
                $fixCell = array_fill_keys($this->filter['fixcell'] ,'');
            }
        }
        $new_header=false;
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){

                    if(in_array($row, array('Цена за кг, руб'))){
                        $this->filter['coef'] = 1000;
                        $this->filter['end'] = '';
                    }
                    if(in_array($row, array('Цена за м/п, руб'))){
                        $this->filter['coef'] = 1;
                        $this->filter['end'] = 'м/п';
                    }
                    if(in_array(trim($row), array('уголок 63х63х5'))){
                        $this->filter['begin']='';
                    }
                    if(in_array(trim($row), array('ООО ТД «Армроскомплект» является  дилером ООО «ТД «Кичигинский» и предлагает гидранты пр-ва Кичигинского рем. Завода.С прайс-листом завода можно ознакомиться ниже (прайс№1)'))){
                        continue;
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
                        if($num_row == $this->filter['fixcell'][0])$fixCell=array();
                        $fixCell[$num_row]=$row;
                        continue;
                    }
                    if($this->filter['fixcell']!== false && in_array($num_row, $this->filter['baseOfFixCell'])){
                        $name .= ' '.implode(" ", $fixCell);
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
                        continue;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                        $row = iconv('UTF-8','windows-1251',$row);
                        $cost = str_replace(array(' ','руб.','р.','&#160;','&nbsp;', chr(160),'по','от'),'',$row) * $this->filter['coef'];
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
                        $row = iconv('UTF-8','windows-1251',$row);
                        $cost = str_replace(array(' ','руб.','р.','&#160;','&nbsp;', chr(160),'по','от'),'',$row) * $this->filter['coef'];
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
                $this->items[] = array('name' => preg_replace('/×/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($this->filter['begin'].' '.$header.' '.$treeString.' '.$name.' '.$this->filter['end']))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
    public function getUrl(){
        $hrefs = array();
        $url='http://citymetall.ru/pricelist';
        foreach(file_get_html($url)->find('body a') as $link){
            $href = $link->href;
            if(strripos($href,'.xls' )!==false){
                if(strripos($href, parse_url($url, PHP_URL_HOST))!= true){
                    $href = 'http://'.parse_url($url, PHP_URL_HOST).$href;
                }
                $hrefs[] = str_replace(' ','%20',$href);
            }
        }
        $links['main']=$hrefs[0];
        //p($hrefs);
        //p($links);
        //die();
        return $links;
    }
}