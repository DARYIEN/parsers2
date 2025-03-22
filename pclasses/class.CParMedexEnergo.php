<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParMedexEnergo extends CParMain{
    var $city_id;
    public $file_list = array();
    static $name_parser = array(
        'medexenergo' => 'Медэкс Энерго'
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
        $this->document_url = 'http://medexe.ru/netcat_files/File/price.zip';
        //$this->file_list['price'] = ROOT.'/'.$this->dirArray['zip'].'/Џа ©б …ђ€_2014_01_20ls.xls';
        $dir = ROOT.'/'.$this->dirArray['zip'].'/price/';
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    $this->file_list[] =$dir.$file;
                }
                closedir($dh);
            }
            //p($this->file_list);
            //die();
        }
       // rename($this->file_list[4],$dir.'price.xlsx');
       // $this->file_list[4]=$dir.'price.xlsx';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 11074520;
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
                        case 4:
                            $this->filter =  array(
                            'propusk' => $this->num('BC'),
                            'cost1' => false,
                            'cost2' => 0,
                            'coef' => 1.18,
                            'end' => '',
                            'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 6',
                            'skleyka' => false,
                            'dualheader'=>true,
                            'beginOfPrice'=>0,
                            'tree' => false,
                            'horizontal' => array(
                                'to' => column('D'),
                                'from' => column('A')),
                            'vertical' => array(
                                'to' => array(
                                    'numeric' => 37),
                                'from' => array(
                                    'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 6',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 37),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHI'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 10',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('J'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 37),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHIJK'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 10',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('L'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 37),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('OP'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 16',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('Q'),
                                    'from' => column('N')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 37),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('OPQR'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 16',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('S'),
                                    'from' => column('N')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 37),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('OPQRSTU'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 25',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('V'),
                                    'from' => column('N')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 37),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('OPQRSTUVW'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => 'Фланцы плоские приварные, сталь 20 ГОСТ 12820-80 Рy 25',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('X'),
                                    'from' => column('N')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 37),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык, сталь 20 ГОСТ 12821-80 Рy 10',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 64),
                                    'from' => array(
                                        'numeric' => 44))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык, сталь 20 ГОСТ 12821-80 Рy 16',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 64),
                                    'from' => array(
                                        'numeric' => 44))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHI'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык, сталь 20 ГОСТ 12821-80 Рy 25',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('J'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 64),
                                    'from' => array(
                                        'numeric' => 44))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHIJKLM'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык, сталь 20 ГОСТ 12821-80 Рy 40',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('N'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 64),
                                    'from' => array(
                                        'numeric' => 44))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцевые заглушки, сталь 20 АТК 24.200.02-90 Ру 10',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 87),
                                    'from' => array(
                                        'numeric' => 71))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцевые заглушки, сталь 20 АТК 24.200.02-90 Рy 16',
                                'skleyka' => false,
                                'dualheader'=>true,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 87),
                                    'from' => array(
                                        'numeric' => 71))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            ///////////////////list3

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFG'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Ст.09г2с',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                            $this->documentParsing();

                            //////////////////list4

                            $this->filter =  array(
                                'propusk' => $this->num('CD'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('E'),
                                    'from' => column('B')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('CDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                            $this->documentParsing();

                            ///////////////////list5

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('ABCDEFHI'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('J'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('ABCDEFHIJK'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('L'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                            $this->documentParsing();

                            ///////////////////list6

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 6',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('C'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCD'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 10',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('E'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 16',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGH'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 25',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('I'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHIJK'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 40',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('L'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHIJKLM'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 63',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('N'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHIJKLMNO'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 100',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('P'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHIJKLMNOPQ'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы приварные встык ст.12Х18Н10Т ГОСТ 12821 Ру 160',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('R'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 27),
                                    'from' => array(
                                        'numeric' => 10))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы плоские приварные ст.12Х18Н10Т ГОСТ 12820-80 Ру 6',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('C'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 55),
                                    'from' => array(
                                        'numeric' => 35))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCD'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы плоские приварные ст.12Х18Н10Т ГОСТ 12820-80 Ру 10',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('E'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 55),
                                    'from' => array(
                                        'numeric' => 35))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы плоские приварные ст.12Х18Н10Т ГОСТ 12820-80 Ру 16',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('G'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 55),
                                    'from' => array(
                                        'numeric' => 35))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGH'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => 'Фланцы плоские приварные ст.12Х18Н10Т ГОСТ 12820-80 Ру 25',
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('I'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 55),
                                    'from' => array(
                                        'numeric' => 35))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('M'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('N'),
                                    'from' => column('L')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 55),
                                    'from' => array(
                                        'numeric' => 30))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('LMNOQ'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('R'),
                                    'from' => column('L')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 55),
                                    'from' => array(
                                        'numeric' => 30))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
                            $this->documentParsing();

                            ///////////////////list7

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('C'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('E'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('D')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('I'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('J'),
                                    'from' => column('H')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('L'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('M'),
                                    'from' => column('K')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                            $this->documentParsing();

                            ///////////////////list8

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 10',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('C'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCD'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 16',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('E'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 25',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGH'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 40',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('L'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('M'),
                                    'from' => column('K')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('MO'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('P'),
                                    'from' => column('M')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
                            $this->documentParsing();

                            ///////////////////list9

                            $this->filter =  array(
                                'propusk' => $this->num('B'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 6',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('C'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCD'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 10',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('E'),
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
                            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEF'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 16',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGH'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 25',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
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
                            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('L'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 16',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('M'),
                                    'from' => column('K')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('LMN'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'РУ 40',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('O'),
                                    'from' => column('K')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
                            $this->documentParsing();

                            ///////////////////list10

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
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

                            ///////////////////list11

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Чугун ГОСТ 8961',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 31),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Чугун оцинк. ГОСТ 8961',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 31),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFG'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'ст.25(20) ГОСТ 8968',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 31),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFGHI'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'ст.AISI (нерж) ISO',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('J'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 31),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'ст.AISI (нерж) ISO',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 42),
                                    'from' => array(
                                        'numeric' => 33))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('GHI'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'ст. 25(20) ГОСТ 8969',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('J'),
                                    'from' => column('F')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 42),
                                    'from' => array(
                                        'numeric' => 33))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Чугун ГОСТ 8961',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 59),
                                    'from' => array(
                                        'numeric' => 44))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Чугун оцинк. ГОСТ 8946',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 59),
                                    'from' => array(
                                        'numeric' => 44))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDEFG'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'ст.AISI (нерж) ISO',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 59),
                                    'from' => array(
                                        'numeric' => 44))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'ст.25(20) ГОСТ 8968',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 72),
                                    'from' => array(
                                        'numeric' => 61))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'ст.AISI (нерж) ISO',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 72),
                                    'from' => array(
                                        'numeric' => 61))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                            $this->documentParsing();

                            ///////////////////list12

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 58),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'оцинк.',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 58),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('IJ'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('K'),
                                    'from' => column('H')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 32),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('IJ'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Ст.09г2с',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('K'),
                                    'from' => column('H')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 46),
                                    'from' => array(
                                        'numeric' => 34))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('IJKL'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Ст.А2',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('M'),
                                    'from' => column('H')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 46),
                                    'from' => array(
                                        'numeric' => 34))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('IJ'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('K'),
                                    'from' => column('H')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 48),
                                    'from' => array(
                                        'numeric' => 58))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Ст. 35',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 87),
                                    'from' => array(
                                        'numeric' => 60))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Ст.09г2с',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 87),
                                    'from' => array(
                                        'numeric' => 60))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BC'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Паронит ПОН',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 113),
                                    'from' => array(
                                        'numeric' => 89))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('BCDE'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => 'Резина ТМКЩ',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 113),
                                    'from' => array(
                                        'numeric' => 89))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => $this->num('IJ'),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1.18,
                                'end' => '',
                                'header' => true,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('K'),
                                    'from' => column('H')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 87),
                                    'from' => array(
                                        'numeric' => 60))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                            $this->documentParsing();




                            break;


                    }
                }
            }
        }
        //p('<br>'.memory_get_usage()/(1024*1024));
        //p($this->items);
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $new_header=false;
        $treeString ='';
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            //die();
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if( count($rows) == 1 && strripos($row,'код')===false){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $row; }else {$header = $this->filter['header'].' '.$row;}
                        $new_header=true;
                        continue;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                            $cost = Round(str_replace(array(' ','руб.',','),'',$row) * $this->filter['coef']);
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
                        $cost = Round(str_replace(array(' ','руб.',','),'',$row) * $this->filter['coef']);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
                        continue;
                    }


                    if(in_array($row, array('кг',))){
                        $this->filter['coef'] = 1000;
                        continue;
                    }
                    if(in_array($row, array('тыс.шт','тыс.метров',))){
                        $this->filter['coef'] = 1;
                        //continue;
                    }
                    /*
                    if(in_array($row, array('Наименование'))){
                        continue 2;
                    }
                    if(in_array($row, array('Лист рифленый','Лист оцинкованный 0,8ПС'))){
                        $this->filter['header'] = true;
                    }*/

                    if($num_row == $this->filter['horizontal']['from']['numeric']  && $this->filter['tree']!== false && $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']+1==count($rows)){
                        $treeString = '';
                    }
                    if($this->filter['tree'] !== false && $num_row <= $this->filter['horizontal']['from']['numeric'] + $this->filter['tree']){
                        $treeString .= $row.' ';
                        continue;
                    }
                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }
                    //if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric'] + 1 ) $name =str_replace('ВГП, ЭСВ','',$header);
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