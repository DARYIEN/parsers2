<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
class CParChermet extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'chermet' => ''
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
        $this->company_name = 'Чермет '.current(array_values($this->cities_list[$this->city_id]));
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';

        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = false;
        $this->document_urls = $this->getUrl();
        $this->price_id = 9123;
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
                //$this->price_id = $price_id;
                //$document_name = 'temp_'.md5(time()).'_'.$this->document_extended;
                //echo $document_name;
                $this->getDocument();
            }
        }
        return $this;
    }
    function documentParsing($path){
        //gjy$this->items = array();
        $parse = file_get_html($path);
        $table = $parse->find('div.catalog-section table.table tbody tr');
        if(!empty($table)){
            $result = array();
            foreach($table as $tr){
                $temp = array();
                $i = 0;
                foreach($tr->find('td[valign=top]') as $td){
                    //echo mb_detect_encoding($td->innertext);
                    if(preg_match('/(.*?)<\/a>/', $td->innertext)){
                        $temp['name'][] = strip_tags(iconv('utf-8', 'cp1251', preg_replace('/;/i','', $td->innertext)));
                        continue;
                    }
                    //$temp['name'][] = !empty($td->innertext) ? iconv('cp1251', 'utf-8', preg_replace('/;/i','', $td->innertext)) : '';
                    $temp['name'][] = !empty($td->innertext) ? $td->innertext : '';
                }
                foreach($tr->find('td.table-price span') as $span){
                    $temp['cost'][] = !empty($span->{'data-price'}) ? $span->{'data-price'} : '';
                }
                //p($govnokod);
                /*
                for($i = 0; $i<=count($govnokod); $i++){
                    if(in_array($i, array(count($govnokod)-1,count($govnokod)-2,count($govnokod)-3))){
                        if(empty($temp['cost'])){
                            $temp['cost'] = !empty($govnokod[$i]) ? preg_replace('/(\s|'.chr(160).')/', '', strip_tags($govnokod[$i])) : 0;
                        }
                        continue;
                    }
                    if(in_array($i, array(count($govnokod)-5,count($govnokod)-4))){
                        continue;
                    }
                    $temp['name'][] = !empty($govnokod[$i]) ? strip_tags($govnokod[$i]) : '';

                }*/
                if(empty($temp)) continue;
                $temp['name'] = implode(' ',$temp['name']);
                if(empty($temp['name'])){
                    continue;
                }
                $temp['name'] = preg_replace('/\s+/i',' ', $temp['name']);
                $temp['cost'] = implode(';',$temp['cost']);
                $result[] = $temp;
            }
            //p($result);
            $this->items = array_merge($this->items,$result);
            //p($this->items);
            //$match = array();
            //preg_match('/(?<=_)[0-9]+(?=_)/', $path, $match);
            //$this->price_id = current($match);
            //$this->company_name = $parse->find('#page_title h1', 0)->innertext;
            //$this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.time().'.csv';
        }
        $parse->clear();
        unset($parse);
        //die();
    }
    public function getUrl(){
        $parse = file_get_html('http://www.chermet.com/sitemap.html');
        $info_all = $parse->find('div.span6');
        //p($links);
        $href = array();
        $i = 0;
        foreach($info_all as $info){
            foreach($info->find('h4') as $h){
                if(in_array(iconv('cp1251', 'utf-8',$h->innertext), array('Металлообработка','Статьи'))){
                    //echo $h->innertext;
                    continue 2;
                }
                //echo $h->innertext;
            }
            $source = $info->find('div.catalog-section-list ul ul ul li a');
            foreach($source as $a){
                if($i>6) break;
                $href[] = 'http://www.chermet.com'.$a->href;
                $i++;
                //break;
            }
            //p($source->innertext);
            //$href[] = 'chermet.com'.$link->href;
        }
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        //p($href);
        //die();
        return $href;
    }

}