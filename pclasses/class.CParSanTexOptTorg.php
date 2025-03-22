<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParSanTexOptTorg extends CParMain{
    var $city_id;
    public $file_list = array();
    static $name_parser = array(
        'santexopttorg' => 'СанТехОптТорг'
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
        $this->document_url = 'http://www.stot.ru/price/4z.zip';
        //$this->file_list['price'] = ROOT.'/'.$this->dirArray['zip'].'/Џа ©б …ђ€_2014_01_20ls.xls';
        $dir = ROOT.'/'.$this->dirArray['zip'].'/';
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
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 11487323;
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
                        case 2:
                            $this->filter =  array(
                            'propusk' => $this->num('DE'),
                            'cost1' => false,
                            'cost2' => 0,
                            'coef' => 1,
                            'end' => '',
                            'header' => false,
                            'skleyka' => false,
                            'dualheader'=>false,
                            'tree' => false,
                            'fixcell' => false,
                            'baseOfFixCell' => false,
                            'defaultfixcell' => '',
                            'horizontal' => array(
                                'to' => column('F'),
                                'from' => column('C')),
                            'vertical' => array(
                                'to' => array(
                                    'numeric' => null),
                                'from' => array(
                                    'numeric' => 1))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                            $this->documentParsing();
                        break;
                    }
                }
            }
        }
       // p($this->items);
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
                    if($this->filter['fixcell']!== false && in_array($num_row-current($this->filter['baseOfFixCell']), $this->filter['fixcell'])){
                        $name .= ' '.$fixCell;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
                        continue;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                        $cost = str_replace(array(' ','руб.',',','р.'),'',$row) * $this->filter['coef'];
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
                        $cost = str_replace(array(' ','руб.',',','р.'),'',$row) * $this->filter['coef'];
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
}
class CParSanTexOptTorgKrasnodar extends CParSanTexOptTorg{
    function __construct(){
        parent::__construct();
        $this->price_id = 11487328;
        $this->document_name= 'SanTexOptTorgKrasnodar'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParSanTexOptTorgVoronej extends CParSanTexOptTorg{
    function __construct(){
        parent::__construct();
        $this->price_id = 11487332;
        $this->document_name= 'SanTexOptTorgVoronej'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParSanTexOptTorgVladimir extends CParSanTexOptTorg{
    function __construct(){
        parent::__construct();
        $this->price_id = 11487337;
        $this->document_name= 'SanTexOptTorgVladimir'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParSanTexOptTorgUljanovsk extends CParSanTexOptTorg{
    function __construct(){
        parent::__construct();
        $this->price_id = 11487340;
        $this->document_name= 'SanTexOptTorgUljanovsk'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}