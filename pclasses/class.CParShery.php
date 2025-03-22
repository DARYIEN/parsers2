<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParShery extends CParMain{
    var $city_id;
    public $file_list = array();
    static $name_parser = array(
        'shery' => 'ШЕРИ'
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
        $this->document_url = 'http://shery.ru/upload/docs/shery.zip';
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
        $this->price_id = 8471106;
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
                            'propusk' => array($this->n('D'), $this->n('E'), $this->n('F'), $this->n('G'), $this->n('H'), $this->n('I'), $this->n('K'), $this->n('L'), ),
                            'cost1' => 0,
                            'cost2' => 3,
                            'coef' => 1,
                            'end' => '',
                            'header' => false,
                            'skleyka' => false,
                            'dualheader'=>false,
                            'beginOfPrice'=>1,
                            'horizontal' => array(
                                'to' => column('M'),
                                'from' => column('A')),
                            'vertical' => array(
                                'to' => array(
                                    'numeric' => null),
                                'from' => array(
                                    'numeric' => 5))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => array($this->n('D'), $this->n('E'), $this->n('F'), $this->n('G'), $this->n('H'), $this->n('I'), $this->n('K'), $this->n('L'), ),
                                'cost1' => 0,
                                'cost2' => 3,
                                'coef' => 1,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('M'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 5))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('B'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('E'),
                                    'from' => column('D')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('G')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => array($this->n('D'), $this->n('E'), $this->n('F'), $this->n('G'),),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1000,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('H'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 25),
                                    'from' => array(
                                        'numeric' => 7))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => array($this->n('D'), $this->n('F'), $this->n('G'), $this->n('H'),),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('I'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 55),
                                    'from' => array(
                                        'numeric' => 29))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('E'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 72),
                                    'from' => array(
                                        'numeric' => 62))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => array($this->n('D'),),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1000,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('E'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 87),
                                    'from' => array(
                                        'numeric' => 77))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 92),
                                    'from' => array(
                                        'numeric' => 91))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                            $this->documentParsing();

                            $this->filter =  array(
                                'propusk' => array(),
                                'cost1' => false,
                                'cost2' => 0,
                                'coef' => 1000,
                                'end' => '',
                                'header' => false,
                                'skleyka' => false,
                                'dualheader'=>false,
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 101),
                                    'from' => array(
                                        'numeric' => 96))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('D'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 114),
                                    'from' => array(
                                        'numeric' => 105))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('I'),
                                    'from' => column('F')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => 105),
                                    'from' => array(
                                        'numeric' => 104))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
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
                                'beginOfPrice'=>1,
                                'horizontal' => array(
                                    'to' => column('B'),
                                    'from' => column('A')),
                                'vertical' => array(
                                    'to' => array(
                                        'numeric' => null),
                                    'from' => array(
                                        'numeric' => 8))
                            );
                            $this->filter_subset = new MyReadFilter($this->filter);
                            $this->head_non = true;
                            $this->documentLoad($file_path);
                            $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
                            $this->documentParsing();
                            break;
                    }
                }
            }
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
                    if(count($rows) == 1 ){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $row; }else {$header = $this->filter['header'].' '.$row;}
                        $new_header=true;
                        continue;
                    }
                    if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric'] + $this->filter['beginOfPrice'] ) $name =str_replace('ВГП, ЭСВ','',$header);
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
}