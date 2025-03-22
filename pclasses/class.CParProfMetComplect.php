<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParProfMetComplect extends CParProfMetComolectWeb{
    var $city_id;

    function __construct(){
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }

        $this->document_extended = '.xlsx';
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_urls = array('http://profmetcomplekt.ru/userfiles/files/PMC_profnastil.xlsx');
        $this->path = 'http://profmetcomplekt.ru/userfiles/files/PMC_profnastil.xlsx';
        //$this->document_urls['procat'] = 'http://178.63.70.28/pricelists/moskva/profmetcomplect/PMC_metalloprokat.xls';
        $this->getDocuments();
        //$this->coef = 1000;
        //$this->dual_cost = true;
        //$this->price_id = 8438017;
    }

    function processParsing(){
        foreach($this->document_list as $key => $path){


                $this->filter =  array(
                    'propusk' => array($this->n('D'), $this->n('E'), $this->n('F'), $this->n('G'), $this->n('H'), ),
                    'cost1' => 6,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'оц',
                    'header' => 'Профнастил',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'blockheader'=>true,
                    'horizontal' => array(
                        'to' => column('I'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 12))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($this->path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => array( $this->n('E'), $this->n('F'), $this->n('G'), $this->n('H'), $this->n('C'), $this->n('I'), ),
                    'cost1' => 6,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'пэ',
                    'header' => 'Профнастил',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'blockheader'=>true,
                    'horizontal' => array(
                        'to' => column('J'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 12))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($this->path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'propusk' => array( $this->n('F'), $this->n('G'), $this->n('H'), $this->n('C'), $this->n('I'), $this->n('E'), $this->n('J'),),
                    'cost1' => 6,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => 'пп',
                    'header' => 'Профнастил',
                    'skleyka' => false,
                    'dualheader'=>false,
                    'blockheader'=>true,
                    'horizontal' => array(
                        'to' => column('K'),
                        'from' => column('A')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 12))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($this->path);
                $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
                $this->documentParsing();

        }

        //$this->save();
        return $this->items;
    }

    function documentParsing($path=null){
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
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost2']))){
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
                    if(count($rows) == 1 ){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){$header = $row; }else {$header = $this->filter['header'].' '.$row;}
                        $new_header=true;
                        continue;
                    }
                    if($this->filter['blockheader'] && in_array($num_row, array($this->filter['horizontal']['from']['numeric'])))$header = $this->filter['header'].' '.$row;;
                    if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric'] +1) $name =str_replace('ВГП, ЭСВ','',$header);
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