<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParDorPlit extends CParMain{
    var $city_id;
    static $name_parser = array(
        'dorplit' => 'ДорПлит ЖБИ'
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
        $this->document_urls['pliti'] = 'http://dorgbi.ru/uploads/files/%D0%94%D0%BE%D1%80%D0%BF%D0%BB%D0%B8%D1%82%D0%96%D0%91%D0%98-%D0%BF%D1%80%D0%B0%D0%B9%D1%81%20%D0%BF%D0%BB%D0%B8%D1%82%D1%8B.xlsx';
        $this->document_urls['pesok'] = 'http://dorgbi.ru/uploads/files/%D0%94%D0%BE%D1%80%D0%9F%D0%BB%D0%B8%D1%82%D0%96%D0%91%D0%98-%D0%BF%D1%80%D0%B0%D0%B9%D1%81-%D0%BF%D0%B5%D1%81%D0%BE%D0%BA_%D1%89%D0%B5%D0%B1%D0%B5%D0%BD%D1%8C.xlsx';
        $this->document_urls['pfsb'] = 'http://dorgbi.ru/uploads/files/%D0%94%D0%BE%D1%80%D0%BF%D0%BB%D0%B8%D1%82%D0%96%D0%91%D0%98-%D0%BF%D1%80%D0%B0%D0%B9%D1%81-%D0%9F%D0%A1%D0%A4%D0%91.xls';
        $this->document_urls['jbi'] = 'http://dorgbi.ru/uploads/files/%D0%94%D0%BE%D1%80%D0%BF%D0%BB%D0%B8%D1%82%D0%96%D0%91%D0%98-%D0%BF%D1%80%D0%B0%D0%B9%D1%81%20%D0%B6%D0%B1%D0%B8-%D1%84%D0%B1%D1%81.xlsx';
        $this->document_urls['dver'] = 'http://dorgbi.ru/uploads/files/%D0%94%D0%BE%D1%80%D0%BF%D0%BB%D0%B8%D1%82%D0%96%D0%91%D0%98-%D0%BF%D1%80%D0%B0%D0%B9%D1%81_%D0%B4%D0%B2%D0%B5%D1%80%D0%B8.xlsx';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 66666666;
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
            if($key == 'pliti'){
                $this->filter =  array(
                    'propusk' => $this->num('HI'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'horizontal' => array(
                        'to' => column('J'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 9))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'pesok'){
                $this->filter =  array(
                    'propusk' => $this->num('H'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => 'Щебень',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 11),
                        'from' => array(
                            'numeric' => 9))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('FG'),
                    'cost1' => 3,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'Карьерный песок строительный',
                    'header' => 'Щебень',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 20),
                        'from' => array(
                            'numeric' => 17))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'pfsb'){
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
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 9))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'jbi'){
                $this->filter =  array(
                    'propusk' => $this->num('GH'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => 57),
                        'from' => array(
                            'numeric' => 9))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'dver'){
                $this->filter =  array(
                    'propusk' => $this->num('J'),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('C')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 12))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
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
        $new_header=false;
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
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
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
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