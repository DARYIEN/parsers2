<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParMirSetki extends CParMain{
    var $city_id;
    public $file_list = array();
    static $name_parser = array(
        'mirsetki' => 'Мир Сетки'
    );
    public $items = array();
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
        $this->document_extended = '.zip';
        $this->zip = true;
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_url = $this->getUrl();
        //$this->file_list['price'] = ROOT.'/'.$this->dirArray['zip'].'/Џа ©б …ђ€_2014_01_20ls.xls';
        $dir = ROOT.'/'.$this->dirArray['zip'].'/';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $this->file_list[] =$dir.$file;
                }
                closedir($dh);
            }
            //p($this->file_list);
        }
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 11346352;
        //$this->price_type = 'zip';
    }
    function formDirsArray(){
        $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
        $this->dirArray['zip'] = $this->dirArray['root'].'/zip';
        return $this;
    }
    function processParsing(){
        foreach($this->document_list as $path){
            if($this->zip){
                $this->unZip($path);
                foreach($this->file_list as $key => $file_path){
                    switch($key){
                        case 2:
                            $this->filter =  array(
                            'propusk' => $this->num('DEFH'),
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
                            'horizontal' => array(
                                'to' => column('I'),
                                'from' => column('A')),
                            'vertical' => array(
                                'to' => array(
                                    'numeric' => null),
                                'from' => array(
                                    'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('DE'),
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
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('CEF'),
                                'cost1' => 3,
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
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 8))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('DEFH'),
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
                                'horizontal' => array(
                                    'to' => column('I'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 4))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('CDF'),
                                'cost1' => 2,
                                'cost2' => 0,
                                'coef' => 1,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>true,
                                'tree' => false,
                                'fixcell' => false,
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 38),
                                    'from' => array(
                                        'numeric' => 8))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('CDF'),
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
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 47),
                                    'from' => array(
                                        'numeric' => 39))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('CDF'),
                                'cost1' => 2,
                                'cost2' => 0,
                                'coef' => 1,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>true,
                                'tree' => false,
                                'fixcell' => false,
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 48))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num(''),
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
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('B')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 9))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num(''),
                                'cost1' => 1,
                                'cost2' => 0,
                                'coef' => 1,
                                'end' => '',
                                'header' => 'Сетка тканая нержавеющая ГОСТ 3826-82',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'tree' => false,
                                'fixcell' => false,
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('E')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 9))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BE'),
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
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 9))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BE'),
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
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('DEFH'),
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
                                'horizontal' => array(
                                    'to' => column('I'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 6))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('DEFH'),
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
                                'horizontal' => array(
                                    'to' => column('I'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(10)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('FG'),
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
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 29),
                                    'from' => array(
                                        'numeric' => 6))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('FG'),
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
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 44),
                                    'from' => array(
                                        'numeric' => 33))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('E'),
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
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 52),
                                    'from' => array(
                                        'numeric' => 45))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('FG'),
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
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 67),
                                    'from' => array(
                                        'numeric' => 53))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num(''),
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
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 73),
                                    'from' => array(
                                        'numeric' => 69))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('FG'),
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
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(14)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('D'),
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
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 40),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(15)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('DE'),
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
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 41))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(15)->toArray();
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
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('B'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(30)->toArray();
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
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('B'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(31)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('C'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1000,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'tree' => false,
                                'fixcell' => false,
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('B'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 6))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(32)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
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
                                'horizontal' => array(
                                    'to' => column('C'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 6))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(33)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
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
                                'horizontal' => array(
                                    'to' => column('C'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 6))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(34)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1000,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'tree' => false,
                                'fixcell' => false,
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('C'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 8))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(35)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('C'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1,
                                'end' => '',
                                'header' => true,
                                'skleyka' => true,
                                'dualheader'=>false,
                                'tree' => false,
                                'fixcell' => false,
                                'baseOfFixCell' => false,
                                'defaultfixcell' => '',
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 6))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(36)->toArray();
                            $this->documentParsing();
                        break;
                    }
                }
            }
        }
       // p($this->items);
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
                    if($this->filter['fixcell']!== false && in_array($num_row-current($this->filter['baseOfFixCell']), $this->filter['fixcell'])){
                        $name .= ' '.$fixCell;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
                        continue;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                        $cost = Round(str_replace(array(' ','руб.','р.'),'',$row) * $this->filter['coef']);
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
                        $cost = Round(str_replace(array(' ','руб.','р.'),'',$row) * $this->filter['coef']);
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
                    if(in_array($row, array('тн','тн.', 'шт.', 'кг',' тонна',))){
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
                $this->items[] = array('name' => preg_replace('/"/iu','',preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($header.' '.$treeString.' '.$name.' '.$this->filter['end'])))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
    public function getUrl(){
        $parse = file_get_html('http://www.mirsetki.ru/prices');
        $links = 'http://www.mirsetki.ru'.$parse->find('a.b', 0)->href;
        return $links;
    }
}