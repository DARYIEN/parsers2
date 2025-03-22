<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParElecmet extends CParMain{
    var $city_id;
    public $file_list = array();
    static $name_parser = array(
        'elecmet' => 'ЭЛЕКМЕТ'
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
        $this->document_url = 'http://elecmet.ru/prices/elecmet-allprices.zip';
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
        $this->dual_cost = true;
        $this->price_id = 11074476;
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
                        case 3:
                            $this->filter =  array(
                            'propusk' => array(),
                            'cost1' => 0,
                            'cost2' => 1,
                            'coef' => 1,
                            'end' => '',
                            'header' => false,
                            'skleyka' => false,
                            'dualheader'=>false,
                            'beginOfPrice'=>0,
                            'tree' => false,
                            'horizontal' => array(
                                'to' => column('E'),
                                'from' => column('A')),
                            'vertical' => array(
                                'to' => array(
                                    'numeric' => null),
                                'from' => array(
                                    'numeric' => 81))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                            $this->documentParsing();

                        break;

                        case 4:
                            $this->filter =  array(
                                'propusk' => array(),
                                'cost1' => 0,
                                'cost2' => 1,
                                'coef' => 1,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 88))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                            $this->documentParsing();

                        break;

                        case 6:
                            $this->filter =  array(
                                'propusk' => array(),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => 1,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
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
                                'beginOfPrice'=>0,
                                'tree' => 1,
                                'horizontal' => array(
                                    'to' => column('F'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 12))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
                            $this->documentParsing();

                        break;

                        case 7:
                            $this->filter =  array(
                                'propusk' => array(),
                                'cost1' => false,
                                'cost2' => 1,
                                'coef' => 1,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>0,
                                'tree' => false,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 86))
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
        //p($this->items);
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $new_header=false;
        $treeString ='';
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
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
                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }

                    if(in_array($row, array('кг',))){
                        $this->filter['coef'] = 1000;
                        continue;
                    }
                    if(in_array($row, array('тыс.шт','тыс.метров',))){
                        $this->filter['coef'] = 1;
                        //continue;
                    }
                    /*
                    if(in_array($row, array('Наименование'))){
                        continue 2;
                    }
                    if(in_array($row, array('Лист рифленый','Лист оцинкованный 0,8ПС'))){
                        $this->filter['header'] = true;
                    }*/
                    if($this->filter['header']&&in_array($num_row, array(0,)) && count($rows) <= 2 ){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $row; }else {$header = $this->filter['header'].' '.$row;}
                        $new_header=true;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']  && $this->filter['tree']!== false && $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']+1==count($rows)){
                        $treeString = '';
                    }
                    if($num_row <= $this->filter['horizontal']['from']['numeric'] + $this->filter['tree']){
                        $treeString .= $row.' ';
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