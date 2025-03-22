<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
class CParSksGkl extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    public $temp = array();
    public $stringForFind = 'div.article table tbody tr';
    static $name_parser = array(
        'sksgk' => 'СКС ГК'
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
        $this->home_url = 'http://sksmetall.ru/metalloprokat/balka-bu';
        $this->company_name = current(array_values(self::$name_parser)).' '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = true;
        $this->document_urls = $this->getUrl();
        $this->price_id = 12199685;
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
        foreach($this->document_list as $key => $path){
            $this->getTemp($path);
            switch($key){
                case 0:
                    $this->filter = array(
                        'propusk' => array(4),
                        'cost' => array(3),
                    );
                    $this->documentParsing();
                    break;
                case 2:
                    $this->filter = array(
                        'propusk' => array(5),
                        'cost' => array(4),
                    );
                    $this->documentParsing();
                    break;
                default:
                    $this->documentParsingAlt();
                    break;

            }
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
    function getTemp($path){
        $this->temp = array();
        $parse = file_get_html($path);
        $table = $parse->find($this->stringForFind);
        if(!empty($table)){
            foreach($table as $tr){
                $temp = array();
                foreach($tr->find('td') as $td){
                    //echo iconv('cp1251','utf-8',$td->innertext);
                    $temp[] =  trim(preg_replace('/\s+/',' ',preg_replace('/&nbsp;/', '', $td->plaintext)));
                }
                $this->temp[] = $temp;
            }
        }
        $parse->clear();
        unset($parse);
        //p($this->temp);
        return $this;
        //die();
    }
    function documentParsing(){
        $result = array();
        foreach($this->temp as $items){
            $temp = array();
            $name = '';
            $cost = array();
                //p($items);
            foreach($items as $key => $item){
                if(empty($item)){
                    continue;
                }
                if(!empty($this->filter['cost']) && in_array($key, $this->filter['cost'])){
                    $cost[] = (int) $item;
                    if(!is_numeric(end($cost)) || end($cost) == 0){
                        continue 2;
                    }
                    continue;
                }
                if(in_array($key, $this->filter['propusk'])){
                    continue;
                }
                $name .= ' '.$item;
            }
            if(empty($name) || empty($cost)){
                continue;
            }
            $temp['cost'] = $cost;
            $temp['name'] = preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', html_entity_decode($name)))));
            //$temp['name'] = str_replace(';','', $temp['name']);

            $result[] = $temp;
        }
        $this->items = array_merge($this->items,$result);
    }

    function documentParsingAlt(){
        $result = array();
        foreach($this->temp as $items){
            $temp = array();
            $cost = array();
                //p($items);
            /*foreach($items as $key => $item){
                if(empty($item)){
                    continue;
                }
                if(!empty($this->filter['cost']) && in_array($key, $this->filter['cost'])){
                    $cost[] = (int) preg_replace('/\s+/iu','',$item);
                    if(!is_numeric(end($cost)) || end($cost) == 0){
                        continue 2;
                    }
                    continue;
                }
                if(in_array($key, $this->filter['propusk'])){
                    continue;
                }
                $name .= ' '.$item;
            }*/
            $cost[] = (int) preg_replace('/\s+/iu','',array_pop($items));
            if(!is_numeric(end($cost)) || end($cost) == 0){
                continue;
            }
            $name = implode(' ', $items);
            if(empty($name) || empty($cost)){
                continue;
            }
            $temp['cost'] = $cost;
            $temp['name'] = preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ', html_entity_decode($name)))));
            //$temp['name'] = str_replace(';','', $temp['name']);

            $result[] = $temp;
        }
        $this->items = array_merge($this->items,$result);
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
        $info_all = $parse->find('ul.vmenu li a');
        $href = array();
        $i = 0;
        foreach($info_all as $info_first){
            $i++;
            echo $i.$info_first->href;
            if($i==4 || $i == 19) continue;
            if($i==23) break;
            $href[] = 'http://sksmetall.ru/'.$info_first->href;
        }
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        //p($href);
        return $href;
    }

}