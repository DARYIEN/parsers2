<?php

/*/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44

*/

class CParMcHtml extends CParMainMC
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
        $this->price_type = ISSERVER ? 'web' : 'safe2';
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
            $this->filter = array('cost' => array(5, 7), 'hide' => array(4, 6, 9), 'coef' => $this->priceInfo['coefficient'], 'selector' => 'table.catalogTable');
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
                sleep(60);
                $this->document_url = $url.'/PageAll/1';
                $document_name = 'temp_' . md5(time()) . '_0_' . $this->document_extended;
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
            $el = pq($table);
            foreach ($el->find('tr') as $tr) {
                $el_td = pq($tr)->find("td");
                if (!empty($el_td)) {
                    $i = 1;
                    $suffix = "";
                    foreach ($el_td as $td) {
                        switch($i) {
                            case 1:
                                $name = pq($td)->text();
                                break;
                            case 2:
                                $size = pq($td)->text();
                                break;
                            case 3:
                                $gost = pq($td)->text();
                                break;
                            case 4:
                                $length = pq($td)->text();
                                break;
                            case 9:
                                $cost = pq($td)->text();
                                break;
                        }
                        $i++;
                    }
                    if (!empty($cost)) {
                        $costs = array();
                        $size_name = str_replace('&ndash', '-', $name." ".$size." ".$gost." ".$length);
                        $size_name = str_replace(array('&nbsp;', '&#160;'), ' ', $size_name);
                        $size_name = trim($size_name, '&nbsp;');
                        $size_name = trim($size_name);
                        $size_name = str_replace(';', '!', $size_name);
                        $size_name = str_replace('×', 'х', $size_name);
                        $size_name = str_replace('б/у', 'б/у ', $size_name);
                        $size_name = preg_replace('/\s+/iu', ' ', $size_name);
                        $size_name = preg_replace('/[\(\)\[\]\'"]/iu', ' ', $size_name);
                        $cost = preg_replace('~[^0-9\,]+~', '', $cost);
                        $cost = str_replace(',', '.', $cost);
                        if (is_numeric($cost) && $cost != 0) {
                            $cost = str_replace('.', ',', $cost);
                            $cost = round($cost);
                            $costs[] = $cost * $this->filter['coef'];
                        } else {
                            $costs[] = 12345678;
                        }
                        $result[] = array('name' => $size_name, 'cost' => $costs);
                    }
                }
            }
            $this->items = array_merge($this->items, $result);
        }
        unset($parse);
    }

    public function getUrl()
    {
        $href = array();
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->home_url);
        curl_setopt($this->curl, CURLOPT_HEADER, 0);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
        $result = curl_exec_follow($this->curl);
        $parse = phpQuery::newDocumentHTML($result);
        if (!empty($parse)) {
            $links = pq($parse)->find('div.productsMenuCol ul li div.catalog-list-item a');
            foreach ($links as $link) {
                $link = pq($link)->attr("href");
                if (!empty($link)) {
                    if (strpos($link, "krepezh_gvozdi_bolty_cepi")) {
                        sleep(60);
                        curl_setopt($this->curl, CURLOPT_URL, 'https://mc.ru' . $link);
                        curl_setopt($this->curl, CURLOPT_HEADER, 0);
                        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                        $resultSub = curl_exec_follow($this->curl);
                        $parseSub = phpQuery::newDocumentHTML($resultSub);
                        $subLinks = pq($parseSub)->find('div.catalogItemList div.gr_spis ul li a');
                        foreach ($subLinks as $subLink) {
                            $subLink = pq($subLink)->attr("href");
                            if (!empty($subLink)) {
                                $href[] = 'https://mc.ru' . $subLink;
                            }
                        }
                    } else {
                        $href[] = 'https://mc.ru' . $link;
                        //if (sizeof($href) > 0)
                        //    break;
                    }
                }
            }
        }
        unset($this->curl);
        return $href;
    }
}
