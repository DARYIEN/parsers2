<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParAgruppMetalloprokat extends CParMain{
    var $city_id;
    public $sheet = array();
    static $name_parser = array(
        'agrupp' => 'Агрупп'
    );
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
        $this->document_name = current(rus2translit(array_values(self::$name_parser))).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        //$this->document_urls['spirtyaga'] = 'http://178.63.70.28/pricelists/moskva/A-grupp/A-grupp.xls';
        $this->document_urls['spirtyaga'] = $this->getUrl();
        //$this->document_urls['CParAgruppProfilTrub'] = 'http://178.63.70.28/pricelists/moskva/A-grupp/kopiya_profilnaya_truba.xls';
        //$this->document_urls['CParAgruppMetalloprokat'] = 'http://178.63.70.28/pricelists/moskva/A-grupp/kopiya_metalloprokat.xls';
        //$this->document_urls['CParAgruppPloskyProkat'] = 'http://178.63.70.28/pricelists/moskva/A-grupp/listyi.xls';
        //$this->document_urls['CParAgruppDetaliTrub'] = 'http://178.63.70.28/pricelists/moskva/A-grupp/kopiya_prays_dt_i_tpa.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 10631034;
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
            if($key == 'spirtyaga'){
                $this->filter =  array(
                    'propusk' => array(5),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'horizontal' => array(
                        'to' => column('G'),
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
            } else if($key == 'nerj'){


            }
            //$kid = new $key($path);
            //$this->items = array_merge($this->items,$kid->processParsing());
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
                    if($this->filter['header']&&$num_row == $this->filter['horizontal']['from']['numeric'] ) $name =str_replace('ВГП, ЭСВ','',$header);
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
    public function getUrl($_id = 0){
        $str = 'div.all-price div.download ul li a';
        $parse = file_get_html('http://www.agrupp.com/');
        $link = $parse->find($str, 1);
        return 'http://www.agrupp.com'.$link->href;
    }
}
class CParAgruppMetalloprokatSpb extends CParAgruppMetalloprokat{
    static $name_parser = array(
        'agrupp' => 'Агрупп Питер'
    );
    public function __construct(){
        parent::__construct();
        //$this->home_url = 'http://www.td-rtz.ru/price/all/all/tula/';
        $this->price_id = 8438124;
    }
    public function getUrl($_id = 0){
        return 'http://www.agrupp.com/xls.php?region_id=2&no_sheets=1';
    }
}

class CParAgrupp2pr extends CParAgruppMetalloprokat{
    static $name_parser = array(
        'agrupp' => 'Агрупп'
    );
    public function __construct(){
        parent::__construct();
    }
    function processParsing(){
        foreach($this->document_list as $key => $path){
            if($key == 'spirtyaga'){
                $this->filter =  array(
                    'propusk' => array(),
                    'cost1' => false,
                    'cost2' => 0,
                    'coef' => 1.02,
                    'end' => '',
                    'header' => true,
                    'skleyka' => false,
                    'dualheader'=>false,
                    'horizontal' => array(
                        'to' => column('F'),
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
            } else if($key == 'nerj'){


            }
            //$kid = new $key($path);
            //$this->items = array_merge($this->items,$kid->processParsing());
        }
        $this->save();
    }

}
class CParAgruppPloskyProkat extends CParMain{
    var $city_id;
    function __construct($path){
        $this->path = $path;
    }
    function processParsing(){
        $this->filter =  array(
            'horizontal' => array(
                'to' => array(
                    'numeric' => 1,
                    'char' => 'B'),
                'from' => array(
                    'numeric' => 0,
                    'char' => 'A')),
            'vertical' => array(
                'to' => array(
                    'numeric' => null),
                'from' => array(
                    'numeric' => 5))
        );
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        $this->documentLoad($this->path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();
        $this->filter =  array(
            'horizontal' => array(
                'to' => array(
                    'numeric' => 4,
                    'char' => 'E'),
                'from' => array(
                    'numeric' => 3,
                    'char' => 'D')),
            'vertical' => array(
                'to' => array(
                    'numeric' => null),
                'from' => array(
                    'numeric' => 5))
        );
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        $this->documentLoad($this->path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();
        return $this->items;
    }
    function documentParsing(){
        $name = '';
        $cost = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = (int) str_replace(' ','',preg_replace('/руб\./i', '',$row));
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        continue;
                    }
                    $name .= ' '.$row;
                }
            }

            if(!empty($name)&& !empty($cost)){
                $this->items[] = array('name' => preg_replace('/;/iu','!',preg_replace('/\s+/iu',' ', $name)), 'cost' => $cost);
            }
            $name = '';
            $cost = '';
        }
        //p($this->items);
        //die();
    }
}
class CParAgruppDetaliTrub extends CParAgruppPloskyProkat{
    function __construct($path){
        $this->path = $path;
    }
    function processParsing(){
        $this->filter =  array(
            'horizontal' => array(
                'to' => array(
                    'numeric' => 1,
                    'char' => 'B'),
                'from' => array(
                    'numeric' => 0,
                    'char' => 'A')),
            'vertical' => array(
                'to' => array(
                    'numeric' => null),
                'from' => array(
                    'numeric' => 6))
        );
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        $this->documentLoad($this->path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();

        $this->filter =  array(
            'horizontal' => array(
                'to' => array(
                    'numeric' => 3,
                    'char' => 'D'),
                'from' => array(
                    'numeric' => 2,
                    'char' => 'C')),
            'vertical' => array(
                'to' => array(
                    'numeric' => null),
                'from' => array(
                    'numeric' => 6))
        );
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        $this->documentLoad($this->path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();
        return $this->items;
    }

}
class CParAgruppProfilTrub extends CParAgruppMetalloprokat{
    public $range = array();

    function __construct($path){
        $this->path = $path;
    }
    function allLoad($path){
        $this->range['a'] = 8;
        $this->range['b'] = 64;
        $this->_allLoad($path);
        $this->range['a'] = 77;
        $this->range['b'] = 136;
        $this->_allLoad($path);
        $this->range['a'] = 149;
        $this->range['b'] = 207;
        $this->_allLoad($path);
        $this->range['a'] = 218;
        $this->range['b'] = 276;
        $this->_allLoad($path);
        $this->range['a'] = 290;
        $this->range['b'] = 346;
        $this->_allLoad($path);
    }
    function _allLoad($path){
        $this->filter[0] =  array(
            'horizontal' => array(
                'to' => array(
                    'numeric' => 1,
                    'char' => 'B'),
                'from' => array(
                    'numeric' => 0,
                    'char' => 'A')),
            'vertical' => array(
                'to' => array(
                    'numeric' => $this->range['b']),
                'from' => array(
                    'numeric' => $this->range['a']))
        );
        $this->filter_subset = new MyReadFilter($this->filter[0]);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = array_merge($this->sheet,$this->objPHPExcel->getSheet(0)->toArray());
        $this->filter[1] = $this->filter[0];
        $this->filter[1]['horizontal']['to']['char'] = 'E';
        $this->filter[1]['horizontal']['to']['numeric'] = 4;
        $this->filter[1]['horizontal']['from']['char'] = 'D';
        $this->filter[1]['horizontal']['from']['numeric'] = 3;
        $this->filter_subset = new MyReadFilter($this->filter[1]);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = array_merge($this->sheet,$this->objPHPExcel->getSheet(0)->toArray());
    }
    function processParsing(){
        $this->allLoad($this->path);
        //p($this->sheet);
        $this->documentParsing();
        //die();
        return $this->items;
    }
    function documentParsing(){
        $name = '';
        $cost = '';
        $header = '';
        $clear = true;
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            if(!empty($rows)){
                $cost = isset($rows[$this->filter[0]['horizontal']['to']['numeric']]) ? trim($rows[$this->filter[0]['horizontal']['to']['numeric']]) : '';
                $cost = isset($rows[$this->filter[1]['horizontal']['to']['numeric']]) ? trim($rows[$this->filter[1]['horizontal']['to']['numeric']]) : $cost;
                //p($cost);
                if(!is_numeric($cost) || empty($cost)){
                    //$name = '';
                    if($clear)
                    {
                        $header = '';
                        $clear = false;
                    }
                    $cost = null;
                    $header = isset($rows[$this->filter[0]['horizontal']['from']['numeric']]) ? $header.' '.$rows[$this->filter[0]['horizontal']['from']['numeric']] : $header;
                    $header = isset($rows[$this->filter[1]['horizontal']['from']['numeric']]) ? $header.' '.$rows[$this->filter[1]['horizontal']['from']['numeric']] : $header;
                    $header = trim($header);
                    continue;
                }else{
                    $clear = true;
                }

                foreach($rows as $num_row => $row){
                    if(!empty($row)){
                        if(in_array($num_row, array($this->filter[0]['horizontal']['to']['numeric'],$this->filter[1]['horizontal']['to']['numeric']))){
                            continue;
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
        //p($this->items);
        //die();
    }
}
class CParAgruppKruglieTrubi extends CParAgruppMetalloprokat{
    function __construct($path){
        $this->path = $path;
    }
    function allLoad($path){
        $this->range['a'] = 9;
        $this->range['b'] = 67;
        $this->_allLoad($path);
        $this->range['a'] = 82;
        $this->range['b'] = 126;
        $this->_allLoad($path);
        $this->range['a'] = 68;
        $this->range['b'] = 70;
        $this->_allLoadAdd($path);
    }
    function _allLoad($path){
        $this->filter =  array(
            'horizontal' => array(
                'to' => array(
                    'numeric' => 2,
                    'char' => 'C'),
                'from' => array(
                    'numeric' => 0,
                    'char' => 'A')
            ),
            'vertical' => array(
                'to' => array(
                    'numeric' => $this->range['b']),
                'from' => array(
                    'numeric' => $this->range['a'])
            )
        );
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();

        $this->filter['horizontal']['to']['char'] = 'G';
        $this->filter['horizontal']['to']['numeric'] = 6;
        $this->filter['horizontal']['from']['char'] = 'E';
        $this->filter['horizontal']['from']['numeric'] = 4;
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();

        $this->filter['horizontal']['to']['char'] = 'K';
        $this->filter['horizontal']['to']['numeric'] = 10;
        $this->filter['horizontal']['from']['char'] = 'I';
        $this->filter['horizontal']['from']['numeric'] = 8;
        $this->filter_subset = new MyReadFilter($this->filter);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
        $this->documentParsing();
    }
    function _allLoadAdd($path){
        $this->filter[0] =  array(
            'horizontal' => array(
                'to' => array(
                    'numeric' => 2,
                    'char' => 'C'),
                'from' => array(
                    'numeric' => 0,
                    'char' => 'A')),
            'vertical' => array(
                'to' => array(
                    'numeric' => $this->range['b']),
                'from' => array(
                    'numeric' => $this->range['a']))
        );

        $this->filter_subset = new MyReadFilter($this->filter[0]);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = array_merge($this->sheet,$this->objPHPExcel->getSheet(0)->toArray());

        $this->filter[1] = $this->filter[0];
        $this->filter[1]['horizontal']['to']['char'] = 'G';
        $this->filter[1]['horizontal']['to']['numeric'] = 6;
        $this->filter[1]['horizontal']['from']['char'] = 'E';
        $this->filter[1]['horizontal']['from']['numeric'] = 4;

        $this->filter_subset = new MyReadFilter($this->filter[1]);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = array_merge($this->sheet,$this->objPHPExcel->getSheet(0)->toArray());

        $this->filter[2] = $this->filter[0];
        $this->filter[2]['horizontal']['to']['char'] = 'K';
        $this->filter[2]['horizontal']['to']['numeric'] = 10;
        $this->filter[2]['horizontal']['from']['char'] = 'I';
        $this->filter[2]['horizontal']['from']['numeric'] = 8;

        $this->filter_subset = new MyReadFilter($this->filter[2]);
        $this->head_non = true;
        $this->documentLoad($path);
        $this->sheet = array_merge($this->sheet,$this->objPHPExcel->getSheet(0)->toArray());

        $this->documentParsingAdd();
    }
    function processParsing(){
        $this->allLoad($this->path);
        return $this->items;
    }
    function documentParsingAdd(){
        $name = '';
        $cost = '';
        $header = '';
        $clear = true;
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            if(!empty($rows)){
                $cost = isset($rows[$this->filter[0]['horizontal']['to']['numeric']]) ? (int) $rows[$this->filter[0]['horizontal']['to']['numeric']] : $cost;
                $cost = isset($rows[$this->filter[1]['horizontal']['to']['numeric']]) ? (int) $rows[$this->filter[1]['horizontal']['to']['numeric']] : $cost;
                $cost = isset($rows[$this->filter[2]['horizontal']['to']['numeric']]) ? (int) $rows[$this->filter[2]['horizontal']['to']['numeric']] : $cost;
                //p($cost);
                if(!is_numeric($cost) || empty($cost)){
                    //$name = '';
                    if($clear)
                    {
                        $header = '';
                        $clear = false;
                    }
                    $cost = null;
                    $header = isset($rows[$this->filter[0]['horizontal']['from']['numeric']]) ? $header.' '.$rows[$this->filter[0]['horizontal']['from']['numeric']] : $header;
                    $header = isset($rows[$this->filter[1]['horizontal']['from']['numeric']]) ? $header.' '.$rows[$this->filter[1]['horizontal']['from']['numeric']] : $header;
                    $header = isset($rows[$this->filter[2]['horizontal']['from']['numeric']]) ? $header.' '.$rows[$this->filter[2]['horizontal']['from']['numeric']] : $header;
                    $header = trim($header);
                    continue;
                }else{
                    $clear = true;
                }

                foreach($rows as $num_row => $row){
                    if(!empty($row)){
                        if(in_array($num_row, array($this->filter[0]['horizontal']['to']['numeric'],$this->filter[1]['horizontal']['to']['numeric'],$this->filter[2]['horizontal']['to']['numeric']))){
                            continue;
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
    }
}
