<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
class CPar1000met extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        '1000met' => 'Союзстальторг'
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
        //$this->document_extended = '.html';
        $this->home_url = 'http://1000met.ru/catalog/polosa';
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = false;
        $this->document_urls = $this->getUrl();
        $this->price_id = 8438003;
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
        foreach($this->document_list as $path){
            $this->documentParsing($path);
            //unlink($path);
        }
        $this->save();
        $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
        $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
        //$this->save();
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
        $parse = str_get_html(file_get_contents($path));
        $tables = $parse->find('table.table');
        if(!empty($tables)){
            $result = array();
            foreach ($tables as $table) {
                $tbody = $table->find("tbody tr");
                foreach($tbody as $tr){
                    $temprorary = array();
                    $temp = array();
                    $temp["cost"] = 'notcost';
                    /*foreach($tr->find('td span.price') as $span){
                        //p($span->innertext);
                        //echo iconv('cp1251','utf-8',$td->innertext);
                        $temp['cost'] =  trim(preg_replace('/'.chr(160).'/', '', $span->innertext));
                        p($temp['cost']);
                    }*/
                    foreach($tr->find('td') as $td){
                        //p($td->innertext);
                        //echo iconv('cp1251','utf-8',$td->innertext);
                        if($td === end($tr->find('td'))){
                            $temp['cost'] = trim(preg_replace('/'.chr(160).'/', '', $td->innertext));
                        }else{
                            $temp['name'][] =  trim(str_replace('&nbsp;', ' ', $td->innertext));
                        }
                    }
                    if(!is_numeric($temp['cost']) || $temp['cost'] == 0){
                        //p($temp['cost']);
                        continue;
                    }
                    $temp['name'] = implode(' ',$temp['name']);
                    /*if(empty($temp)) continue;
                    if(empty($temp['name'])){
                        continue;
                    }*/
                    $temp['name'] = preg_replace('/\s+/i',' ', $temp['name']);
                    $temp['name'] = str_replace(';','', $temp['name']);
                    //$temp['cost'] = implode(';',$temp['cost']);
                    $result[] = $temp;
                }
            }
            $this->items = array_merge($this->items,$result);
        }
        $parse->clear();
        unset($parse);
    }
    public function getUrl(){
        $cookie = tempnam ("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt($ch, CURLOPT_URL, $this->home_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt( $ch, CURLOPT_MAXREDIRS, 100 );
        $data = curl_exec($ch);
        curl_close($ch);
        $parse = str_get_html($data);
        $info_all = $parse->find('li.current ul li a');
        $href = array();
        foreach($info_all as $info_first){
            //$info_second = $info_first->find('a',0);
            //$links = file_get_html('http://1000met.ru'.$info_second->href);
            /*$low_links = $links->find('div.metal_tabs div a');
            if(!empty($low_links)){
                foreach($low_links as $low_link){
                    //$low_link->find('a',0);
                    $href[] = 'http://www.arielmetall.ru'.$low_link->href;
                }
                continue;
            }*/
            $href[] = 'http://1000met.ru/'.$info_first->href;
            //break;
        }
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        return $href;
    }

}
