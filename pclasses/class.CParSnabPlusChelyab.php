<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParSnabPlusChelyab extends CParMain{
    var $city_id;
    static $name_parser = array(
        'snabplus' => 'Снаб плюс'
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
        $this->document_extended = '.xls';
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_url = 'http://snab-plus.my1.ru/prajs_ooo_snab-pljus.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 8614176;
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
        foreach($this->document_list as $path){
            $this->filter =  array(
                'propusk' => array(6),
                'cost1' => false,
                'cost2' => false,
                'cost3' => 0,
                'coef' => 1,
                'end' => '',
                'header' => false,
                'beginOfPrice' => 0,
                'skleyka' => false,
                'dualheader'=>false,
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('B')),
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
                        $cost = Round(str_replace(array(' ','руб.'),'',$row)) * $this->filter['coef'];
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
                        $cost = Round(str_replace(array(' ','руб.'),'',$row)) * $this->filter['coef'];
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
                        $cost = Round(str_replace(array(' ','руб.'),'',$row)) * $this->filter['coef'];
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
                    if(count($rows) == 1 ){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $row; }else {$header = $this->filter['header'].' '.$row;}
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
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', $name.' '.$this->filter['end']))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);

        }
    }
}