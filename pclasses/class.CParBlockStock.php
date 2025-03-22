<?php
class CParBlockStock extends CParMain {
    static $name_parser = array(
        "BlockStock" => "Блоксток"
    );
    function __construct() {
        parent::__construct();
        $this->dual_cost = false;
        $this->decimal = false;
        $this->author = "Никита";
        $this->batches = 50;
    }
    function processParsing() {

        $startTime = microtime(true);


        $this->logMessage("Получение ссылок на категории...");
        $url = [$this->site_link . "/catalog/"];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//div[contains(@class, "section_item item")]//td[contains(@class, "image")]/a/@href'
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
            "html_argument" => "href="
        ];
        $pageLinks = $this->gettingUrls($categories, $data, true);
        $this->logMessage("Получено " . count($pageLinks) . " ссылок на страницы с товарами.");


        $baseSelector = '//div[contains(@class, "container")]' .
            '//div[contains(@class, "js_wrapper_items") and contains(@class, "has_mobile_filter_compact")]' .
            '//div[contains(@class, "right_block1") and contains(@class, "clearfix") and contains(@class, "catalog") and contains(@class, "vertical")]' .
            '//div[contains(@class, "inner_wrapper")]' .
            '//div[contains(@class, "ajax_load") and contains(@class, "block")]' .
            '//div[contains(@class, "top_wrapper margin0") and contains(@class, "show_un_props")]' .
            '//div[contains(@class, "catalog_item_wrapp ") and contains(@class, "item")]';
        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => [$baseSelector . '//div[contains(@class, "item_info--top_block")]//div[contains(@class, "item-title")]/a/span'],
            "price_selector" => [$baseSelector . '//div[contains(@class, "item_info--bottom_block")]//div[contains(@class, "price_matrix_block")]//div[contains(@class, "price")]//span[contains(@class, "price_value")]'],
            "price_html_argument" => 'nodeValue',
            "price_ban_list" => ["0.00"],
            "big_data" => true
        ];
        #$pageLinks = array_slice($pageLinks, 0, 1);
        $this->productCount = $this->gettingUrls($pageLinks, $data, false, true);


//        $this->logMessage("Начало парсинга товаров...");
//        $data = [
//            "title" => "Товары",
//            "log" => "cсылок на товары",
//            "price_selector" => '//div[contains(@class, "middle_info") and contains(@class, "main_item_wrapper")]//div[@class="price"]',
//            "title_selector" => '//h1[contains(@id, "pagetitle")]',
//            "price_order" => 0
//        ];
//        #array_slice($productLinks, 0, 11);
//        $productsData = $this->gettingUrls($productLinks, $data);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}