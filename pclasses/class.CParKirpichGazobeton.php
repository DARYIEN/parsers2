<?php

class CParKirpichGazobeton extends CParMain{
    static $name_parser = array(
        "KirpichGazobeton" => "Зедстрой"
    );
    function __construct(){
        parent::__construct();
        $this->dual_cost = false;
        $this->decimal = false;
        $this->author = "Никита";
        $this->batches = 24;
    }
    function processParsing() {

        $startTime = microtime(true);


        $this->logMessage("Получение ссылок на категории...");
        $url = [$this->site_link . "/catalog"];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//div[contains(@class, "section-content-wrapper ")]/div/div/div/table//td/a/@href'
        ];
        $categories = $this->gettingUrls($url, $data);
        #$categories = array_slice($categories, 0, 5);
        $this->logMessage("Найдено " . count($categories) . " категорий.");


        $this->logMessage("Пагинация ссылок...");
        $data = [
            "title" => "Пагинация",
            "log" => "пагинации",
            "paginate_selector" => '//div[contains(@class, "module-pagination")]/div[contains(@class, "nums")]/a',
            "last_button_id" => 1,
            "url_argument" => "?PAGEN_1=",
            "html_argument" => "href="];
        $pageLinks = $this->gettingUrls($categories, $data, true);
        $this->logMessage("Получено " . count($pageLinks) . " ссылок на страницы с товарами.");


        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => ['//div[contains(@class, "catalog_block") and contains(@class, "items") and contains(@class, "row")]/div/div/div/div/a'],
            "price_selector" => ['//div[contains(@class, "catalog_block") and contains(@class, "items") and contains(@class, "row")]/div/div/div/div/div/div/div//div[contains(@class, "font-bold")]'],
            "price_html_argument" => "data-value",
            "title_html_argument" => "href",
            "big_data" => true,
        ];
        $pageLinks = array_slice($pageLinks, 0, 1);
        $this->productCount = $this->gettingUrls($pageLinks, $data, false,true);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}