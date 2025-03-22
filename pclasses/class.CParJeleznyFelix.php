<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParJeleznyFelix extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'jeleznyfelix' => 'Железный Феликс'
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
        /*$this->document_urls = array(   'http://www.z-felix.ru/profnastil/s8.html',
                                        'http://www.z-felix.ru/profnastil/s10.html',
                                        'http://www.z-felix.ru/profnastil/s18.html',
                                        'http://www.z-felix.ru/profnastil/s21.html',
                                        'http://www.z-felix.ru/profnastil/s44.html',
                                        'http://www.z-felix.ru/profnastil/ns10.html',
                                        'http://www.z-felix.ru/profnastil/n57.html',
                                        'http://www.z-felix.ru/profnastil/n60.html',
                                        'http://www.z-felix.ru/profnastil/n75.html',

                                    );
        $this->home_url = current($this->document_urls);*/
        $this->home_url = 'http://www.z-felix.ru/sitemap.html';
        $this->document_urls = $this->getUrl();
        $this->document_urls[] = 'http://www.z-felix.ru/krug/';
        $this->document_urls[] = 'http://www.z-felix.ru/kvadrat/';
        $this->document_urls[] = 'http://www.z-felix.ru/polosa/';
        $this->price_id = 11116877;
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
        $page = trim($parse->find('h1',0)->plaintext);
        foreach($parse->find('table tbody tr') as $key => $row){
            $title = trim($parse->find('td.headtab',0)->plaintext);
            //p($title);
            foreach($row->find('td') as $td){
                $price[$key][]=str_replace('&nbsp;', '', $td->plaintext);
            }
        }
        $data=array('cost'=>count($price[3])-1);
        if(strripos($page,'Профнастил')!==false){
            $data=array(
                'propusk'=>4,
                'cost'=>5,
            );
        }
        if(strripos($page,'Свая')!==false){
            $data=array(
                'propusk'=>0,
                'cost'=>2,
            );
        }
        if(strripos($page,'Опоры граненые конические')!==false){
            $data=array(
                'propusk'=>4,6,
                'cost'=>5,
            );
        }
        if(strripos($page,'Трубы Б/У')!==false){
            $data=array(
                //'propusk'=>4,6,
                'cost'=>5,
            );
        }
        // p($price);
        if(!empty($price)){
            foreach($price as $row){
                $name='';
                foreach($row as $key=>$element){
                    //if(count($row==1)&&$key==0)$title=$element;
                    if(in_array(trim($element), array('тн'))){
                        continue;
                    }
                    if(!in_array($key,$data)) $name .=' '. $element;
                }
                $cost=str_replace(',','.',str_replace(array(' ','р.'),'',$row[$data['cost']]));
                if(!empty($name)&&!empty($cost)&&is_numeric($cost)){
                    $result[]=array('name'=>preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($title.' '.$name))))),'cost'=>array($cost),);
                }
            }
        }
        //p($result);
        if(!empty($result))$this->items = array_merge($this->items,$result);
        unset($result);
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
        $info_all = $parse->find('div.left_modules div.moduletable ul#menu_left li ul li');
        $href = array();
        foreach($info_all as $info_first){
            $info_second = $info_first->find('a',0);
            $link = $info_second->href;
            if($link != '/'){
                $href[] = 'http://www.z-felix.ru'.$link;
            }
        }
        //unset($href[100]);
        //unset($href[101]);
        //$href[] = 'http://mtk-steel.ru/price/truba-bu-ceni.html';
        //$href[] = 'http://mtk-steel.ru/price/fitingi-ceni.html';
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        //p($href);
        return $href;
    }

}