<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParStalSplav extends CParMain{
    var $city_id;
    static $name_parser = array(
        'stalsplav' => 'Сталь-Сплав'
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
        $this->document_url = 'http://www.stal-splav.ru/wp-content/themes/stal-splav/includes/dl_save.php?filename=/home/stal-splav/stal-splav.ru/docs/wp-content/downloads/price.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 8900631;
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
            //============================= list 1
            $this->filter =  array(
                'propusk' => array(1,2,3),
                'cost' => 0,                //на сколько сдвигаемся влево
                'coef' => 1,
                'end' => 'от 500 м.пог',
                'header' => '',
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 72),
                    'from' => array(
                        'numeric' => 4))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,3),
                'cost' => 1,
                'coef' => 1000,
                'end' => 'от 200 кг',
                'header' => false,
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 309),
                    'from' => array(
                        'numeric' => 78))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1,
                'end' => '',
                'header' => 'Алюминиевый лист рифленый  «Квинтет»',
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 351),
                    'from' => array(
                        'numeric' => 315))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,3),
                'cost' => 1,
                'coef' => 1000,
                'end' => 'рифленый от 200 кг',
                'header' => '',
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 388),
                    'from' => array(
                        'numeric' => 353))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => '',
                'header' => '',
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 515),
                    'from' => array(
                        'numeric' => 392))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 300 кг',
                'header' => '',
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 571),
                    'from' => array(
                        'numeric' => 521))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2),
                'cost' => 0,
                'coef' => 1000,
                'end' => '',
                'header' => '',
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 680),
                    'from' => array(
                        'numeric' => 575))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2,3),
                'cost' => 0,
                'coef' => 1000,
                'end' => '',
                'header' => true,
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 731),
                    'from' => array(
                        'numeric' => 682))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2,3),
                'cost' => 0,
                'coef' => 1000,
                'end' => '',
                'header' => 'Труба алюминиевая круглая',
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 774),
                    'from' => array(
                        'numeric' => 737))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1000,
                'end' => '',
                'header' => 'Труба алюминиевая круглая',
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 816),
                    'from' => array(
                        'numeric' => 805))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2,4),
                'cost' => 1,
                'coef' => 1000,
                'end' => '',
                'header' => '',
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 844),
                    'from' => array(
                        'numeric' => 821))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1,
                'end' => '',
                'header' => '',
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 857),
                    'from' => array(
                        'numeric' => 849))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 300 кг',
                'header' => '',
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 874),
                    'from' => array(
                        'numeric' => 862))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2,3),
                'cost' => 0,
                'coef' => 1000,
                'end' => '',
                'header' => '',
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 889),
                    'from' => array(
                        'numeric' => 879))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1,2),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 300 кг',
                'header' => '',
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 909),
                    'from' => array(
                        'numeric' => 894))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1,
                'end' => '',
                'header' => 'Алюминиевые сплавы в чушках ГОСТ 1583-93',
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 938),
                    'from' => array(
                        'numeric' => 914))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1,
                'end' => 'м.пог',
                'header' => 'Профиль для натяжных потолков',
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 959),
                    'from' => array(
                        'numeric' => 943))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();
            //============================= end list 1




            //============================= list 2
            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 1 тн',
                'header' => 'Лист нержавеющий',
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 397),
                    'from' => array(
                        'numeric' => 26))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 500кг до 1т',
                'header' => 'Лист нержавеющий',
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 397),
                    'from' => array(
                        'numeric' => 26))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

           /* $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 300кг',
                'header' => 'Труба нержавеющая',
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 366),
                    'from' => array(
                        'numeric' => 401))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
*/
           /* $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 1 тн',
                'header' => 'Труба нержавеющая',
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 351),
                    'from' => array(
                        'numeric' => 368))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();*/

            $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 500кг до 1т',
                'header' => 'Труба нержавеющая',
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 687),
                    'from' => array(
                        'numeric' => 400))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 500кг до 1т',
                'header' => 'Труба нержавеющая',
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 871),
                    'from' => array(
                        'numeric' => 681))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

           /* $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 500кг до 1т',
                'header' => 'Труба нержавеющая',
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 691),
                    'from' => array(
                        'numeric' => 400))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();*/


            $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1,
                'end' => '',
                'header' => '',
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 1022),
                    'from' => array(
                        'numeric' => 1022))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 1 тн',
                'header' => true,
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 1027))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(1),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 500кг до 1т',
                'header' => true,
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 1027))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();
            //============================= end list 2


            //============================= list 3
            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 300 кг',
                'header' => false,
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 361),
                    'from' => array(
                        'numeric' => 2))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => '',
                'header' => true,
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 365),
                    'from' => array(
                        'numeric' => 363))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();

            //============================= end list 3

            //============================= list 4
            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 300 кг',
                'header' => false,
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 361),
                    'from' => array(
                        'numeric' => 2))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();

            //============================= end list 4


            //============================= list 5
            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 500 кг',
                'header' => false,
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 193),
                    'from' => array(
                        'numeric' => 2))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1.18,
                'end' => '',
                'header' => true,
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 221),
                    'from' => array(
                        'numeric' => 195))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'propusk' => array(),
                'cost' => 0,
                'coef' => 1000,
                'end' => 'от 500 кг',
                'header' => 'Бронзовая чушка',
                'horizontal' => array(
                    'to' => column('B'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 225),
                    'from' => array(
                        'numeric' => 225))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
            $this->documentParsing();

            //============================= end list 5



            //============================= list 8

            $this->filter =  array(
                'propusk' => $this->num('AE'),
                'cost1' => 8,
                'cost2' => 9,
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
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 1))
            );
            $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
            $this->documentParsingL();



            //============================= end list 8w

        }
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $new_header=false;
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost']))){
                        $cost = Round(str_replace(array(' ','руб.'),'',$row) * $this->filter['coef']);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        continue;
                    }
                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }
                    if(in_array($row, array('т', 'шт.', 'кг',))){
                        continue;
                    }
                    if(in_array($row, array('Наименование'))){
                        continue 2;
                    }
                    if(count($rows) == 1 ){
                        if($new_header && $this->filter['header'] !== false)$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($header = $this->filter['header'] === true)$header = $row; else $header = $this->filter['header'].' '.$row;
                        $new_header=true;
                        continue;
                    }
                    if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric'] ) $name = $header;
                    $new_header=false;
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($cost)){
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', $name.' '.$this->filter['end']))), 'cost' => array($cost));
            }
            $name = '';
            $cost = '';

        }
    }

    function documentParsingL(){
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
            // p($rows);
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

                    if($this->filter['header'] && count($rows)<=3  && $this->filter['horizontal']['from']['numeric']+1 == $num_row || $row == 'Труба водогазопроводная'){
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
                        $cost = str_replace(array(' ','руб.',',','р.','От','от','&#160;'),'',$row) * $this->filter['coef'];
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
                        $cost = str_replace(array(' ','руб.','р.','&#160;','&nbsp;', chr(160),'От','от'),'',$row) * $this->filter['coef'];
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
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($this->filter['begin'].' '.$header.' '.$treeString.' '.$name.' '.$this->filter['end']))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
}
class CParStalSplavSpb extends CParStalSplav{
    function __construct(){
        parent::__construct();
        $this->price_id = 8900399;
    }
}
class CParStalSplavNN extends CParStalSplav{
    function __construct(){
        parent::__construct();
        $this->price_id = 11059996;
    }
}
class CParStalSplavSamara extends CParStalSplav{
    function __construct(){
        parent::__construct();
        $this->price_id = 11057582;
    }
}