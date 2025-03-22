<?php
class CParAGM extends CParMain {
    static $name_parser = array(
        "AGM" => "Агрупп"
    );
    function __construct() {
        parent::__construct();
        $this->dual_cost = false;
        $this->decimal = false;
        $this->author = "Никита";
        $this->batches = 11;
    }
    function processParsing() {

        $startTime = microtime(true);


        $this->logMessage("Получение ссылок на категории...");
        $url = [$this->site_link];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//a[contains(@class, "main-banner-3__item")]//@href'
        ];

        $categoriesUnsorted = $this->gettingUrls($url, $data);
        $categories = [];
        foreach ($categoriesUnsorted as $category) {
            if (strpos($category, "/catalog/") && !empty($category)) {
                $categories[] = $category;
            }
        }
        $this->logMessage("Найдено " . count($categories) . " категорий.");


        $this->logMessage("Получение ссылок на подкатегории...");
        $data = [
            "title" => "Категории",
            "log" => "ссылок на подкатегории",
            "category_selector" => '//div[@class="categories__item"]//a[@class="categories__image-wrapper"]//@href'
        ];
        $underCategories = $this->gettingUrls($categories, $data);
        $this->logMessage("Ссылок на подкатегории получено : " . count($underCategories));


        $this->logMessage("Пагинация...");
        $data = [
            "title" => "Пагинация",
            "log" => "пагинации",
            "paginate_selector" => '//div[contains(@class, "pagination")]//a[contains(@class, "pagination__item")]',
            "last_button_id" => 2,
            "url_argument" => "?PAGEN_1=",
            "html_argument" => "href="
        ];
        $pageLinks = $this->gettingUrls($underCategories, $data, true);
        #$pageLinks = array_slice($pageLinks, 0, 1);
        $this->logMessage("Получено " . count($pageLinks) . " ссылок на страницы с товарами.");


        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => ['//div[contains(@class, "product-table__product")]//a[contains(@class, "product-table__title link")]'],
            "price_selector" => ['//div[contains(@class, "product-table__product")]//div[contains ' .
                '(@class, "product-table__price product-table__price_main")]//div[@class="product-table__price-value"]'],
            "title_html_argument" => "href",
            "price_ban_list" => ["0.00"]
        ];
        $productLinks = $this->gettingUrls($pageLinks, $data);
        #$productLinks = array_slice($productLinks, 0, 10);
        $this->logMessage("Получено " . count($productLinks) . " ссылок на товары.");


        $this->logMessage("Начало парсинга товаров...");
        $data = [
            "title" => "Товары",
            "log" => "товаров",
            "description_selector" => '//div[contains(@class, "info__descr wysiwyg")]',
            "title_selector" => ['//h1[contains(@class, "content__title")]'],
            "price_selector" => ['//div[contains(@class, "item__p-item")]//div[contains(@class, "item__p-value")]'],
            "price_order" => 0,
            "big_data" => true
        ];
        $this->productCount = $this->gettingUrls($productLinks, $data);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}