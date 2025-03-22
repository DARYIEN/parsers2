<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParProfMetComolectWeb extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'profmetcomolect' => 'ПРОФ МЕТ КОМПЛЕКТ'
    );
    function start(){
        $this->getDocuments();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
        return $mail;
    }
    function __construct(){
        $this->iconv = false;
        $this->items = array();
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }
        $this->company_name = current(array_values(self::$name_parser)).' '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        // $this->home_url = 'http://www.constali.ru/armatura-gladkaya-a1';
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = true;
        $this->document_urls = array(   'http://profmetcomplekt.ru/profnastil/sendvich_paneli/',
                                        'http://profmetcomplekt.ru/metalloprokat/armatura/',
                                        'http://profmetcomplekt.ru/metalloprokat/balka/',
                                        'http://profmetcomplekt.ru/metalloprokat/Shveller/',
                                        'http://profmetcomplekt.ru/metalloprokat/ugolok/',
                                        'http://profmetcomplekt.ru/metalloprokat/ugolok/',
                                        'http://profmetcomplekt.ru/metalloprokat/polosa/',
                                        'http://profmetcomplekt.ru/metalloprokat/list/goryachekatany/',
                                        'http://profmetcomplekt.ru/metalloprokat/list/Holodnokatany/',
                                        'http://profmetcomplekt.ru/metalloprokat/list/rifleniy/',
                                        'http://profmetcomplekt.ru/metalloprokat/list/list_otsinkovanniy_v_pachkah/',
                                        'http://profmetcomplekt.ru/metalloprokat/truba_profilnaya/kvadratnaya/',
                                        'http://profmetcomplekt.ru/metalloprokat/truba_profilnaya/pryamougolnaya/',
                                        'http://profmetcomplekt.ru/metalloprokat/truba_kruglaya/truba_e_s/',
                                        'http://profmetcomplekt.ru/metalloprokat/truba_kruglaya/truba_vgp/',
                                        'http://profmetcomplekt.ru/metalloprokat/truba_kruglaya/truba_vgp_otsinkovannaya/',
                                        'http://profmetcomplekt.ru/metalloprokat/katanka/',
                                        'http://profmetcomplekt.ru/profnastil/samorezi/',
                                        'http://profmetcomplekt.ru/profnastil/otsinkovanniy/',
                                        'http://profmetcomplekt.ru/profnastil/okrashenniy/',
                                    );
        $this->home_url = current($this->document_urls);
        $this->price_id = 8438017;
        $this->iconv = true;
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
        foreach($this->document_list as $path){
            $this->documentParsing($path);
            //unlink($path);
        }
        //$kid = new CParProfMetComplect;
        //$this->items = array_merge($this->items,$kid->processParsing());
        $this->save();
        $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
        $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
        //$this->save();
        //return $this->items;
    }
    function getDocuments(){
        if(!empty($this->document_urls)){
            foreach($this->document_urls as $url){
                $this->document_url = $url;
                //p($url);
                $this->getDocument();
            }
        }
        return $this;
    }
    function documentParsing($path){
        $parse = file_get_html($path);
        //$title = trim($parse->find('h1',0)->plaintext);
        $conf=array();
       // p($title);
            foreach($parse->find('table.item_table tr.item_tbl_body') as $key => $row){
                foreach($row->find('td') as $td){
                    $price[$key][]=$td->plaintext;
                    foreach($td->find('option') as $opt){
                        if(!empty($opt->plaintext)&&!empty($opt->price))$conf[]=array('size'=>$opt->plaintext, 'cost'=>$opt->price,);
                    }
                    $price[$key]['conf']=$conf;
                }
                $conf=array();
            }
            foreach($price as $row){
                //p($price);
                if(!empty($row['conf'])){
                    foreach($row['conf'] as $inem){
                        $result[]=array('name'=>$row[1].' '.$inem['size'],'cost'=>array(round($inem['cost'])),);
                    }
                }else{
                    preg_match('/\s*\S+$/iu', trim($row[5]), $matches);
                    if($matches[0]<100){
                        $result[]=array('name'=>str_replace('&#216;','',$row[1]).'тыс.шт','cost'=>array(1000*$matches[0]),);
                    }else{
                        $result[]=array('name'=>str_replace('&#216;','',$row[1]),'cost'=>array(str_replace('.',',',$matches[0])),);
                    }
                }
            }
        $this->items = array_merge($this->items,$result);
        $parse->clear();
        unset($parse);
    }
}