<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParTdMetalSib extends CParMain{
    var $city_id;
    static $name_parser = array(
        'tdmetallsib' => 'Торговый дом Металсиб'
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
        $this->document_urls['main'] = 'http://i.tdmetalsib.ru/u/f1/6b2940440111e38b068144b19fbfa3/-/%D0%BF%D1%80%D0%B0%D0%B9%D1%81.xls';
        $this->document_urls['truba'] = 'http://i.tdmetalsib.ru/u/f2/204c76440111e38b068144b19fbfa3/-/%D0%BF%D1%80%D0%B0%D0%B9%D1%81%20%D1%82%D1%80%D1%83%D0%B1%D1%8B.xls';
        $this->document_urls['list'] = 'http://i.tdmetalsib.ru/u/f2/7443a8440111e38b068144b19fbfa3/-/%D1%81%D1%82%D0%B0%D0%BB%D1%8C%20%D0%BB%D0%B8%D1%81%D1%82%D0%BE%D0%B2%D0%B0%D1%8F.xls';
        //$this->document_urls['prof'] = 'http://metallosort.ru/z1/3.xls';
        //$this->document_urls['krepezh'] = 'http://www.zitar.ru/files/price/64/%CA%F0%E5%EF%E5%E6%ED%E0%FF%20%F2%E5%F5%ED%E8%EA%E0.XLS';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 11506157;
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
            if($key == 'main'){
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
                    'header' => true,
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
                            'numeric' => 6))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'truba'){
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
                            'numeric' => 1))
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
                    'header' => true,
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
                            'numeric' => 2))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'list'){
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
                    'header' => 'СТАЛЬ КРОВЕЛЬНАЯ',
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
        $fixCell = $this->filter['defaultfixcell'];
        $new_header=false;
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            // p($rows);
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