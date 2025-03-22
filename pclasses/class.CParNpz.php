<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */
include_once ROOT . '/extension/phpQuery/phpQuery/phpQuery.php';
class CParNpz extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'npz' => 'npz'
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
        $this->author = 'Феликс';
        $this->document_urls['all'] = 'http://ntpz.ru/?page_id=1090';
       /* $this->document_urls['list_rifl'] = 'http://www.metopttrade.ru/list-rifleniy-kvintet/';
        $this->document_urls['al_rulon'] = 'http://www.metopttrade.ru/aluminieviy-rulon/';
        $this->document_urls['al_plita'] = 'http://www.metopttrade.ru/aluminievaya-plita/';
        $this->document_urls['al_prut'] = 'http://www.metopttrade.ru/aluminieviy-prutok/';
        $this->document_urls['truba_pryam'] = 'http://www.metopttrade.ru/truba-aluminievaya-pryamougolnaya/';
        $this->document_urls['profil_alum'] = 'http://www.metopttrade.ru/profil-aluminieviy-ugolok/';
        $this->document_urls['truba_alum_kr'] = 'http://www.metopttrade.ru/truba-aluminievaya-kruglaya/';
        $this->document_urls['alum_polosa'] = 'http://www.metopttrade.ru/aluminievaya-polosa/';
        $this->document_urls['shveller_alu'] = 'http://www.metopttrade.ru/shveller-aluminieviy-p-obraznii-profil/';
        $this->document_urls['alum_t_obrz'] = 'http://www.metopttrade.ru/aluminieviy-t-obraznii-profil-tavr/';
        $this->document_urls['alum_shina_elekt'] = 'http://www.metopttrade.ru/aluminievaya-shina-elektrotehnicheskaya/';
        $this->document_urls['alum_profil'] = 'http://www.metopttrade.ru/aluminieviy-profil-milliken/';
        $this->document_urls['profil_nat_pot'] = 'http://www.metopttrade.ru/profil-dlya-natyazhnih-potolkov/';
        $this->document_urls['ohlad'] = 'http://www.metopttrade.ru/ohladiteli/';*/
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
            if($key == "all"){
                $this->filter = array('cost' => [1], 'hide' => [], 'coef' => 1, 'selector' => 'div#content', /*'count' => 5,*/ 'boundaries' => [0,1], 'begins' => 2, "header" => "Труба профильная ГОСТ 30245, 8639 ");
                $this->documentParsing($path);
                $this->filter = array('cost' => [4], 'hide' => [], 'coef' => 1, 'selector' => 'div#content', /*'count' => 5,*/ 'boundaries' => [3,4], 'begins' => 2, "header" => "Труба профильная ГОСТ 8645 ");
                $this->documentParsing($path);
            }

            /*if($key == "list_rifl"){
                $this->filter = array('cost' => [2], 'hide' => array(1,3), 'coef' => 1000, 'selector' => 'table.table-bordered');
                $this->documentParsing($path);
            }

            if($key == "al_rulon"){
                $this->filter = array('cost' => [1], 'hide' => array(), 'coef' => 1, 'selector' => 'table.table-bordered');
                $this->documentParsing($path);
            }

            if($key == "al_plita"){
                $this->filter = array('cost' =>[1,2], 'hide' => array(), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "al_prut"){
                $this->filter = array('cost' => [3,4], 'hide' => array(1,2), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "truba_pryam"){
                $this->filter = array('cost' => [4,5], 'hide' => array(2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "profil_alum"){
                $this->filter = array('cost' => [4,5], 'hide' => array(1,2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "truba_alum_kr"){
                $this->filter = array('cost' => [4,5], 'hide' => array(2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "alum_polosa"){
                $this->filter = array('cost' => [4,5], 'hide' => array(2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "shveller_alu"){
                $this->filter = array('cost' => [4,5], 'hide' => array(2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "alum_t_obrz"){
                $this->filter = array('cost' => [4,5], 'hide' => array(2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "alum_shina_elekt"){
                $this->filter = array('cost' => [4,5], 'hide' => array(2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }

            if($key == "profil_nat_pot"){
                $this->filter = array('cost' => [2], 'hide' => array(0), 'coef' => 1, 'selector' => 'td.center_r table', 'dop' => ' п.м.');
                $this->documentParsing($path);
            }

            if($key == "ohlad"){
                $this->filter = array('cost' => [4], 'hide' => array(0,2,3), 'coef' => 1000, 'selector' => 'td.center_r table');
                $this->documentParsing($path);
            }*/
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
        //echo $parse->charset;
        $div = $parse->find($this->filter['selector']);
        if(!empty($div)){
            //echo $p->text();
            $p = pq($div);
            foreach($p->find("table") as $el_tables){
                $table = pq($el_tables);
                $result = array();
                $el = pq($table);
                foreach ($el->find('tr') as $tr_key =>$tr) {
                    if($tr_key < $this->filter['begins']) continue;
                    $name = '';
                    $costs = array();
                    $el_td = pq($tr)->find("td");
                    //echo count($el_td)."<br />";
                    //if(count($el_td) != $this->filter["count"]) continue;
                    foreach ($el_td as $key => $value) {
                        $td = pq($value);
                       // echo iconv('CP1251','UTF-8',iconv('UTF-8','CP1252',$td->text()));
                        if($td->text()=="Наименование") continue 2;
                        if(in_array($key, $this->filter['hide'])) continue;
                        if($key < $this->filter['boundaries'][0] || $key > $this->filter['boundaries'][1]) continue;
                        if(in_array($key, $this->filter['cost'])) {
                            $cost = preg_replace('~[^0-9]+~','', $td->text());
                            //$cost = str_replace(',', '.', $cost);
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
                    //$name = iconv('CP1251','UTF-8',iconv('UTF-8','CP1252',$name));
                    $name = str_replace('&ndash', '-', $name);
                    $name = str_replace(array('&nbsp;', '&#160;'), ' ', $name);
                    //$name = strip_tags($name);
                    //$name = html_entity_decode($name);
                    $name = trim($name, '&nbsp;');
                    $name = trim($name);
                    $name = str_replace(';', '!', $name);
                    $name = str_replace('×', 'х', $name);
                    $name = str_replace('б/у', 'б/у ', $name);
                    if(isset($this->filter['header'])) $name = $this->filter['header'].' '.$name;
                    $name = preg_replace('/\s+/iu', ' ', $name);
                    $name = preg_replace('/[\(\)\[\]\'"]/iu', ' ', $name);
                    if(isset($this->filter['dop'])) $name .= $this->filter['dop'];
                    $result[] = array('name' => $name, 'cost' => $costs,);
                }
                //p($result);
                $this->items = array_merge($this->items,$result);
                //p($this->items);
            }
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
//        p($hrefs);
//        die();
        return $hrefs;
    }

}