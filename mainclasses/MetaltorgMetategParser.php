<?php

include_once ROOT . '/extension/Excel/reader.php';
include_once ROOT . '/extension/PHPExcel/Classes/PHPExcel.php';
include_once ROOT . '/extension/html_dom/simple_html_dom.php';

class MetaltorgMetategParser{

    private $urls = array();
    private $rootUrls = array();

    private function getAllUrls($urls){
        $hrefs = array();
        foreach($urls as $url => $rootTitle){
            $htmlDomPage = file_get_html($url);
            if($htmlDomPage){
                $links = $htmlDomPage->find('a');
                //$title = $htmlDomPage->find('title', 0)->plaintext;
                //p($links);
                foreach($links as $link){
                    $href = $link->href;
                    if($this->isValid($href)){
                        if(!preg_match("/http:\/\//", $href)){
                            $href = str_replace(array("../"), '', $href);
                            if($href{0} == "/"){
                                $href = preg_replace("/\//","",$href,1);
                            }
                            $href = "http://doska.metaltorg.ru/".$href;
                        }
                        if(!in_array($href,array_keys($this->urls)) && !in_array($href,array_keys($hrefs))){
                            $hrefs[$href] = $href;
                        }
                    }
                }
            }
            $this->urls = array_merge($this->urls, $hrefs);
            //$this->urls[$url] = $title;
            $this->writeInFile();
            if(!empty($hrefs)){
                $this->getAllUrls($hrefs);
            }
        }
    }
    private function isValid($href){
        if(preg_match("/[A-z]\.metaltorg\.ru/", $href) &&( preg_match("/metaltorg/", $href) || !preg_match("/http:\/\//", $href)) && $href != '' && $href != "#" && !preg_match("/banners/",$href) && !preg_match("/classified/",$href) && !preg_match("/adclick/",$href) && !preg_match("/404\./",$href) && !preg_match("/www.metaltorg/",$href) && $href != "http://metaltorg.ru/" && $href != "http://metaltorg.ru"){
            return true;
        }
        return false;
    }
    public function start(){
        $this->readOfFile();
        $this->getAllUrls($this->urls);
        //$this->getAllUrls($this->urls);
        //$this->cutArray("http://metaltorg.ru/catalogue/showlist.php?ct=3&ky=001B00080012");
       // $this->readAndWriteMetatag();
        //p($this->urls);
    }
    private function writeInFile(){
        // Открыть текстовый файл
        $f = fopen(ROOT."/files/hrefs_met_1.txt", "w");
        // Записать текст
        fwrite($f, serialize($this->urls));
        // Закрыть текстовый файл
        fclose($f);
    }
    private function readOfFile(){
        // Открыть текстовый файл
        if(is_file(ROOT."/files/hrefs_met_1.txt")){
            $file = file_get_contents(ROOT."/files/hrefs_met_1.txt");
            $this->urls = unserialize($file);
        }else{
            $this->urls = array("http://metaltorg.ru"=>0);
        }
    }
    private function readAndWriteMetatag(){
        $csv[] = "Title; Description; Keywords; Url";
        $metatags = array();
        if(!is_file(ROOT."/files/metaltorgmeta.csv")){
            file_put_contents(ROOT."/files/metaltorgmeta.csv","");
        }
        foreach($this->urls as $url){
            try{
                $allMetatags = get_meta_tags($url);
                $titleHtmlDom = file_get_html($url);
                if(!empty($titleHtmlDom)){
                    $title = $titleHtmlDom->find("title", 0)->plaintext;
                }

                $metatags["Title"] = $title;
                $metatags["Description"] = !empty($allMetatags['description']) ? $allMetatags['description'] : '';
                $metatags["Keywords"] = !empty($allMetatags['keywords']) ? $allMetatags['keywords'] : '';
                $metatags["url"] = $url;
                $csv[] = implode(";", $metatags);
                $csvFile = new CSV(ROOT."/files/metaltorgmeta.csv");
                $csvFile->setCSV($csv);
            }catch (Exception $e){

            }
        }

    }
    private function cutArray($url){
        $this->urls = array_slice($this->urls,array_search($url, $this->urls));
    }

}
class CSV {
    private $_csv_file = null;
    /**
     * @param string $csv_file  - путь до csv-файла
     */
    public function __construct($csv_file) {
        if (file_exists($csv_file)) { //Если файл существует
            $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную
        }
        else { //Если файл не найден то вызываем исключение
            throw new Exception("Файл \"$csv_file\" не найден");
        }
    }

    public function setCSV(Array $csv) {
        //Открываем csv для до-записи,
        //если указать w, то  ифнормация которая была в csv будет затерта
        $handle = fopen($this->_csv_file, "w");

        foreach ($csv as $value) { //Проходим массив
            //Записываем, 3-ий параметр - разделитель поля
            fputcsv($handle, explode(";", $value), ";");
        }
        fclose($handle); //Закрываем
    }

    /**
     * Метод для чтения из csv-файла. Возвращает массив с данными из csv
     * @return array;
     */
    public function getCSV() {
        $handle = fopen($this->_csv_file, "r"); //Открываем csv для чтения

        $array_line_full = array(); //Массив будет хранить данные из csv
        //Проходим весь csv-файл, и читаем построчно. 3-ий параметр разделитель поля
        while (($line = fgetcsv($handle, 0, ";")) !== FALSE) {
            $array_line_full[] = $line; //Записываем строчки в массив
        }
        fclose($handle); //Закрываем файл
        return $array_line_full; //Возвращаем прочтенные данные
    }

}