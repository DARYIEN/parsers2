<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
include_once ROOT . '/extension/phpQuery/phpQuery/phpQuery.php';
abstract class CParBrokinvest extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $company_name;
    static $name_parser = array(
        'Brokinvest' => ''
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
        $this->company_name = 'БрокИнвест '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.$this->company_name.'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.time().'.csv';
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
            $this->filter = array('cost' => 1, 'hide' => array(), 'coef' => 1, 'selector' => 'div.container table');
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
                $this->document_url = "http://www.brokinvest.ru/catalog/price/";
                $this->price_id = $price_id;
                $document_name = 'temp_'.md5(time()).'_'.$this->price_id.'_'.$this->document_extended;
                //echo $document_name;
                $this->getDocument($document_name);
            }
        }
        return $this;
    }
    function documentParsing($path){
        $parse = phpQuery::newDocumentFileHTML($path);
        $table = $parse->find($this->filter['selector']);
        if(!empty($table)){
            $result = array();
            $table_number  = 1;
            $el = pq($table);
            foreach ($el->find('tr') as $tr) {
                //echo 1;
                $name = '';
                $costs = array();
                $el_td = pq($tr)->find("td");
                foreach ($el_td as $key => $value) {
                    $td = pq($value);
                    //echo $key."=>".$td->text()."<br />";
                    if($td->text()=="Наименование") continue 2;
                    if(in_array($key, $this->filter['hide'])) continue;
                    if ($key == $this->filter['cost']) {
                        $costs = array();
                        $cost = preg_replace('~[^0-9]+~','', $td->text());
                        $cost = str_replace(',', '.', $cost);
                        //echo "cost - - - ".$cost."<br />";
                        if (is_numeric($cost) && $cost != 0) {
                            $cost = str_replace('.', ',', $cost);
                            $costs[] = $cost*$this->filter['coef'];
                        }
                        //print_r($costs);
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
                if(isset($this->filter['dop'])) $name .= $this->filter['dop'];
                $result[] = array('name' => $name, 'cost' => $costs,);
            }
            $this->items = array_merge($this->items,$result);
            //p($this->items);
        }
        unset($parse);
        //die();
    }
}