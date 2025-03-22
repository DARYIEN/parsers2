<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
include_once ROOT . '/extension/phpQuery/phpQuery/phpQuery.php';
class CParlsst extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $company_name;
    static $name_parser = array(
        'lsst' => ''
    );
    function start(){
        $this->getDocuments();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
        return $mail;
    }
    function __construct(){
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
        $this->document_urls['truby_bu'] = 'https://www.lsst.ru/price-list/';
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
        foreach($this->document_list as $path){
            $this->filter = array('cost' => 2, 'hide' => array(1), 'coef' => 1, 'selector' => 'table.pricetable');
            $this->documentParsing($path);
            $this->save();
            $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
            if(!empty($this->to_save_new)){
                $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
            }
            //unlink($path);
        }
        //$this->save();
    }

    function documentParsing($path){
        $parse = phpQuery::newDocumentFileHTML($path);
        $tables = $parse->find($this->filter['selector']);
        if(!empty($tables)){
            $result = array();
            $els = pq($tables);
            foreach ($els as $el) {
                $el = pq($el);
                foreach ($el->find('tr') as $tr) {
                    //echo 1;
                    $name = '';
                    $costs = array();
                    $el_td = pq($tr)->find("td");
                    foreach ($el_td as $key => $value) {
                        $td = pq($value);
                        //echo $key."=>".$td->text()."<br />";
                        if ($td->text() == "Наименование") continue 2;
                        if (in_array($key, $this->filter['hide'])) continue;
                        if ($key == $this->filter['cost']) {
                            $costs = array();
                            $cost = preg_replace('~[^0-9\.]+~', '', $td->text());
                            $cost = str_replace(',', '.', $cost);
                            //echo "cost - - - ".$cost."<br />";
                            if (is_numeric($cost) && $cost != 0) {
                                $cost = str_replace('.', ',', $cost);
                                $costs[] = $cost * $this->filter['coef'];
                            }
                            continue;
                        }
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
                    if (isset($this->filter['dop'])) $name .= $this->filter['dop'];
                    $result[] = array('name' => $name, 'cost' => $costs,);
                }
            }
            $this->items = array_merge($this->items,$result);
            //p($this->items);
        }
        unset($parse);
        //die();
    }
}
