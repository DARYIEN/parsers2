<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
class CParBetonTesla extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    public $temp = array();
    public $stringForFind = 'div.content table tbody tr';
    static $name_parser = array(
        'BetonTesla' => 'Бетон Тесла'
    );
    function start(){
        $this->getDocument();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
        return $mail;
    }
    function __construct(){
        //$this->iconv = false;
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
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = true;
        //$this->home_url = 'http://beton-tesla.ru/metalloprokat/';
        $this->document_url = 'http://beton-tesla.ru/metalloprokat/';
        $this->price_id = 11094962;
        //$this->iconv = true;
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
            $this->getTemp($path);
            //p($this->temp);
            //die();

            $this->documentParsing();
        }
        //p($this->items);
        //die();
        $this->save();
        $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->document_url.')</h4>';
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
    function getTemp($path){
        $this->temp = array();
        $parse = file_get_html($path);
        $table = $parse->find($this->stringForFind);
        if(!empty($table)){
            foreach($table as $tr){
                $temp = array();
                foreach($tr->find('td') as $td){
                    //p($span->innertext);
                    //echo iconv('cp1251','utf-8',$td->innertext);
                    $temp[] =  trim(preg_replace('/\&nbsp\;/', '', $td->plaintext));
                }
                $this->temp[] = $temp;
            }
        }
        $parse->clear();
        unset($parse);
        return $this;
        //die();
    }
    function documentParsing(){
        $result = array();
        $column_r = array();
        $header_r = '';
        $header_l = '';
        $column_l = array();
        foreach($this->temp as $items){
            if(empty($items)){
                continue;
            }
            //$temp = $this->prepare_item($items);
            //if(empty($temp)) continue;
            if(in_array(count($items),array(2,3))){
                switch(count($items)){
                    case 2:
                        $header_l = $items[0];
                        $header_r = $items[1];
                        break;
                    case 3:
                        if(is_numeric($items[2])){
                            $header_l = $items[0];
                            $column_r[] = array($header_r,$items[1],$items[2]);
                            continue;
                        }else{
                            $header_r = $items[2];
                            $column_l[] = array($header_l,$items[0],$items[1]);
                            continue;
                        }
                        break;
                }
            }
            $array_pieces = array_chunk($items,2);
            $column_r[] = array_merge(array($header_l),$array_pieces[0]);
            $column_l[] = array_merge(array($header_r),$array_pieces[1]);
        }
        //p($column_r);
        //p($column_l);
        foreach($column_l as $items){
            $temp = $this->prepare_item($items);
            if(empty($temp)) continue;
            $result[] = $temp;
        }
        foreach($column_r as $items){
            $temp = $this->prepare_item($items);
            if(empty($temp)) continue;
            $result[] = $temp;
        }
        $this->items = array_merge($this->items,$result);
    }
    public function prepare_item($items){
        if(count($items)<3) return array();
        $cost[] = (int) str_replace(array(' ','руб.'),'',array_pop($items));
        if(!is_numeric(end($cost)) || end($cost) == 0){
            return array();
        }
        $name = implode(' ',$items);
        if(empty($name) || empty($cost)){
            return array();
        }
        $temp['cost'] = $cost;
        $temp['name'] = preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', html_entity_decode($name)))));
        return $temp;
        //$temp['name'] = str_replace(';','', $temp['name']);
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
        $info_all = $parse->find('ul.menu li ul li ul li');
        $href = array();
        foreach($info_all as $info_first){
            $info_second = $info_first->find('a',0);
            $link = $info_second->href;
            if($link != '/'){
                $href[] = 'http://mtk-steel.ru'.$link;
            }
            //break;
        }
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        return $href;
    }

}