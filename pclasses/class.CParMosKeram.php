<?php
class CParMoskeram extends CParMain {
    static $name_parser = array(
        "Moskeram" => "Москерам"
    );
    function __construct() {
        parent::__construct();
        $this->dual_cost = false;
        $this->decimal = false;
        $this->author = "Никита";
        $this->batches = 40;
    }
    function processParsing() {

        $startTime = microtime(true);

        $this->logMessage("Получение ссылок на категории...");
        $url = [$this->site_link . "/catalog/"];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//div[contains(@class, "row") and contains(@class, "catalog_sections_tile")]//div//div/ul/li/a/@href'
        ];
        $categories = $this->gettingUrls($url, $data);
        #$categories = array_slice($categories, 0, 1);
        $this->logMessage("Найдено " . count($categories) . " категорий.");


        $pageLinks = [];
        foreach ($categories as $category) {
            $pageLinks[] = $category . "?SHOWALL_1=1";
        }


        $this->logMessage("Начало парсинга ссылок на товары...");
        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => [
                '//div[contains(@class, "main__table-wrapper") and contains(@class, "clearfix")]//table[contains(@class, "producs")]//tr//td[contains(@class, "img")]//a',
                '//div[contains(@class, "row") and contains(@class, "products_tile")]//div//div//a[@class="img-wrap"]' #
            ],
        ];
        #$pageLinks = array_slice($pageLinks, 0, 1);
        $productLinks = $this->gettingUrls($pageLinks, $data);


        $this->logMessage("Начало парсинга товаров...");
        $data = [
            "title" => "Товары",
            "log" => "товаров",
            "title_selector" => "link",
            "price_selector" => ['//table[contains(@class, "props") and contains(@itemprop, "offers")]//span[contains(@class, "price") and contains(@class, "rouble")]'],
            "price_order" => 0,
            "price_ban_list" => ["0.00"],
            "big_data" => true,
        ];
        #$productLinks = array_slice($productLinks, 0, 10);
        $this->productCount = $this->gettingUrls($productLinks, $data, true);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}