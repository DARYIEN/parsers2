<?php
class CParUniYmlEvraz extends CParMain{
    static $name_parser = array('YmlUni' => 'UniYml');
    public $xml;
    public function __construct(){
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.yml';
        $this->document_name = current(array_keys(self::$name_parser)).'_'.str_replace(array('/','\/'),'_',CParMain::$sergeyParserPath).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->document_urls['main'] = CParMain::$sergeyParserPath;
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 1;
        $this->author = 'Сергей';
        $this->price_type = 'web';
    }
    public function formDirsArray(){
        $this->dirArray['root'] = '/files/'.current(array_keys($this->cities_list[$this->city_id])).'/'.current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'].'/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'].'/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'].'/temporary';
        return $this;
    }

    public function start(){
        $this->getDocuments()->processParsing();
        return true;
    }

    public function processParsing(){
        foreach($this->document_list as $key => $path){
            if($key == 'main'){
                $this->documentLoad($path);
                //print_r($this->xml);
                //print_r($this->xml->shop->offers->offer[0]->url);
                $item = array();
                foreach($this->xml->shop->offers->offer as $offer){
                    $item["name"] = $offer->name;
                    if (empty(end($offer->price3))) {
                        $item["cost"] = array(end($offer->price1), end($offer->price2));
                    } else {
                        $item["cost"] = array(end($offer->price1), end($offer->price3));
                    }
                    $item["picture"] = (string)$offer->picture;
                    $this->items[] = $item;
                    $item = array();
                }
            }
        }
        $this->save();
    }

    public function documentLoad($path, $input_file_type = null){
        $this->xml =  simplexml_load_file($path);
    }
}