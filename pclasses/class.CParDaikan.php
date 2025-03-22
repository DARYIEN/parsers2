<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParDaikan extends CParMain{
    var $city_id;
    static $name_parser = array(
        'Daikan' => 'ДайКан'
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
        $this->document_urls['main'] = $this->getUrl('http://k-daikan.ru/trubyi1/trubyi-kruglogo-secheniya.html');
        $this->document_urls['bu'] = $this->getUrl('http://k-daikan.ru/trubyi1/trubyi-byivshie-v-upotreblenii.html');
        //$this->document_urls['main'] = 'http://vialmet.ru/upload/Прайс%20лист%20всей%20продукции%20Виалмет.xls';
        //$this->document_urls['prof'] = 'http://www.spk.ru/price/spk-krsn/_m_/10786/_p_/file';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 14393840;
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
                $this->filter =  array(
                    'propusk' => $this->num(''),
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
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => $this->num(''),
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
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();
            }else if($key == 'bu'){
                $this->filter =  array(
                    'propusk' => $this->num(''),
                    'cost1' => 1,
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
    public function getUrl($url){
        foreach(file_get_html($url)->find('body a') as $link){
            $href = $link->href;
            if(strripos($href,'.xls' )!==false){
                if(strripos($href, parse_url($url, PHP_URL_HOST))!== true){
                    $href = 'http://'.parse_url($url, PHP_URL_HOST).'/'.$href;
                }
                $hrefs[] = $href;
            }
        }
        //p($hrefs);
        $links=str_replace(' ','%20',$hrefs[0]);
       // p($links);
        //die();http://k-daikan.ru/trubyi1/trubyi-byivshie-v-uss%20%20%20potreblenii.html
        return $links;
    }
}