<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */
class CParMetallHoldingKiev extends CParMain{
    var $city_id;
    static $name_parser = array(
        'metallholdingkiev' => 'Металл Холдинг Киев'
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
        $this->document_urls['cherniy'] = 'http://www.metal-holding.kiev.ua/system/data/price/price_st.xls';
        ///$this->document_urls['nzh'] = 'http://www.metal-holding.kiev.ua/system/data/price/price_nj.xls';
        $this->document_urls['alum'] = 'http://www.metal-holding.kiev.ua/system/data/price/price_al.xls';
        $this->document_urls['setka'] = 'http://www.metal-holding.kiev.ua/system/data/price/price_pr.xls';
        $this->dual_cost = true;
        $this->price_id = 11185168;
        $this->author = 'Феликс';
        //$this->iconv = false;
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
            switch($key){
                case 'cherniy':
                    $this->filter =  array(
                        'propusk' => $this->num('BD'),
                        'cost1' => 6,
                        'cost2' => 4,
                        'cost3' => 3,
                        'coef' => 1,
                        'end' => '',
                        'header' => false,
                        'beginOfPrice' => 0,
                        'fake' => '',
                        'skleyka' => false,
                        'dualheader'=>true,
                        'horizontal' => array(
                            'to' => column('I'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 6))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'nzh':
                    $this->filter =  array(
                        'propusk' => array(),
                        'cost1' => false,
                        'cost2' => false,
                        'cost3' => 0,
                        'coef' => 1,
                        'end' => '',
                        'header' => true,
                        'beginOfPrice' => 0,
                        'fake' => '',
                        'skleyka' => false,
                        'dualheader'=>true,
                        'horizontal' => array(
                            'to' => column('C'),
                            'from' => column('A')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 15))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                    $this->documentParsing();
                    break;
                case 'alum':
                    $this->filter =  array(
                        'propusk' => $this->num('BD'),
                        'cost1' => 6,
                        'cost2' => 4,
                        'cost3' => 3,
                        'coef' => 1,
                        'end' => '',
                        'header' => false,
                        'beginOfPrice' => 0,
                        'fake' => '',
                        'skleyka' => false,
                        'dualheader'=>true,
                        'horizontal' => array(
                            'to' => column('I'),
                            'from' => column('A')),
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
                    break;
                case 'setka':
                    $this->filter =  array(
                        'propusk' => $this->num('BD'),
                        'cost1' => 6,
                        'cost2' => 4,
                        'cost3' => 3,
                        'coef' => 1,
                        'end' => '',
                        'header' => false,
                        'beginOfPrice' => 0,
                        'fake' => '',
                        'skleyka' => false,
                        'dualheader'=>true,
                        'horizontal' => array(
                            'to' => column('I'),
                            'from' => column('A')),
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
                    break;
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
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(isset($rows[$this->filter['horizontal']['from']['numeric']+1]) && trim($rows[$this->filter['horizontal']['from']['numeric']+1]) == 'êã'){
                        $this->filter['coef'] = 1000;
                    }else{
                        $this->filter['coef'] = 1;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                        $cost = Round(str_replace(array(' ',','),'.',str_replace(array(' ','руб.'),'',$row))) * $this->filter['coef'];
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if($this->filter['cost2']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost2']))){
                        $cost = Round(str_replace(array(' ',','),'.',str_replace(array(' ','руб.'),'',$row))) * $this->filter['coef'];
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if($this->filter['cost3']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost3']))){
                        $cost = Round(str_replace(array(' ',','),'.',str_replace(array(' ','руб.'),'',$row))) * $this->filter['coef'];
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
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
                    if(count($rows)==1){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$rows[$this->filter['horizontal']['from']['numeric']]; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $rows[$this->filter['horizontal']['from']['numeric']]; }else {$header = $this->filter['header'].' '.$rows[$this->filter['horizontal']['from']['numeric']];}
                        $new_header=true;
                        continue;
                    }
                    if(count($rows)==2){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$rows[$this->filter['horizontal']['from']['numeric']].' '.$rows[$this->filter['horizontal']['from']['numeric']+1]; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $rows[$this->filter['horizontal']['from']['numeric']].' '.$rows[$this->filter['horizontal']['from']['numeric']+1]; }else {$header = $this->filter['header'].' '.$rows[$this->filter['horizontal']['from']['numeric']].' '.$rows[$this->filter['horizontal']['from']['numeric']+1];}
                        $new_header=true;
                        continue;
                    }
                    if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric']+$this->filter['beginOfPrice'] ) $name =str_replace('ВГП, ЭСВ','',$header);
                    $new_header=false;

                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/ø/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', iconv('CP1251','UTF-8',iconv('UTF-8','CP1252',$this->filter['fake'].' '.$name.' '.$this->filter['end'])))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);

        }
    }
}