<?php
class CParSabonSM extends CParMain{
    static $name_parser = array(
        "SabonSM" => "СабонСМ"
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
            "category_selector" => '//div[contains(@class, "section-content-wrapper")]//td[contains(@class, "image")]/a/@href'
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
            '//div[contains(@class, "container")]//div[contains(@class, "js_wrapper_items")]//div[contains(@class, "top_wrapper")' .
            'and contains(@class, "items_wrapper") and contains(@class, "catalog_block_template")]//div[contains(@class, "catalog_block") and contains(@class, "items")'.
            'and contains(@class, "ajax_load") and contains(@class, "has-bottom-nav") and contains(@class, "margin0") and contains(@class, "js_append ") and contains(@class, "block ") and '.
            'contains(@class, "flexbox") and contains(@class, "row")]//div[contains(@class, "inner_wrap") and contains(@class, "TYPE_1")]';
        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => [$baseSelector . '//div[contains(@class, "item-title")]//a/span'],
            "price_selector" => [$baseSelector . '//div[contains(@class, "price_matrix_wrapper")]//div[contains(@class, "price") and contains(@class, "font-bold")'.
             ' and contains(@class, "font_mxs")]//span[contains(@class, "values_wrapper")]//span[contains(@class, "price_value")]'],
            "big_data" => true
        ];
        #$pageLinks = array_slice($pageLinks, 0, 1);
        $this->productCount = $this->gettingUrls($pageLinks, $data, false, true);


//        $this->logMessage("Начало парсинга товаров...");
//        $data = ['//div[contains(@class, "middle-info-wrapper ") and contains(@class, "main_item_wrapper")]//div[contains(@class, "price_matrix_wrapper")]//span[contains(@class, "price_value")]', '', '//h1[contains(@id, "pagetitle")]', 0];
//        #$productLinks = array_slice($productLinks, 0, 11);
//        $productsData = $this->gettingUrls($productLinks, $data);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}