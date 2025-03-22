<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

class CParInterSteel extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'intersteel' => 'Интерсталь'
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
        $this->home_url = 'http://www.intersteel-spb.ru/prais';
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = false;
        $this->document_urls = array( $this->home_url);
        $this->price_id = 8840725;
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
    function documentParsing($path){
        $parse = file_get_html($path);
        $tables = $parse->find('table.treeTable');
        if(!empty($tables)){
            $result = array();
            foreach($tables as $table){
                $step = 1;
                foreach($table->find('tr') as $tr){
                    $name = '';
                    $cost = '';
                    // p(count($tr->find('td')));
                    if (count($tr->find('td')) == 4 && $step != 1) $step = 2;
                    foreach($tr->find('td') as $td){
                        //echo iconv('cp1251','utf-8',$td->innertext);
                        //$temp['cost'] =  trim(strip_tags(str_replace(' ', '', $td->innertext)));
                        if($step == 1){
                            continue 2;
                        }else if ($step == 3){
                            $name .= ' '.$td->innertext;
                        }else if ($step == 4){
                            $name .= ' '.$td->innertext;
                        }else if ($step == 5){
                            $cost = trim(strip_tags($td->innertext));
                            $cost = iconv('UTF-8','windows-1251',$cost);
                            $cost = str_replace(chr(160), '', $cost);
                            //$cost = str_replace(array('&nbsp;', '&#160;'), array('', ''), $cost);
                            //$cost = preg_replace('/\s+/iu','', $cost);
                        }else if ($step == 7){
                            if($td->innertext == 'кг') $cost *= 1000;
                        }
                        //$name .= ' '.$td->innertext;
                        $name = str_replace('&ndash','-', $name);
                        $name = strip_tags($name);
                        $name = html_entity_decode($name);
                        $name = trim($name,'&nbsp;');
                        $name = str_replace(';','!', $name);
                        $step++;
                    }
                    $step = 3;
                    if (empty($cost)) continue;
                    $result[]=array('name'=>$name,'cost'=>$cost,);
                }

                //p($result);
                //  die();

                /*foreach($tr->find('td.pl_center div, td.pl_left') as $td){
                    //p($td->innertext);
                    //echo iconv('cp1251','utf-8',$td->innertext);
                    $temp['name'][] =  trim(str_replace('&nbsp;', ' ', $td->innertext));
                }
                if(!is_numeric($temp['cost'])){
                    //p($temp['cost']);
                    continue;
                }
                if(empty($temp)) continue;
                $temp['name'] = implode(' ',$temp['name']);
                if(empty($temp['name'])){
                    continue;
                }
                $temp['name'] = preg_replace('/\s+/i',' ', $temp['name']);

                //$temp['cost'] = implode(';',$temp['cost']);
                $result[] = $temp;*/
            }
            $this->items = array_merge($this->items,$result);
        }
        $parse->clear();
        unset($parse);
        //die();
    }
    /*
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
        $info_all = $parse->find('ul.msubmenu li');
        $href = array();
        foreach($info_all as $info_first){
            $info_second = $info_first->find('a',0);
            $href[] = 'http://www.constali.ru'.$info_second->href;
            //break;
        }
        //$href = array('http://www.chermet.com/catalog/section/profnastil-copy');
        p($href);
        //die();
        return $href;
    }
*/
}