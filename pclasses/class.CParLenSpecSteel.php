<?php
/**
 * Created by vasya.
 * Date: 29.11.13
 * Time: 16:57
 */

/*class CParLenSpecSteel extends CParMain{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    static $name_parser = array(
        'lenspecsteel' => 'ЛЕНСПЕЦСТАЛЬ'
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
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu','',$this->company_name )).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        $this->price_type = 'web';
        $this->dual_cost = false;
        $this->document_urls = array('http://www.lsst.ru/xls/export.php');
        $this->home_url = current($this->document_urls);
        $this->price_id = 8483187;
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
        $tables = $parse->find('table');
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
                            $step++;
                            continue 2;
                        }else if ($step == 2){
                            $header = $td->innertext;
                            $step++;
                        }else if ($step == 3){
                            $name .= $header.' '.$td->innertext;
                            $step++;
                        }else if ($step == 4){
                            //$name .= ' '.$td->innertext;
                            $step++;
                        }else if ($step == 5){
                            $cost = trim(strip_tags(str_replace(' ', '', $td->innertext)));

                        }

                        $name = str_replace('&ndash','-', $name);
                        $name = strip_tags($name);
                        $name = html_entity_decode($name);
                        $name = trim($name,'&nbsp;');
                        $name = str_replace(';','!', $name);
                        $name = str_replace('"','', $name);

                    }
                    $step = 3;
                    if (empty($cost)) continue;
                    $result[]=array('name'=>$name,'cost'=>$cost,);
                }

                //p($result);
                //  die();


            }
            $this->items = array_merge($this->items,$result);
        }
        $parse->clear();
        unset($parse);
        //die();
    }

}
*/
class CParLenSpecSteel extends CParMain{
    var $city_id;
    static $name_parser = array(
        'lenspecsteel' => 'ЛЕНСПЕЦСТАЛЬ'
    );
    public $items = array();
    function start(){
        $this->getDocuments()->processParsing();
        $mail[$this->city_id] = '<br /><h4>'.current(array_values(self::$name_parser)).'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->document_url.')</h4>';
        $mail[$this->city_id] .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $mail[$this->city_id] .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
        return $mail;
    }
    function __construct(){
        foreach($this->list_parsers as $city_id => $parser){
            if(in_array(get_class($this), $parser)){
                $this->city_id = $city_id;
                break;
            }
        }
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.xls';
        $this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).'_'.time().'.csv';
        //$this->document_urls = $this->getUrl();
        $this->document_urls['main'] = 'http://www.lsst.ru/xls/export2.php';
        //$this->document_urls['prof'] = 'http://www.spk.ru/price/spk-krsn/_m_/10786/_p_/file';
        //$this->coef = 1000;
        $this->dual_cost = false;
        $this->decimal = true;
        $this->price_id = 8483187;
        $this->author = 'Михаил';
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
            if($key == 'main'){
                for($i=1; $i <= 22; $i++){
                    $this->filter =  array(
                        'propusk' => $this->num('C'),
                        'cost1' => false,
                        'cost2' => 0,
                        'coef' => 1,
                        'begin' => '',
                        'end' => '',
                        'header' => false,
                        'skleyka' => false,
                        'dualheader'=>false,
                        'tree' => false,
                        'fixcell' => false,
                        'baseOfFixCell' => false,
                        'defaultfixcell' => false,
                        //'columns' => array('D','B','V','AZ'),
                        'horizontal' => array(
                            'to' => column('D'),
                            'from' => column('B')),
                        'vertical' => array(
                            'to' => array(
                                'numeric' => null),
                            'from' => array(
                                'numeric' => 1))
                    );
                    $this->filter_subset = new MyReadFilter($this->filter/*,false*/);
                    $this->head_non = true;
                    $this->documentLoad($path);
                    $this->sheet = $this->objPHPExcel->getSheet($i)->toArray();
                    $this->documentParsing();
                }
            }else if($key == 'prof'){

            }
            //$kid = new $key($path);
            //$this->items = array_merge($this->items,$kid->processParsing());
        }
        //p($this->items);
        $this->save();
    }

    function documentParsing(){
        $name = '';
        $cost = '';
        $treeString = '';
        if($this->filter['fixcell']!==false) {
            if($this->filter['defaultfixcell']!==false) {
                $fixCell = array_combine($this->filter['fixcell'] ,$this->filter['defaultfixcell']);
            }else{
                $fixCell = array_fill_keys($this->filter['fixcell'] ,'');
            }
        }
        $new_header=false;
        $header =$this->filter['header'];
        foreach($this->sheet as $rows){
            $rows = clear_array($rows);
            // p($rows);
            foreach($rows as $num_row => $row){
                if(!empty($row)){

                    if(in_array($row, array('Цена за кг, руб'))){
                        $this->filter['coef'] = 1000;
                        $this->filter['end'] = '';
                    }
                    if(in_array($row, array('Цена за м/п, руб'))){
                        $this->filter['coef'] = 1;
                        $this->filter['end'] = 'м/п';
                    }
                    if(in_array(trim($row), array('уголок 63х63х5'))){
                        $this->filter['begin']='';
                    }
                    if(in_array(trim($row), array('ООО ТД «Армроскомплект» является  дилером ООО «ТД «Кичигинский» и предлагает гидранты пр-ва Кичигинского рем. Завода.С прайс-листом завода можно ознакомиться ниже (прайс№1)'))){
                        continue;
                    }

                    if($this->filter['header']  && count($rows) == 1 && $this->filter['horizontal']['from']['numeric'] == $num_row){
                        if ($new_header && $this->filter['skleyka']){$header .= ' '.$row; continue;}
                        if ($new_header && $this->filter['dualheader'])$this->filter['header']=str_replace($this->filter['header'],'',$header);
                        if ($this->filter['header'] === true){
                            $header = $row;
                        }else {
                            if($this->filter['dualheader']){
                                $header = $this->filter['header'].' '.$row;
                            }else{
                                $header = $row;
                            }
                        }
                        $new_header=true;
                        continue;
                    }
                    if($this->filter['fixcell']!== false && in_array($num_row, $this->filter['fixcell'])){
                        if($num_row == $this->filter['fixcell'][0])$fixCell=array();
                        $fixCell[$num_row]=$row;
                        continue;
                    }
                    if($this->filter['fixcell']!== false && in_array($num_row, $this->filter['baseOfFixCell'])){
                        $name .= ' '.implode(" ", $fixCell);
                    }
                    if($num_row == $this->filter['horizontal']['from']['numeric']+1 && $this->filter['tree']!== false && $this->filter['tree'] >=  $this->filter['horizontal']['to']['numeric']-$this->filter['horizontal']['from']['numeric']-count($rows)){
                        $treeString = $row;
                        continue;
                    }
                    if($this->filter['cost1']!==false && in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost1']))){
                        $cost = str_replace(array(' ','руб.',',','р.','От','от','&#160;'),'',$row) * $this->filter['coef'];
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if(in_array($num_row, array($this->filter['horizontal']['to']['numeric'] - $this->filter['cost2']))){
                        //$row = iconv('UTF-8','windows-1251',$row);
                        $cost = str_replace(array(' ','руб.','р.','&#160;','&nbsp;', chr(160),'От','от'),'',$row) * $this->filter['coef'];
                        if(!is_numeric($cost) || is_null($cost)){
                            $name = '';
                            $cost = '';
                            continue 2;
                        }
                        if($cost== 0) continue;
                        $costs[] = $cost;
                        continue;
                    }
                    if(in_array($num_row, $this->filter['propusk'])){
                        continue;
                    }
                    if(in_array($row, array('тн','тн.', 'шт.', 'кг',))){
                        continue;
                    }
                    /*if(in_array($row, array('Кирпич ручной формовки (Эстония)'))){
                        $this->filter['header'] = true;
                    }*/
                    $new_header=false;
                    $name .= ' '.$row;
                }
            }
            if(!empty($name)&& !empty($costs)){
                $this->items[] = array('name' => preg_replace('/Ǿ/iu','',preg_replace('/\?/iu','', preg_replace('/;/iu','!', preg_replace('/\s+/iu',' ',trim($this->filter['begin'].' '.$header.' '.$treeString.' '.$name.' '.$this->filter['end']))))), 'cost' => $costs);
            }
            $name = '';
            $cost = '';
            unset($costs);
        }
    }
    public function getUrl(){
        preg_match_all('~href=\W\S+.xlsx{0,1}\W~', file_get_html('http://kayp.ru/')->find('body',0)->innertext, $out);
        $links['main']=$out[0][0];
        // p($out);
        //p(str_replace(array('href=','"',"'"), '', $links));
        //die();
        return str_replace(array('href=','"',"'"), '', $links);
    }
}