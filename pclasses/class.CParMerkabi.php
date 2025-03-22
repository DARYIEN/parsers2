<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
    class CParMerkabi extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    public $temp = array();
    public $stringForFind = 'table.tab-product tbody tr';
    static $name_parser = array(
        'apexmet' => 'APEX Металл'
    );
    function start(){
        $this->getDocuments();
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
        $this->home_url = 'http://apex-metal.ru';
        $this->document_urls = $this->getUrl();
        $this->price_id = 11074517;
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
        foreach($this->document_list as $number_list => $path){
            //$array_pieces = array();
            //$this->stringForFind = '';
            $this->filter = array(
                'propusk' => array(0),
                'cost' => array(3),
                'header' => array('Канат стальной ','Электроды ', 'Труба стальная электросварная ', 'Труба ВГП ', 'Круг ', 'Полоса ', 'Шестигранник ','Проволока ', 'Профнастил ', 'Труба ', 'Квадрат '),
            );
            $this->getTemp($path);
            $this->documentParsing($number_list);
        }
            //die();
        //p($this->items);
        //die();
        $this->save();
        $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
        $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
        //$this->save();
    }
    function getTemp($path){
        $this->temp = array();
        $parse = file_get_html($path);
        $table = $parse->find($this->stringForFind);
        if(!empty($table)){
            foreach($table as $tr){
                $temp = array();
                $key = 0;
                foreach($tr->find('td') as $td){
                    //p($span->innertext);
                    //echo iconv('cp1251','utf-8',$td->innertext);
                    if(in_array($key, $this->filter['propusk'])){
                        $key++;
                        continue;
                    }
                    $temp[] =  trim(preg_replace('/\&nbsp\;/', '', $td->plaintext));
                    $key++;
                }
                $this->temp[] = $temp;
            }
        }
        $parse->clear();
        unset($parse);
        return $this;
        //die();
    }
    function documentParsing($number_list = 0){
        $result = array();
        foreach($this->temp as $key => $items){
            //$temp = array();
            if(empty($items)){
                continue;
            }
            $temp = $this->prepare_item($items);
            if(empty($temp)) continue;
            if(in_array($number_list,range(9,28))) $temp['name'] = $this->filter['header'][0].$temp['name'];
            if(in_array($number_list,range(125,136))) $temp['name'] = $this->filter['header'][1].$temp['name'];
            if(in_array($number_list,array(98))) $temp['name'] = $this->filter['header'][2].$temp['name'];
            if(in_array($number_list,array(99))) $temp['name'] = $this->filter['header'][3].$temp['name'];
            if(in_array($number_list,range(45,52))) $temp['name'] = $this->filter['header'][4].$temp['name'];
            if(in_array($number_list,range(77,82))) $temp['name'] = $this->filter['header'][5].$temp['name'];
            if(in_array($number_list,range(121,124))) $temp['name'] = $this->filter['header'][6].$temp['name'];
            if(in_array($number_list,range(83,87))) $temp['name'] = $this->filter['header'][7].$temp['name'];
            if(in_array($number_list,range(89,91))) $temp['name'] = $this->filter['header'][8].$temp['name'];
            if(in_array($number_list,array(100))) $temp['name'] = $this->filter['header'][9].$temp['name'];
            if(in_array($number_list,range(30,36))) $temp['name'] = $this->filter['header'][10].$temp['name'];
            $result[] = $temp;
        }
        $this->items = array_merge($this->items,$result);
    }
    public function prepare_item($items){
        $cost[] = preg_replace('/\s+/iu',' ',str_replace(array(' ','руб.'),'',array_pop($items)));
        $cost = array_map(create_function('$v', 'return (int) $v;'), $cost);
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
        $info_all = $parse->find('div#vmenu ul li ul li');
        $href = array();
        foreach($info_all as $info_first){
            $info_second = $info_first->find('a',0);
            $link = $info_second->href;
            if($link != '/'){
                $href[] = 'http://apex-metal.ru'.$link;
            }
        }
        //$href[] = 'http://mtk-steel.ru/price/truba-bu-ceni.html';
        //$href[] = 'http://mtk-steel.ru/price/fitingi-ceni.html';
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        //p($href);
        //die();
        return $href;
    }

}