<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

abstract class CPar23met extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $company_name;
    static $name_parser = array(
        '23met' => ''
    );
    function start(){
        $this->getDocuments();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
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
        $this->document_extended = '.html';
        $this->document_urls = array(
            8159619 => '23met.ru/plist/mechel',
            //8312374 => '23met.ru/plist/tis',
            //8312387 => '23met.ru/plist/regionstal',
            8160681 => '23met.ru/plist/zmkgost',
            //8180791 => '23met.ru/plist/mhs',
            //8312401 => '23met.ru/plist/arielmetall',
            8177515 => '23met.ru/plist/emg',
            //8176551 => '23met.ru/plist/avantsteel',
            8173908 => '23met.ru/plist/spectrstal',
            8173840 => '23met.ru/plist/apexpromsteel',
            8172587 => '23met.ru/plist/amp',
            //8172553 => '23met.ru/plist/kapitalmet',
            8172100 => '23met.ru/plist/stroyprofil',
            8167327 => '23met.ru/plist/craft',
            //8436983 => '23met.ru/plist/uraltrybostal',
            8166686 => '23met.ru/plist/mkpsm',
            8166659 => '23met.ru/plist/psm',
            8161648 => '23met.ru/plist/sktk',
            8159511 => '23met.ru/plist/foragrand',
            8159397 => '23met.ru/plist/verna',
            8157305 => '23met.ru/plist/mtk',
            8157115 => '23met.ru/plist/profil',
            8156957 => '23met.ru/plist/mosglavsnab',
            8176400 => '23met.ru/plist/kontinental',
            //8176099 => '23met.ru/plist/stalnoydom',
            7859165 => '23met.ru/plist/agrupp',
            8158485 => '23met.ru/plist/eamc'
        );
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = true;
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
            $this->documentParsing($path);
            $this->save();
            $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->document_urls[$this->price_id].')</h4>';
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
            if(!empty($this->to_save_new)){
                $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
            }
            //unlink($path);
        }
        //$this->save();
    }
    function getDocuments(){
        if(!empty($this->document_urls)){
            foreach($this->document_urls as $price_id => $url){
                $this->document_url = $url;
                $this->price_id = $price_id;
                $document_name = 'temp_'.md5(time()).'_'.$this->price_id.'_'.$this->document_extended;
                echo $document_name;
                $this->getDocument($document_name);
            }
        }
        return $this;
    }
    function documentParsing($path){
        $this->items = array();
        $parse = file_get_html($path);
        $table = $parse->find('table.tablesorter tbody tr');
        if(!empty($table)){
            $result = array();
            foreach($table as $tr){
                $temp = array();
                foreach($tr->find('td') as $td){
                    if(!preg_match('/span/i',$td->innertext)){
                        $temp['name'][] = !empty($td->innertext) ? strip_tags($td->innertext) : 0;
                    }
                    foreach($td->find('span') as $cost){
                        //if() $temp['cost'][] = (int) preg_replace('/\s+/iu','',strip_tags($cost->innertext));
                        $temp['cost'][] = !empty($cost->innertext) ? (int) preg_replace('/\s+/iu','',strip_tags($cost->innertext)) : '';
                    }

                }
                if(empty($temp)) continue;
                $temp['name'] = implode(' ',$temp['name']);
                $temp['name'] = preg_replace('/\s+/iu',' ', $temp['name']);
                //$temp['cost'] = implode(';',$temp['cost']);
                $result[] = $temp;
            }
            $this->items = $result;

            $match = array();
            preg_match('/(?<=_)[0-9]+(?=_)/', $path, $match);
            $this->price_id = current($match);
            $this->company_name = $parse->find('#page_title h1', 0)->innertext;
            $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.time().'.csv';
        }
        $parse->clear();
        unset($parse);
    }
    public function getUrl(){
        $parse = file_get_html('http://www.ktzholding.com/');
        $links = $parse->find('ul.mnu li a');
        foreach($links as $link){
            if(preg_match('/download/',$link->href)){
                return 'http://www.ktzholding.com/'.$link->href;
            }
        }
    }
}