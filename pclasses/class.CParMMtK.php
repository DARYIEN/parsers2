<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParMMtK extends CParMain{
    var $city_id;
    static $name_parser = array(
        'mmtk' => 'ММтК (Московская Металлоторговая Компания)'
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
        $this->document_url = $this->getUrl('http://mmtk-msk.ru/prices');
        //$this->document_url = 'http://178.63.70.28/pricelists/moskva/MMtK/848%D0%9A%D0%9F_2__%D1%81_20.01.2014.xls';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->price_id = 10707362;
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
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(0)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(1)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(2)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 4))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(4)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 5))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(5)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('J'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 4))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(6)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('J'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(7)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(8)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('C'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(9)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('D'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(10)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(11)->toArray();
            $this->documentParsing();

            $this->filter =  array(
                'horizontal' => array(
                    'to' => column('I'),
                    'from' => column('A')),
                'vertical' => array(
                    'to' => array(
                        'numeric' => null),
                    'from' => array(
                        'numeric' => 3))
            );
            $this->filter_subset = new MyReadFilter($this->filter);
            $this->head_non = true;
            $this->documentLoad($path);
            $this->sheet = $this->objPHPExcel->getSheet(12)->toArray();
            $this->documentParsing();
        }
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $header2 = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            //p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = round(str_replace(' ','',$row));
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        continue;
                    }
                    if($num_row == 2 ||$num_row == 6 ||$num_row == 7 ||$num_row == 8 ||$num_row == $this->filter['horizontal']['to']['numeric']-1){
                         continue;
                    }
                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if(count($rows) == 1){
                        $header = str_replace(array(
                            'с полимерным покрытием (цинк 120 г/кв.м.)',
                            'с полимерным покрытием (цинк 120 кл, 2 класса)',
                            'с полимерным покрытием (цинк 120 кл, 2 кл)',
                        ),array(
                            'Сэндвич-панели с минераловатным утеплителем из базальтового волокна с полимерным покрытием (цинк 120 г/кв.м.)',
                            'Сэндвич-панели с утеплителем из пенополистерола с полимерным покрытием (цинк 120 кл, 2 класса)',
                            'Сэндвич-панели с утеплителем из пенополистерола с полимерным покрытием (цинк 120 кл, 2 кл)',
                        ),$row);
                        continue;
                    }
                    if(!empty($header)&&$num_row == $this->filter['horizontal']['from']['numeric'] ){
                        $header2 = $header.' '.str_replace(array('Рулон','Zn','Sp','2 класс','80-120 класс'),'',$row);
                        $name = $header2;
                        continue;
                    }
                    //if($num_row == $this->filter['horizontal']['from']['numeric'] /*|| count($rows) == 3*/) $name =str_replace('Двутавры','Двутавр',$header);
                    if($num_row == $this->filter['horizontal']['from']['numeric']){
                        continue;
                    }
                    $name .= ' '.str_replace('Двутавр','',$row);
                }
            }
            if(!empty($name)&& !empty($cost)){
                $name = str_replace('  ',' ',$name);
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu',',', preg_replace('/\s+/iu',' ', preg_replace('/\"/iu','',$name)))), 'cost' => $cost);
            }
            $name = $header2;
            $cost = '';

        }
        //p($this->items);
    }

    public function getUrl($url){
        foreach(file_get_html($url)->find('body a') as $link){
            $href = $link->href;
            if(strripos($href,'.xls' )!==false){
                if(strripos($href, parse_url($url, PHP_URL_HOST)) == false){
                    $href = 'http://'.parse_url($url, PHP_URL_HOST).$href;
                }
                $hrefs[] = $href;
            }
        }
        //p($hrefs);
        $link=$hrefs[0];
        // die();
        return $link;
    }
}