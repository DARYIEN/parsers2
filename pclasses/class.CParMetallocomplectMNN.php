<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParMetallocomplectMNN extends CParMain{
    var $city_id;
    static $name_parser = array(
        'metallocomplectmnn' => 'Металлокомплект(МКМ)-Нижний Новгород'
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
        //$this->document_urls['metiz'] = 'http://www.metkomp.ru/mkm-n.novgorod/price/mkmnnovgorod_19_02.xls';
        $this->document_urls['metiz'] = $this->getUrl();
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 11037497;
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
            if($key == 'metiz'){
                $this->filter =  array(
                    'propusk' => array(),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
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
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => array(),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => false,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'tree' => false,
                    'horizontal' => array(
                        'to' => column('G'),
                        'from' => column('E')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 7))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
                //p( $this->items);

            }
            //$kid = new $key($path);
            //$this->items = array_merge($this->items,$kid->processParsing());
        }
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
                    if(mb_strripos ($row,'Цена') !== false){
                        //p('asddddddddddd');
                        if(mb_strripos ($row,'кг')){
                            $this->filter['coef']= 1000;
                            $this->filter['end']='тонны';
                        }else if(mb_strripos ($row,'1000 шт (метров)')){
                            $this->filter['coef']= 1;
                            $this->filter['end']='1000шт(метров)';}
                        else if(mb_strripos ($row,'1000 шт/м')){
                            $this->filter['coef']= 1;
                            $this->filter['end']='1000 шт (метров)';
                        }else if(mb_strripos ($row,'1000шт')){
                            $this->filter['coef']= 1;
                            $this->filter['end']='1000шт';
                        }else if(mb_strripos ($row,'шт')){
                            $this->filter['coef']= 1;
                            $this->filter['end']='1шт';}
                        else if(mb_strripos ($row,'кв м')){
                            $this->filter['coef']= 1;
                            $this->filter['end']='кв м';
                        }
                    }

                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                            $cost = Round(str_replace(array(' ','руб.'),'',$row) * $this->filter['coef']);
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
                    if(count($rows) == 1 && $this->filter['header']){
                        if(in_array($row, array('ГОСТ', 'ТУ',))) continue;
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $row; }else {$header = $this->filter['header'].' '.$row;}
                        if(in_array(trim($row), array('Действуют скидки на фасон  и сорт немерной длины',))) $header='';
                        $new_header=true;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric'] && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
                        continue;
                    }
                    //if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric'] + 1 ) $name =str_replace('ВГП, ЭСВ','',$header);
                    $new_header=false;
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',$header.' '.$treeString.' '.$name.' '.$this->filter['end'])))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
    public function getUrl(){
        $parse = file_get_html('http://www.metkomp.ru/mkm-n.novgorod/');
        $link = $parse->find('div.menu-sitemap-tree a', 6);
       // p($link->href);
        //die;
        return 'http://www.metkomp.ru'.$link->href;
    }
}