<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParOptKrepejh extends CParMain{
    var $city_id;
    static $name_parser = array(
        'optkrepejh' => 'ОптКрепеж'
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
        //$this->document_urls = $this->getUrl();
        $this->document_urls['main'] = 'http://www.interonspb.ru/uploads/price.xls';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 11742080;
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
                for($i=0; $i <= 3; $i++){
                    $this->filter =  array(
                        'propusk' => $this->num('D'),
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
                            'to' => column('E'),
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
                    $this->sheet = $this->objPHPExcel->getSheet($i)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'propusk' => $this->num('J'),
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
                            'to' => column('K'),
                            'from' => column('G')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 1))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet($i)->toArray();
                    $this->documentParsing();
                }

                $this->filter =  array(
                    'propusk' => $this->num('DE'),
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
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('F'),
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
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
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
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('J'),
                        'from' => column('F')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('C'),
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
                $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
                $this->documentParsing();

                for($i=6; $i <= 8; $i++){
                    $this->filter =  array(
                        'propusk' => $this->num('D'),
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
                            'to' => column('E'),
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
                    $this->sheet = $this->objPHPExcel->getSheet($i)->toArray();
                    $this->documentParsing();

                    $this->filter =  array(
                        'propusk' => $this->num('J'),
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
                            'to' => column('K'),
                            'from' => column('G')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 1))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet($i)->toArray();
                    $this->documentParsing();
                }

                $this->filter =  array(
                    'propusk' => $this->num('L'),
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
                        'to' => column('M'),
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
                $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();

                $this->filter =  array(
                    'propusk' => $this->num('D'),
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
                        'to' => column('E'),
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
                $this->sheet = $this->objPHPExcel->getSheet(10)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('C'),
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
                $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('I'),
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
                        'to' => column('J'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 1))
                );
                $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('C'),
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
                $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num('DF'),
                    'cost1' => 2,
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
                    //'columns' => array('D','B','V','AZ'),
                    'horizontal' => array(
                        'to' => column('G'),
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
                $this->sheet = $this->objPHPExcel->getSheet(13)->toArray();
                $this->documentParsing();


            }else if($key == 'nerj'){

            }else if($key == 'prof'){

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
                    if(in_array($row, array('размер','1 скидка'))){
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
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($header.' '.$treeString.' '.$name.' '.$this->filter['end']))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
    public function getUrl(){
        preg_match_all('~href=\W\S+.xlsx{0,1}\W~', file_get_html('http://met-iz.com/?q=%D0%BB%D0%B8%D1%81%D1%82-%D1%81%D1%82%D0%B0%D0%BB%D1%8C%D0%BD%D0%BE%D0%B9')->find('body',0)->innertext, $out);
        $links['main']=$out[0][0];
       // p($out);
        //p(str_replace(array('href=','"',"'"), '', $links));
        //die();
        return str_replace(array('href=','"',"'"), '', $links);
    }
}