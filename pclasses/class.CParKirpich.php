<?php
class CParKirpich extends CParMain {
    static $name_parser = array(
        "Kirpich" => "КирпичРу"
    );
    function __construct(){
        parent::__construct();
        $this->dual_cost = false;
        $this->decimal = false;
        $this->author = "Никита";
        $this->batches = 60;
    }
    function processParsing() {

        $startTime = microtime(true);


        $this->logMessage("Получение ссылок на категории...");
        $url = [$this->site_link . "/shop"];
        $data = [
            "title" => "Категории",
            "log" => "ссылок на категории",
            "category_selector" => '//main[@class="catalog-content"]//div[contains(@class, "catalog-category__row")]//div//div//ul//li/a/@href'
        ];
        $categories = $this->gettingUrls($url, $data);
        #$categories = array_slice($categories, 0, 5);
        $this->logMessage("Найдено " . count($categories) . " категорий.");


        $this->logMessage("Пагинация ссылок...");
        $data = [
            "title" => "Пагинация",
            "log" => "пагинации",
            "paginate_selector" => '//main[@class="catalog-content"]//div[@class="catalog-filter"]//div//div//div[@class="pagination"]//div/a',
            "last_button_id" => 1,
            "url_argument" => "?PAGEN_2=",
            "html_argument" => "href="];
        $pageLinks = $this->gettingUrls($categories, $data, true);
        $this->logMessage("Получено " . count($pageLinks) . " ссылок на страницы с товарами.");


        $data = [
            "title" => "Ссылки на товары",
            "log" => "ссылок на товары",
            "title_selector" => ['//main[@class="catalog-content"]//div[contains(@class, "js-item-cards-container") and contains(@class, "cards-row")]' .
            '//div[@class="card"]//div[@class="card__inner"]//div[contains(@class, "card__content")]//a'],
            "price_selector" => ['//main[@class="catalog-content"]//div[contains(@class, "js-item-cards-container") and contains(@class, "cards-row")]' .
            '//div[@class="card"]//div[@class="card__inner"]//div[contains(@class, "card__price")]//span'],
            "big_data" => true,
            ];
        #$pageLinks = array_slice($pageLinks, 0, 1);
        $this->productCount = $this->gettingUrls($pageLinks, $data, false,true);


        $endTime = microtime(true);
        $this->parse_time = $endTime - $startTime;
    }
}