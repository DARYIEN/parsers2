<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParSmsM extends CParMain{
    var $city_id;
    static $name_parser = array(
        'SmsM' => 'SmsM'
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
        $this->document_urls['main'] = 'http://www.smsm.ru/price_list?filter';
        $this->curlPostData = 'fld%5B6581%5D=1&fld%5B6784%5D=1&fld%5B3876%5D=1&fld%5B6805%5D=1&fld%5B3814%5D=1&fld%5B3979%5D=1&fld%5B3605%5D=1&fld%5B3647%5D=1&fld%5B3656%5D=1&fld%5B3710%5D=1&fld%5B3898%5D=1&fld%5B6806%5D=1&fld%5B3742%5D=1&fld%5B3751%5D=1&fld%5B4079%5D=1&fld%5B3766%5D=1&fld%5B3772%5D=1&fld%5B3810%5D=1&fld%5B3792%5D=1&fld%5B3837%5D=1&fld%5B6807%5D=1&fld%5B6557%5D=1&fld%5B3821%5D=1&fld%5B3720%5D=1&fld%5B3809%5D=1&fld%5B3897%5D=1&fld%5B6769%5D=1&fld%5B6808%5D=1&fld%5B3919%5D=1&fld%5B3957%5D=1&fld%5B3978%5D=1&fld%5B6810%5D=1&fld%5B4008%5D=1&fld%5B4023%5D=1&fld%5B4037%5D=1&fld%5B4049%5D=1&fld%5B4075%5D=1&fld%5B4127%5D=1&fld%5B3732%5D=1';
        //$this->document_urls['main'] = 'http://www.sanitary-group.ru/filesprice/price.xls';
        //$this->coef = 1000;
        //$this->iconv = false;
        $this->price_type = 'safe2';
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 1;
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
                    'cost1' => false,
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
                        'to' => column('C'),
                        'from' => column('B')),
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
                $name = preg_replace('/[\(\)\[\]\'"]/iu',' ', $name);
                //$name = iconv('UTF-8','windows-1251',$name);
                $name = iconv('CP1251','UTF-8',iconv('UTF-8','CP1252', $name));
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($this->filter['begin'].' '.$header.' '.$treeString.' '.$name.' '.$this->filter['end']))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
}
