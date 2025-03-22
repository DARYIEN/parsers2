<?php
class CParGrandLine extends CParMain {
    static $name_parser = array(
        "GrandLine" => "Грандлайн"
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
        $url = [$this->site_link . "/katalog"];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//li[@class="category-item"]/div/a/@href'
        ];
        $categories = $this->gettingUrls($url, $data);
        array_splice($categories, -1);
        $this->logMessage("Найдено " . count($categories) . " категорий.");


        $this->logMessage("Пагинация ссылок...");
        $data = [
            "title" => "Пагинация",
            "log" => "пагинации",
            "paginate_selector" => '//ul[contains(@class, "paging")]//a[contains(@class, "paging__link")]',
            "last_button_id" => 2,
            "url_argument" => "?page=",
            "html_argument" => "href="
        ];
        $pageLinks = $this->gettingUrls($categories, $data, true);
        #$pageLinks = array_slice($pageLinks, 0, 40);
        $this->logMessage("Получено " . count($pageLinks) . " ссылок на страницы с товарами.");

        $this->logMessage("Получение ссылок на товары...");
        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => ['//strong[contains(@class, "product-item__title")]//a[contains(@class, "js_h_pua")]'],
            "price_selector" => ['//div[contains(@class, "product-item__buy-panel")]//strong[contains(@class, "product-item__price")]'],
            "price_ban_list" => ["0.00"],
            "big_data" => true
            ];
        $this->productCount = $this->gettingUrls($pageLinks, $data, false, true);


//        $this->logMessage("Начало парсинга товаров...");
//        $data = [
//            "title" => "Товары",
//            "log" => "товаров",
//            "price_selector" => '//span[@class="product__price"]//meta[@itemprop="price"]/@content',
//            "title_selector" => '//h1[@class="h1"]/text()',
//            "price_order" => 0];
//        #array_slice($productLinks, 0, 100);
//        $productsData = $this->gettingUrls($productLinks, $data);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}