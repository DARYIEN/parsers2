<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
class CParTruboMashServisMoscow extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    public $temp = array();
    public $stringForFind = 'table.table1 tbody tr';
    static $name_parser = array(
        'TruboMashServis' => 'Трубомашсервис'
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



        $this->document_urls["one"] = 'http://t-m-s.ru/price.php';
        $this->document_urls["two"] = 'http://t-m-s.ru/prices.php';
        $this->document_urls["three"] = 'http://t-m-s.ru/prays-profil-truby';
	$this->document_urls["four"] = 'http://t-m-s.ru/prays-na-metalloprokat';
        $this->price_id = 10512488;
        $this->filter = array(
            "one" => array(
                'propusk' => array(4,5,7),
                'cost' => array(6),
                'name' => 'Труба '

            ),
            "two" => array(
                'propusk' => array(5),
                'cost' => array(4),
                'name' => 'Труба котельная '
            ),
            "three" => array(
                'propusk' => array(4,6),
                'cost' => array(5,7),
                'name' => 'Труба нержавеющая '
            ),
	    "four" => array(
		'propusk' => array(4),
		'cost' => array(3),
		'name' => ''
	    )
            //'header' => array('Канат стальной',
        );
        //$this->iconv = false;
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

            $this->getTemp($path,$number_list);
            $this->documentParsing($number_list);
        }
            //die();
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
    function getTemp($path,$filterKey){
        $this->temp = array();
        $parse = file_get_html($path);
        $table = $parse->find($this->stringForFind);
        if(!empty($table)){
            foreach($table as $tr){
                $temp = array();
                $key = 0;
                foreach($tr->find('td') as $td){
                    if(in_array($key, $this->filter[$filterKey]['propusk']) || $key>7){
                        $key++;
                        continue;
                    }
                    //$temp[] = iconv("windows-1251","utf-8",$td->plaintext);
                    $temp[$key] = $td->plaintext;
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
            $temp = $this->prepare_item($items,$number_list);
            if(empty($temp)) continue;

            $result[] = $temp;
        }
        $this->items = array_merge($this->items,$result);
    }
    public function prepare_item($items,$filterKey){
        if(!empty($this->filter[$filterKey]['cost'])){
            foreach ($items as $key => $item) {
                if(in_array($key, $this->filter[$filterKey]['cost'])){
                    $cost[] = $item;
                    unset($items[$key]);
                }
            }
        }else{
            $cost[] = preg_replace('/\s+/iu',' ',str_replace(array(' ','руб.'),'',array_pop($items)));
        }
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
        $temp['name'] = $this->filter[$filterKey]['name'] ? $this->filter[$filterKey]['name'].$temp['name'] : $temp['name'];
        return $temp;
        //$temp['name'] = str_replace(';','', $temp['name']);
    }
}
  class CParTruboMashServisEburg extends CParTruboMashServisMoscow{
      function __construct(){
          parent::$name_parser = array(
              'TruboMashServis' => 'Трубомашсервис'
          );
          parent::__construct();
          $this->price_id = 10140184;
          $this->document_url = 'http://www.t-m-s.ru/stock_e.php';
          $this->filter = array(
              'propusk' => array(5),
              'cost' => array(3),
              //'header' => array('Канат стальной',
          );
      }
  }
  class CParTruboMashServisBel extends CParTruboMashServisMoscow{
      function __construct(){
          parent::$name_parser = array(
              'trubomashservis' => 'Трубомашсервис'
          );
          parent::__construct();
          $this->price_id = 10140184;
          $this->document_url = 'http://www.t-m-s.ru/stock_b.php';
          $this->filter = array(
              'propusk' => array(5),
              'cost' => array(3),
              //'header' => array('Канат стальной',
          );
      }
  }
