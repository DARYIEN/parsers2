<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */
class CParArtMetall extends CParMain{
    var $city_id;
    static $name_parser = array(
        'artmetallsetka' => 'АртМеталл'
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
        //$this->document_urls['CParArtMetall'] = 'http://ametall.ru/bitrix/templates/ametall/images/setka_240214.xls';
        $this->document_urls = $this->getUrl();
        //$this->document_urls['CParArtMetallTrubaArmatura'] = 'http://ametall.ru/bitrix/templates/ametall/images/metalloprokat_240214.xls';
        //$this->document_urls['CParArtMetallTrubaBU'] = 'http://ametall.ru/bitrix/templates/ametall/images/truba_bu_240214.xls';
        //$this->document_urls['CParArtMetallTrubaNew'] = 'http://ametall.ru/bitrix/templates/ametall/images/truba_240214.xls';
        //$this->coef = 1000;
        $this->dual_cost = true;
        $this->price_id = 10664809;
    }
    function getDocuments(){
        if(!empty($this->document_urls)){
            //echo count($this->document_urls );
            foreach($this->document_urls as $key => $url){
                //echo $key;
                $this->document_url = $url;
                //$this->price_id = $price_id;
                $document_name = 'temp_'.md5(time()).'_'.$key.'_'.$this->document_extended;
                //echo $document_name.'<br />';
                $this->getDocument($document_name,$key);
            }
        }
        return $this;
    }
    function formDirsArray(){
        $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
        return $this;
    }
    function processParsing(){
        //p($this->document_list);
        foreach($this->document_list as $key => $path){
            if($key == 'CParArtMetall'){
                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('C'),
                        'from' => column('B')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();

                $this->filter =  array(
                    'horizontal' => array(
                        'to' => column('H'),
                        'from' => column('G')),
                    'vertical' => array(
                        'to' => array(
                            'numeric' => null),
                        'from' => array(
                            'numeric' => 13))
                );
                $this->filter_subset = new MyReadFilter($this->filter);
                $this->head_non = true;
                $this->documentLoad($path);
                $this->sheet = $this->objPHPExcel->getSheet(3)->toArray();
                $this->documentParsing();
                continue;
            }
            $kid = new $key($path);
            $this->items = array_merge($this->items,$kid->processParsing());
        }
        //p($this->items);
        $this->save();
    }

    public function documentParsing(){
        $name = '';
        $cost = '';
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
           // p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric']))){
                        $cost = (int) str_replace(' ','',$row);
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        $costs[]=$cost;
                        continue;
                    }


                    if($num_row == 3){
                       // continue;
                    }
                    if(in_array($row, array('т', 'шт'))){
                        continue;
                    }
                    if(count($rows) == 1){
                        $header = $row;
                        continue;
                    }else if(count($rows) == 0){
                        $header = '';
                    }
                    if($row == '20х20х1,4'){
                        $header = 'Сетка плетеная неоцинкованная "рабица" (в рулонах)';
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric'] ) $name =str_replace('Балка, ст.3сп','',$header);

                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/\?/iu','', preg_replace('/;/iu',',', preg_replace('/\s+/iu',' ', preg_replace('/"/iu','',$name)))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);

        }
        //return $this->items;
    }
    public function getUrl(){
        preg_match_all('~href=\W\S+.xlsx\W~', file_get_html('http://ametall.ru/setka.php')->find('body',0)->innertext, $out);
        $links['CParArtMetall']='http://ametall.ru'.$out[0][0];
       // p($out);
        preg_match_all('~href=\W\S+.xls\W~', file_get_html('http://ametall.ru/production/armback.php')->find('body',0)->innertext, $out);
        $links['CParArtMetallTrubaArmatura']='http://ametall.ru'.$out[0][0];
        //p($out);
        preg_match_all('~href=\W\S+.xlsx\W~', file_get_html('http://ametall.ru/production/tubes/truby-bu.php')->find('body',0)->innertext, $out);
        $links['CParArtMetallTrubaBU']='http://ametall.ru'.$out[0][0];
        //p($out);
        preg_match_all('~href=\W\S+.xls\W~', file_get_html('http://ametall.ru/production/tubes.php')->find('body',0)->innertext, $out);
        $links['CParArtMetallTrubaNew']='http://ametall.ru'.$out[0][0];
        //p($out);
        //p(str_replace(array('href=','"',"'"), '', $links));
        //die();
        return str_replace(array('href=','"',"'"), '', $links);
    }
}
class CParArtMetallJD extends CParArtMetall{
    function __construct(){
        parent::__construct();
        $this->price_id = 13351542;
        $this->document_name= 'ArtMetallJD'.'_'.date('d-m-Y', time()).'_'.time().'.csv';
    }
}