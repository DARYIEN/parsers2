<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParMTK extends CParMain{
    var $city_id;
    static $name_parser = array(
        'mtk' => 'Метизная Торговая Компания'
    );
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
        $this->document_url = 'http://www.mtk-fortuna.ru/price/moscow_price.xls';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 10683501;
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
                'dermolist'=>0,
                'horizontal' => array(
                    'to' => column('E'),
                    'from' => column('C')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 8))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'dermolist'=>1,
                'horizontal' => array(
                    'to' => column('F'),
                    'from' => column('C')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 8))
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
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
           // p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = Round(str_replace(' ','',$row));
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        continue;
                    }

                    if($num_row == 3){
                       // continue;
                    }
                    if(count($rows) == 1 && $num_row == $this->filter['horizontal']['from']['numeric']){
                        $header = $row;
                        continue;
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 ) $name =str_replace('ВГП, ЭСВ','',$header);
                    if(1 == $this->filter['dermolist'] && $num_row == $this->filter['horizontal']['to']['numeric']-1) {$name.='оцинкованные'; continue;}
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($cost)){
                $this->items[] = array('name' => str_replace("½","1/2",preg_replace('/\?/iu','', preg_replace('/;/iu',',', preg_replace('/\s+/iu',' ', $name)))), 'cost' => array($cost));
            }
            $name = '';
            $cost = '';

        }
       // p($this->items);
    }
}
class CParMTKNovgorod extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/gorky_price.xls';
        $this->price_id = 11007963;
        $this->document_name= 'MTKNovgorod'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKVoronej extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/voronezh_price.xls';
        $this->price_id = 11007965;
        $this->document_name= 'MTKVoronej'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKSaratov extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/saratov_price.xls';
        $this->price_id = 11007968;
        $this->document_name= 'MTKSaratov'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKKrasnodar extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/krasnodar_price.xls';
        $this->price_id = 11007969;
        $this->document_name= 'MTKKrasnodar'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKSamara extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/samara_price.xls';
        $this->price_id = 11007970;
        $this->document_name= 'MTKSamara'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKPerm extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/perm_price.xls';
        $this->price_id = 11007971;
        $this->document_name= 'MTKPerm'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKKazan extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/kazan_price.xls';
        $this->price_id = 11007972;
        $this->document_name= 'MTKKazan'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKVolgograd extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/saratov_price.xls';
        $this->price_id = 11007973;
        $this->document_name= 'MTKVolgograd'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}
class CParMTKSPB extends CParMTK{
    function __construct(){
        parent::__construct();
        $this->document_url = 'http://www.mtk-fortuna.ru/price/piter_price.xls';
        $this->price_id = 11007975;
        $this->document_name= 'MTKSPB'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}