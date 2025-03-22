<?php
class CParPulsStroy extends CParMain {
    static $name_parser = array(
        "PulsStroy" => "ПульсСтрой"
    );
    function __construct() {
        parent::__construct();
        $this->dual_cost = false;
        $this->decimal = false;
        $this->author = "Никита";
        $this->batches = 30;
    }
    function processParsing() {

        $startTime = microtime(true);


        $this->logMessage("Получение ссылок на категории...");
        $url = [$this->site_link . "/catalog/"];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//div[contains(@class, "item_block ") and contains(@class, "col-md-6") and contains(@class, "col-sm-6")]//td[contains(@class, "image")]/a/@href'
        ];
        $categories = $this->gettingUrls($url, $data);
        #$categories = array_slice($categories, 0, 1);
        $this->logMessage("Найдено " . count($categories) . " категорий.");


        $this->logMessage("Пагинация ссылок...");
        $data = [
            "title" => "Пагинация",
            "log" => "пагинации",
            "paginate_selector" => '//div[contains(@class, "nums")]//a',
            "last_button_id" => 1,
            "url_argument" => "?PAGEN_1=",
            "html_argument" => "nodeValue"
        ];
        $pageLinks = $this->gettingUrls($categories, $data, true);
        $this->logMessage("Получено " . count($pageLinks) . " ссылок на страницы с товарами.");


        $baseSelector = '//div[contains(@class, "wrapper_inner")]' .
            '//div[contains(@class, "container")]//div[contains(@class, "inner_wrapper")]' .
            '//div[contains(@class, "ajax_load") and contains(@class, "block")]//div[contains(@class, "catalog_block") and contains(@class, "items") '.
            'and contains(@class, "block_list") and contains(@class, "margin0") and contains(@class, "row") and contains(@class, "flexbox")]' .
            '//div[contains(@class, "catalog_item_wrapp") and contains(@class, "item")]';
        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => [$baseSelector . '//div[contains(@class, "item-title")]//a/span'],
            "price_selector" => [$baseSelector . '//div[contains(@class, "price_matrix_wrapper")]//div[contains(@class, "price")]'],
            "price_html_argument" => "data-value",
            "big_data" => true
        ];
        #$pageLinks = array_slice($pageLinks, 0, 1);
        $this->productCount= $this->gettingUrls($pageLinks, $data, false, true);


//        $this->logMessage("Начало парсинга товаров...");
//        #$productLinks = array_slice($productLinks, 0, 1000);
//        $data = [];
//        $productsData = $this->gettingUrls($productLinks, $data);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}