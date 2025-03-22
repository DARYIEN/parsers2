<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParSpecTechMash extends CParMain{
    var $city_id;
    static $name_parser = array(
        'spectechmash' => 'СпецТехМаш'
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
        $this->document_url = $this->getUrl();
        //$this->coef = 1000;
        //$this->dual_cost = false;
        $this->price_id = 7796533;
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
            $this->filter =  array(             //лист0
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 10))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('H'),
                    'from' => column('F')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 10))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('L'),
                    'from' => column('J')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 10))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing(); //конец лист0

            $this->filter =  array(     //лист1
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('H'),
                    'from' => column('F')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('L'),
                    'from' => column('J')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();   //конец лист1

            $this->filter =  array(     //лист2
                'dermolist'=> 1,
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 2,
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
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 3,
                'horizontal' => array(
                    'to' => column('F'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 4,
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('H')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 51),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 4,
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('H')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 53))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 5,
                'horizontal' => array(
                    'to' => column('K'),
                    'from' => column('H')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 51),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 5,
                'horizontal' => array(
                    'to' => column('K'),
                    'from' => column('H')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 53))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> -1,
                'horizontal' => array(
                    'to' => column('O'),
                    'from' => column('M')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing(); //конец лист2

            $this->filter =  array(     //лист3
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('H'),
                    'from' => column('F')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('L'),
                    'from' => column('J')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();   //конец лист3

            $this->filter =  array(     //лист4
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => 44),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 1,
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 46))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=> 2,
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('B')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 46))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('H'),
                    'from' => column('F')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('L'),
                    'from' => column('J')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 7))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();   //конец лист4

        }
        $this->save();
    }
    function documentParsing(){
        $name = '';
        $cost = '';
        $header = '';
        $clear = true;
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            $cost = isset($rows[$this->filter['horizontal']['to']['numeric']]) ? $rows[$this->filter['horizontal']['to']['numeric']] : '';
            if($cost=='руб/м') $cost='мп';
            if( empty($cost)){
                if($clear &&  empty($rows[$this->filter['horizontal']['from']['numeric']]))
                {
                   if(empty($this->filter['dermolist'])){ $header = '';}else{}
                    $clear = false;
                }
                if(!isset($rows[$this->filter['horizontal']['from']['numeric']+1])){
                    if(empty($this->filter['dermolist'])||$this->filter['dermolist']<4){
                        $header .= isset($rows[$this->filter['horizontal']['from']['numeric']]) ? $rows[$this->filter['horizontal']['from']['numeric']] : '';
                    }else if($header==''){
                        $header = isset($rows[$this->filter['horizontal']['from']['numeric']]) ? $rows[$this->filter['horizontal']['from']['numeric']] : '';
                        if($this->filter['dermolist']==4 && $header!=='') $header .=' квадратные';
                        if($this->filter['dermolist']==5 && $header!=='') $header .=' прямоугольные';
                    }
                }else{
                $clear=true;
                }
                continue;
            }else if(!empty($this->filter['dermolist']) && !empty($rows[$this->filter['horizontal']['to']['numeric']])){
                $clear=false;
                if(!is_numeric($cost) && $cost!="цена"&& $cost!="-"){
                    $header .= ' '.$cost;
                    $clear=true;
                    continue;
                }
            }else{
                $clear=true;
            }
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        continue;
                    }
                if(in_array($row, array('тн', 'шт', 'руб/м'))){
                    if(in_array($row, array('руб/м'))){
                        $name .= ' '.'мп';
                    }
                    continue;
                }
                if(in_array($row, array('кг'))){
                    $cost =  $cost*1000;
                    continue;
                }

                if(in_array($row, array('руб/м'))){
                    $name .= ' '.$row;
                }

                if(!empty($this->filter['dermolist'])){
                    if($this->filter['dermolist']==2){
                        if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']-1))){
                            continue;
                        }
                    }
                    if($this->filter['dermolist']==3){
                        if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']-1))){
                            continue;
                        }
                        if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']-2))){
                            continue;
                        }
                    }
                    if($this->filter['dermolist']==5){
                        if(in_array($num_row, array($this->filter['horizontal']['from']['numeric']))){
                            continue;
                        }
                        if(in_array($num_row, array($this->filter['horizontal']['from']['numeric']+1))){
                            continue;
                        }
                    }
                }
                    $name .= ' '.$row;
                }
            }
            if(!empty($header) && !empty($cost)){
                $this->items[] = array('name' => preg_replace('/;/iu','!',preg_replace('/\s+/iu',' ', $header.' '.$name)), 'cost' => $cost);
            }
            $name = '';
            $cost = '';
        }
    }
    public function getUrl(){
        $parse = file_get_html('http://stmmsk.ru/prise');
        $links = $parse->find('div.item-page p a', 0)->href;
        return $links;
    }
}