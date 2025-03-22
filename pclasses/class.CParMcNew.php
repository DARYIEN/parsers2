<?php

/*/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44

*/

class CParMcNew extends CParMainMC
{
    var $city_id;
    var $message = '';
    var $document_urls;
    var $home_url;
    var $company_name;
    var $priceInfo;
    static $name_parser = array(
        'mc' => ''
    );

    function start()
    {
        $this->getDocuments();
        $this->processParsing();
        $mail[$this->city_id] = $this->message;
        return $mail;
    }

    function __construct()
    {
        $this->iconv = true;
        $this->items = array();
        foreach ($this->list_parsers as $city_id => $parser) {
            if (in_array(get_class($this), $parser)) {
                $this->city_id = $city_id;
                break;
            }
        }
        $this->formDirsArray()->createDirs();
        $this->document_extended = '.html';
        //$this->document_name = current(array_keys(self::$name_parser)).'_'.date('d-m-Y', time()).time().'.csv';
        $this->price_type = ISSERVER ? 'safe' : 'safe2';
        $this->dual_cost = true;
    }

    function formDirsArray()
    {
        $this->dirArray['root'] = '/files/' . current(array_keys($this->cities_list[$this->city_id])) . '/' . current(array_keys(self::$name_parser));
        $this->dirArray['full'] = $this->dirArray['root'] . '/price_full';
        $this->dirArray['new_pos'] = $this->dirArray['root'] . '/price_new_position';
        $this->dirArray['temp'] = $this->dirArray['root'] . '/temporary';
        return $this;
    }

    function processParsing()
    {
        if (isset($this->priceInfo)) {
            $this->filter = array('cost' => array(5, 7), 'hide' => array(4,6, 9), 'coef' => $this->priceInfo['coefficient'], 'selector' => 'div#grid-scroll table');
            foreach ($this->document_list as $path) {
                $this->documentParsing($path);
            }
            $this->company_name = $this->priceInfo['company_name'];
            $this->document_name = rus2translit(preg_replace('/[^a-zа-яё0-9]+/iu', '', $this->company_name)) . '_' . date('d-m-Y', time()) . '_' . time() . '.csv';
            $this->save();

        } else {
            throw new Exception("Нельзя так");
        }


        //$this->save();
    }

    function getDocuments()
    {
        if (!empty($this->document_urls)) {
            foreach ($this->document_urls as $url) {
                $this->document_url = $url;
                $document_name = 'temp_' . md5(time()) . '_0_' . $this->document_extended;
                //echo $document_name;
                $this->getDocument($document_name);
            }
        }
        return $this;
    }

    function documentParsing($path)
    {
        $parse = phpQuery::newDocumentFileHTML($path);
        $table = $parse->find($this->filter['selector']);
        if (!empty($table)) {
            $result = array();
            $table_number = 1;
            $el = pq($table);
            foreach ($el->find('tr') as $tr) {
                $name = '';
                $costs = array();
                $el_td = pq($tr)->find("td");
                foreach ($el_td as $key => $value) {
                    $td = pq($value);
                    //echo $key."=>".$value."<br />";
                    if ($td->text() == "Наименование") continue 2;
                    if (in_array($key, $this->filter['hide'])) continue;
                    if (in_array($key, $this->filter['cost'])) {
	                    $count = 0;
	                    $badSymbol = array("т","м2","м");
	                    str_replace($badSymbol,"",trim($td->text()),$count);
                        if ($count > 0) continue;
                        $cost = preg_replace('~[^0-9\,]+~', '', $td->text());
                        $cost = str_replace(',', '.', $cost);
                        //echo "cost - - - ".$cost."<br />";
                        if (is_numeric($cost) && $cost != 0) {
                            $cost = str_replace('.', ',', $cost);
	                        $cost = round($cost);
                            $costs[] = $cost * $this->filter['coef'];
                        }
			//print_r($costs);
                        continue;
                    }
                    $name .= ' ' . $td->text();
                }
		//print_r($costs);
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
            $this->items = array_merge($this->items, $result);
            //p($this->items);
        }
        unset($parse);
    }


    public function getUrl()
    {
        if (ISSERVER) {
            $connection = proxyConnector::getIstance();
            $connection->launch($this->home_url, null);
            $result = $connection->getProxyData();
            $parse = phpQuery::newDocumentHTML($result['return']);
            if (!empty($parse)) {
                $links = pq($parse)->find('li#second_tree_level li#third_tree_level a');
                unset($this->curl);
                $href = array();
                unset($this->curl);
                foreach ($links as $link) {
                    $link = pq($link);
                    $connection->launch('http://mc.ru:8080' . $link->attr("href"), null);
                    $result = $connection->getProxyData();
                    $new_parse = phpQuery::newDocumentHTML($result['return']);
                    if (!empty($new_parse)) {
                        $new_links = pq($new_parse)->find('div#products_nav_list ul li a');
                        foreach ($new_links as $new_link) {
                            $href[] = 'mc.ru:8080' . pq($new_link)->attr("href");
                        }
                    }
                }
            }
        } else {
            $this->curl = curl_init($this->home_url);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
            $result = curl_exec_follow($this->curl);
            $parse = phpQuery::newDocumentHTML($result);
            if (!empty($parse)) {
                $links = pq($parse)->find('li#second_tree_level li#third_tree_level a');
                unset($this->curl);
                $href = array();
                foreach ($links as $link) {
                    $link = pq($link);
                    $this->curl = curl_init('http://mc.ru:8080' . $link->attr("href"));
                    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                    $result = curl_exec_follow($this->curl);
                    $new_parse = phpQuery::newDocumentHTML($result);
                    if (!empty($new_parse)) {
                        $new_links = $new_parse->find('div#products_nav_list ul li a');
                        foreach ($new_links as $new_link) {
                            $href[] = 'mc.ru:8080' . pq($new_link)->attr("href");
                        }
                        if (count($href) == 10) break;
                    }
                }
            }
        }
        //print_r($href);
        return $href;
    }
}
