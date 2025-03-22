<?php
class CParTopHouse extends CParMain {
    static $name_parser = array(
        "TopHouse" => "ТопХаус"
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
        $url = [$this->site_link];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//div[contains(@class, "panel") and contains(@class, "panel-default")]//ul[@class="menu-body"]/li/a[@href and not(ancestor::ul[contains(@class, "sub-menu")])]/@href'
        ];
        $categories = $this->gettingUrls($url, $data);
        $categories = array_slice($categories, 0, count($categories) - 2);

        #$categories = array_slice($categories, 0, 1);
        $this->logMessage("Найдено " . count($categories) . " категорий.");


        $this->logMessage("Пагинация ссылок...");
        $data = [
            "title" => "Пагинация",
            "log" => "пагинации",
            "paginate_selector" => '//nav[@class="pagination-block"]/ul/li/a',
            "last_button_id" => 2,
            "url_argument" => "page/",
            "html_argument" => "nodeValue"
        ];
        $pageLinks = $this->gettingUrls($categories, $data, true);
        #$pageLinks = array_slice($pageLinks, 0, 1);
        $this->logMessage("Получено " . count($pageLinks) . " ссылок на страницы с товарами.");


        $this->logMessage("Получение ссылок на товары...");
        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => ['//div[@class="row"]//div[contains(@class, "item-list") and contains(@class, "vertical")]' .
            '//div[contains(@class, "vertical-price-block") and contains(@class, "flex-row") and contains(@class, "row")]/div/div/div[@class="description"]//a[contains(@class, "click-items")]'],
            "title_html_argument" => "href",
            "price_selector" => ['//div[@class="row"]//div[contains(@class, "item-list") and contains(@class, "vertical")]' .
            '//div[contains(@class, "vertical-price-block") and contains(@class, "flex-row") and contains(@class, "row")]//div//div' .
            '//div[@class="description"]//div[@class="price-wrapper"]/span[@itemprop="price"]'],
            "price_ban_list" => ["0.00"],
            "big_data" => true
        ];
        $this->productCount= $this->gettingUrls($pageLinks, $data, false, true);
        #$productLinks = array_slice($productLinks, 0, 1000);

//        $this->logMessage("Начало парсинга товаров...");
//        $data = [
//            "title" => "Товары",
//            "log" => "товаров",
//            "title_selector" => "",
//            "price_selector" => "",
//            "description_selector" => "",
//            "price_order" => 0,
//            "price_ban_list" => []
//            ];
//        $productsData = $this->gettingUrls($productLinks, $data);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}