<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */
include_once ROOT . '/extension/phpQuery/phpQuery/phpQuery.php';
class CParArtMetallNew extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'artMetall' => 'artMetall'
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
        $this->author = 'Михаил';
        $this->document_urls['truby_bu'] = 'http://ametall.ru/catalog/truby-b-u?SHOWALL_1=1';
        $this->document_urls['balka_bu'] = 'http://ametall.ru/catalog/balka-b-u?SHOWALL_1=1';
        $this->document_urls['shveller_bu'] = 'http://ametall.ru/catalog/shveller-b-u';
        $this->home_url = current($this->document_urls);
        $this->price_id = 1;
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
        foreach($this->document_list as $key => $path){
            if($key == "truby_bu"){
                $this->filter = array('cost' => 9, 'hide' => array(0,1,6,8,10));
                $this->documentParsing($path);
            }

            if($key == "balka_bu"){
                $this->filter = array('cost' => 7, 'hide' => array(0,1,4,6,8));
                $this->documentParsing($path);
            }

            if($key == "shveller_bu"){
                $this->filter = array('cost' => 7, 'hide' => array(0,1,6,8));
                $this->documentParsing($path);
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
   /* function getDocuments(){
        if(!empty($this->document_urls)){
            foreach($this->document_urls as $url){
                $this->document_url = $url;
                //p($url);
                $this->getDocument();
            }
        }
        return $this;
    }*/
    function documentParsing($path){
	$parse = phpQuery::newDocumentFileHTML($path);
        $table = $parse->find('table#tblNeedsScrolling');
        if(!empty($table)){
            $result = array();
            $table_number  = 1;
            $el = pq($table);
            foreach ($el->find('tr') as $tr) {
                $name = '';
                $costs = array();
                $el_td = pq($tr)->find("td");
                foreach ($el_td as $key => $value) {
                    $td = pq($value);
                    if(in_array($key, $this->filter['hide'])) continue;
                    if ($key == $this->filter['cost']) {
                        $cost = str_replace(array(' ', '	', 'зат', 'р. зшт', 'р. зам', 'руб.', 'р.', '&#160;', '&nbsp;', chr(160), 'От', 'RUB'), '', $td->text());
			$cost = preg_replace( "/\r|\n/", "", $cost);
                        $cost = str_replace(',', '.', $cost);
                        if (is_numeric($cost) && $cost != 0) {
                            $cost = str_replace('.', ',', $cost);
                            $costs[] = $cost;
                        }
                        continue;
                    }
                    /*if ($key > count($tr->find('td')) - 3) continue;
                    if ($key == 3 && $header == 'Балка бу') continue;
                    if ($key == 0 && $header == 'Балка бу') continue;
                    if ($key == 0 && $header == 'Трубы бу (лежалые)') continue;
                    if ($key == 6 && !empty($td->plaintext) && $header == 'Трубы бу (лежалые)') {
                        //$name .= ' дл=' . $td->plaintext;
                        continue;
                    }
                    if ($key == 8 && !empty($td->plaintext) && $header == 'Трубы бу (лежалые)') {
                        //$name .= ' дл=' . $td->plaintext;
                        continue;
                    }
                    if ($key == 5 && !empty($td->plaintext) && $header == 'Балка бу') {
                        //$name .= ' дл=' . $td->plaintext;
                        continue;
                    }

                    if ($key == 7 && !empty($td->plaintext) && $header == 'Балка бу') {
                        //$name .= ' дл=' . $td->plaintext;
                        continue;
                    }*/
                    $name .= ' ' . $td->text();
                }
                if (empty($costs)) continue;
                $name = str_replace('&ndash', '-', $name);
                $name = str_replace(array('&nbsp;', '&#160;'), ' ', $name);
                //$name = strip_tags($name);
                //$name = html_entity_decode($name);
                $name = trim($name, '&nbsp;');
                $name = trim($name);
                $name = str_replace(';', '!', $name);
                $name = str_replace('×', 'х', $name);
                $name = str_replace('б/у', 'б/у ', $name);
                $name = preg_replace('/\s+/iu', ' ', $name);
                $name = preg_replace('/[\(\)\[\]\'"]/iu', ' ', $name);
                $result[] = array('name' => $name, 'cost' => $costs,);
            }
            $this->items = array_merge($this->items,$result);
        }
        unset($parse);
        //die();
    }

    public function getUrl($url){
        $parse = file_get_html($url);
        $info_all = $parse->find('div.lmenu_in ul li a');
        $hrefs = array();
        foreach($info_all as $info_first){
            //$info_second = $info_first->value;
            $link = $info_first->href;
            if($link != '/'){
                if(strripos($link, parse_url($url, PHP_URL_HOST)) === false){
                    $link = 'http://'.parse_url($url, PHP_URL_HOST).$link;
                }
                $hrefs[] = $link;
            }
        }
        foreach($hrefs as $href){
            $parse = file_get_html($href);
            $info_all = $parse->find('.it_category a');
            foreach($info_all as $info_first){
                //$info_second = $info_first->value;
                $link = $info_first->href;
                if($link != '/'){
                    if(strripos($link, parse_url($url, PHP_URL_HOST)) === false){
                        $link = 'http://'.parse_url($url, PHP_URL_HOST).$link;
                    }
                    $hrefs[] = $link;
                }
            }
        }
        //p($hrefs);
        //die();
        return $hrefs;
    }

}
