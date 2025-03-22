<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParTyssenKruppMoscow extends CParMain{
    var $city_id;
    static $name_parser = array(
        'tyssenkruppmoscow' => 'ТиссенКрупп Материалс'
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
        $this->document_urls['black'] = 'http://www.tkmr.ru/win/download/10872/';
        $this->document_urls['nerj'] = 'http://www.tkmr.ru/win/download/10803/';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 10366424;
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
            if($key == 'black'){
                $this->filter =  array(
                    'propusk' => array(),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => true,
                    'dualheader'=>false,
                    'horizontal' => array(
                        'to' => column('B'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 16))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
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
                    'skleyka' => true,
                    'dualheader'=>false,
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('D')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 16))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
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
                    'skleyka' => true,
                    'dualheader'=>false,
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 16))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
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
                    'skleyka' => true,
                    'dualheader'=>false,
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('J')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 16))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
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
                    'skleyka' => true,
                    'dualheader'=>false,
                    'horizontal' => array(
                        'to' => column('N'),
                        'from' => column('M')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 16))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

            } else if($key == 'nerj'){
                $this->filter =  array(
                    'propusk' => array(),
                    'header' => 'НЕРЖАВЕЮЩИЕ ЛИСТЫ AISI 304 08Х18Н10 (DIN 1.4301)',
                    'horizontal' => array(
                        'to' => column('D'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 61),
                        'from' => array(
                            'numeric' => 11))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsingList();

                $this->filter =  array(
                    'propusk' => array(3),
                    'header' => 'НЕРЖАВЕЮЩИЕ ЛИСТЫ AISI 321 08Х18Н10Т (DIN 1.4541)',
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 61),
                        'from' => array(
                            'numeric' => 11))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsingList();

                $this->filter =  array(
                    'propusk' => array(3,4),
                    'header' => 'НЕРЖАВЕЮЩИЕ ЛИСТЫ AISI 316L 03Х17Н13М3 (DIN 1.4404)',
                    'horizontal' => array(
                        'to' => column('F'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 61),
                        'from' => array(
                            'numeric' => 11))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsingList();

                $this->filter =  array(
                    'propusk' => array(3,4,5),
                    'header' => 'НЕРЖАВЕЮЩИЕ ЛИСТЫ AISI 201 12Х15Г9НД (DIN 1.4372)',
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 61),
                        'from' => array(
                            'numeric' => 11))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsingList();

                $this->filter =  array(
                    'propusk' => array(3,4,5,6),
                    'header' => 'НЕРЖАВЕЮЩИЕ ЛИСТЫ AISI 430 08Х17 (DIN 1.4016)',
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 61),
                        'from' => array(
                            'numeric' => 11))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsingList();

                $this->filter =  array(
                    'propusk' => array(1,3),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'м/п',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>true,
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 8))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => array(6,8),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'м/п',
                    'header' => 'НЕРЖАВЕЮЩИЕ КРУГЛЫЕ ТРУБЫ',
                    'skleyka' => false,
                    'dualheader'=>true,
                    'horizontal' => array(
                        'to' => column('J'),
                        'from' => column('F')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 8))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => array(1,3),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'м/п',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>true,
                    'horizontal' => array(
                        'to' => column('E'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 51),
                        'from' => array(
                            'numeric' => 8))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => array(),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1000,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>true,
                    'horizontal' => array(
                        'to' => column('D'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 51))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => array(7),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1000,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>true,
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('F')),
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
            }
            //$kid = new $key($path);
            //$this->items = array_merge($this->items,$kid->processParsing());
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
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                            $cost = Round(str_replace(array(' ','руб.'),'',$row)) * $this->filter['coef'];
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
                        $cost = Round(str_replace(array(' ','руб.'),'',$row)) * $this->filter['coef'];
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
                    /*
                    if(in_array($row, array('т', 'шт.', 'кг',))){
                        continue;
                    }
                    if(in_array($row, array('Наименование'))){
                        continue 2;
                    }
                    if(in_array($row, array('Лист рифленый','Лист оцинкованный 0,8ПС'))){
                        $this->filter['header'] = true;
                    }*/
                    if(count($rows) == 1 ){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $row; }else {$header = $this->filter['header'].' '.$row;}
                        $new_header=true;
                        continue;
                    }
                    if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric'] ) $name =str_replace('ВГП, ЭСВ','',$header);
                    $new_header=false;
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', $name.' '.$this->filter['end'])))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);

        }
    }

    function documentParsingList(){
        $name = '';
        $cost = '';
        $header = '';
        $header2 = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = Round(str_replace(' ','',preg_replace('~[^0-9]{3}~','',$row)))*1000;
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        $costs[]=$cost;
                        continue;
                    }
                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }
                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if(count($rows) == 1 ){
                        $header = str_replace('Двутавры','Двутавр',$row);
                        continue;
                    }
                    if(/*!empty($header)&&*/$num_row == $this->filter['horizontal']['from']['numeric'] ){
                        $header2 = $header.' '.str_replace('Двутавр','',$row);
                        $name = $header2;
                        continue;
                    }
                    //if($num_row == $this->filter['horizontal']['from']['numeric'] /*|| count($rows) == 3*/) $name =str_replace('Двутавры','Двутавр',$header);
                    if($num_row == $this->filter['horizontal']['from']['numeric']){
                        continue;
                    }
                    $name .= ' '.str_replace('Двутавр','',$row);
                }
            }
            if(!empty($name)&& !empty($costs)){
                $name = str_replace('  ',' ',$name);
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu',',', preg_replace('/\s+/iu',' ', $this->filter['header'].' '.$name))), 'cost' => $costs);
            }
            $name = $header2;
            $cost = '';
            unset($costs);

        }
       // p($this->items);
    }
}