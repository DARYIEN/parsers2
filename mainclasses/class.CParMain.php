<?php
include_once ROOT . '/pclasses/class.CParMetallservice.php';

$sites = glob(ROOT . "/pclasses/*");
asort($sites);
foreach ($sites as $path) {
    include_once($path);
}

include_once ROOT . '/extension/Excel/reader.php';
include_once ROOT . '/extension/PHPExcel/Classes/PHPExcel.php';
include_once ROOT . '/extension/html_dom/simple_html_dom.php';

class CParMain extends Options
{
    public $city_id;
    public $db = null;
    public $message = '';
    public $dirArray;
    public $our_link = 'https://185.248.102.77';
    public $dual_cost;
    public $price_type = 'xml_price';
    public $iconv = true;
    public $info = array("success" => false);
    static $name_parser;
    public $price_id = false;
    public $rename = true;
    public $document_extended, $decimal, $document_url,
        $document_urls, $mail, $curl, $document_list, $class_name,
        $filter, $filter_subset, $sheets_col,
        $head_non, $objPHPExcel, $document_name, $parametrical_start,
        $to_save, $to_save_new, $prices, $coef = 1;
    public $items = array();
    public $author = 'Миша Феликс';
    public $zip = false;
    public static $sergeyParserPath;
    public static $sergeyParserServer;
    public $curlPostData;

    public $site_link;
    public $tempFilePath;
    public $parse_time;
    public $batches;
    public $productCount;

    public function __construct($params = NULL)
    {
        if (is_numeric($params) || is_null($params)) {
            $this->city_id = $params;
        } elseif (count($params) == 4) {
            if (strpos($params['n'], 'CPar') !== false) {
                $this->class_name = $params['n'];
            } else {
                $this->class_name = 'CParSergeyParser';
                if ($params['n']{0} == "y") {
                    $this->class_name = "CParUniYml";
                    $params['n'] = substr($params['n'], 1);
                } elseif ($params['n']{0} == "z") {
                    $this->class_name = "CParUniYmlEvraz";
                    $params['n'] = substr($params['n'], 1);
                } elseif ($params['n']{0} == "d") {
                    $this->class_name = "CParUniYmlND";
                    $params['n'] = substr($params['n'], 1);
                } elseif ($params['n']{0} == "m") {
                    $this->class_name = "CParUniYml_model";
                    $params['n'] = substr($params['n'], 1);
                }
                self::$sergeyParserPath = $params['n'];
                self::$sergeyParserServer = $params['c'];
                $params['n'] = $this->class_name;
            }
        } else {
            $this->class_name = $params;
        }
        $this->parametrical_start = $params;
        //if(!self::$kursUSD)self::$kursUSD = $this->getKursUSD();
        //if(!self::$kursEUR)self::$kursEUR = $this->getKursEUR();
    }

    /*---------------------------------------*/

    public function executePT($parsers)
    {
        foreach ($parsers as $parser) {
            $name_parser = $parser;
            try {
                $current_parser = new $parser;
                if ($this->parametrical_start['n'] !== 'CParSergeyParser') {
                    $this->updateDescription($current_parser);
                }
                $current_parser->start();
                $current_parser->savePT($this->parametrical_start);
                $this->info['url'] = $current_parser->our_link . $current_parser->dirArray['full'] . '/' . $current_parser->document_name;
                $this->info['success'] = 'true';
            } catch (Exception $e) {
                $this->info['reason'] = $e->getMessage() . ' ' . $name_parser;
                $this->info['success'] = 'false';
            }
        }
    }

    public function updateDescription($currentParser)
    {
        $dbConnection = new WorkDb($this->parametrical_start['c'] . 'Parsing');
        $this->db = $dbConnection->db;
        $urls = array();
        $description = '';
        if (isset($currentParser->document_url)) $urls = array($currentParser->document_url);
        if (isset($currentParser->document_urls)) $urls = $currentParser->document_urls;
        foreach ($urls as $key => $url) {
            $description .= '<a href="' . $url . '">' . $url . '</a><br>';
        }
        $parserData = array(
            'urls' => $urls,
            'description' => $description,
        );

        $querySelect = $this->db->prepare('SELECT companies.name as name, parsers.parserId FROM ' . $this->parametrical_start['c'] . '_parsingtool.parsers LEFT JOIN ' . $this->parametrical_start['c'] . '.companies ON ' . $this->parametrical_start['c'] . '.companies.companyId = ' . $this->parametrical_start['c'] . '_parsingtool.parsers.companyId WHERE parserId = ' . $this->parametrical_start['p']);
        $querySelect->execute();
        $result = $querySelect->fetch();
        $parserData['nameCompany'] = $result['name'];
        $begin = 'Парсер компании ';
        $end = ' (добавлен автоматически)';
        $insert = $this->db->prepare('UPDATE ' . $this->parametrical_start['c'] . '_parsingtool.parsers SET sources = :sources WHERE parserId = ' . $this->parametrical_start['p']);
        try {
            $this->db->beginTransaction();
            $sources = $begin . $parserData['nameCompany'] . $end . '<br>';
            $sources .= '<p>Сайт: <a href="http://' . parse_url(current($parserData['urls']), PHP_URL_HOST) . '">' . parse_url(current($parserData['urls']), PHP_URL_HOST) . '</a></p>';
            $sources .= '<p>' . $parserData['description'] . '</p>';
            $insert->bindParam(':sources', $sources);
            $insert->execute();
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
        }
    }

    public function insertData($datas, $stmt, &$i, $add_p)
    {

        if (!$this->iconv) {
            $datas['name'] = iconv("windows-1251", "utf-8", $datas['name']);
        }
        if (!$this->price_id || !is_numeric($this->price_id)) {
            //throw new Exception('Ошибка: не правильный price_id.');
        }
        /*
        if(!empty($item)){
            $item->{'retail'} = $datas['cost'];
            if($this->dual_cost || $this->decimal){
            if(count($datas['cost'])!=1){
                $item->{'wholesale'} = min($datas['cost']) != 0 ? min($datas['cost']) : '';
            }
            $item->{'retail'} = max($datas['cost']) != 0 ? max($datas['cost']) : "99999999999";
            }
            $item->Store();
        }*/
        $rawData = preg_replace('/\s+/iu', ' ', trim(str_replace("(продолжение)", "", $datas['name'])));
        $imagePath = $datas['imagePath'];
        $imageFileName = $datas['imageFileName'];
        $retail = $datas['cost'];
        if ($this->dual_cost || $this->decimal) {
            if (count($datas['cost']) != 1) {
                $trade = min($datas['cost']) != 0 ? str_replace(',', '.', min($datas['cost'])) : null;
            }
            $retail = max($datas['cost']) != 0 ? str_replace(',', '.', max($datas['cost'])) : null;
        }
        $stmt->bindParam($i++, $add_p[0]);
        $stmt->bindParam($i++, $add_p[1]);
        $stmt->bindParam($i++, $rawData);
        $stmt->bindParam($i++, $trade);
        $stmt->bindParam($i++, $retail);
        $stmt->bindParam($i++, $imagePath);
        $stmt->bindParam($i++, $imageFileName);
        return true;
    }

    public function savePT($parametrical_start)
    {
        $this->connectionDB($scheme = $parametrical_start['c']);
        $this->to_save = array();
        $this->to_save_new = array();
        if (empty($this->items)) {
            throw new Exception('Ошибка: Пустой массив items.');
        }
        if (TEST_RECOGNIZE) {
            //$option['l'] = '164';
            //$option['c'] = 'metal100';
            //$recognize = new RecognizeFile($option, $this->items);
            //$recognize->init();
            //$this->items = $recognize->output;
        }
        //p($this->items);
        //Категория  Подкатегория  Тип Диаметр Длина, размер Марка стали ГОСТ Кол-во Цена опт Ценароз Примечания
        $this->to_save[0] = 'Прокат; Наименование; Вид; характеристика; Длина; Марка стали; ГОСТ; Кол; Цена опт; Цена роз; Примечание; Наименование Организации; цена1; цена2; Розн; Путь к файлу; Имя файла';
        $parserId = $parametrical_start['p'];
        $logEntryId = $parametrical_start['l'];
        $rawData = $retail = $trade = '';
        /*$query = "INSERT INTO foo (key1, key2) VALUES "; //Prequery
        $qPart = array_fill(0, count($data), "(?, ?)");
        $query .=  implode(",",$qPart);
        $stmt = $dbh -> prepare($query);
        $i = 1;
        foreach($data as $item) { //bind the values one by one
            $stmt -> bindParam($i++, $item['key1']);
            $stmt -> bindParam($i++, $item['key2']);
        }
    $stmt -> execute(); //execute
         * */
        $query = 'INSERT INTO prices_temp (parserId, logEntryId, rawData, price1, price2, imagePath, imageFileName) VALUES ';
        $qPart = array_fill(0, count($this->items), "(?, ?, ?, ?, ?, ?, ?)");
        $query .= implode(",", $qPart);
        $stmt = $this->db->prepare($query);
        $i = 1;
        $add_p = array($parserId, $logEntryId);
        foreach ($this->items as $datas) {
            $string['category'] = '';
            $string['subcategory'] = '';
            $string['type'] = '';
            $string['size'] = '';
            $string['length'] = '';
            $string['mark'] = '';
            $string['gost'] = '';
            $string['count'] = '';
            $string['cost_whosail'] = '';
            $string['cost_retail'] = '';
            $string['other'] = '';
            $string['begin'] = '';
            $string['cost1'] = '';
            $string['cost2'] = '';
            $string['rozn'] = '';

            if (empty($datas)) {
                continue;
            }
            $datas['name'] = trim(str_replace('Ø', '', $datas['name']));
            $string['begin'] = $datas['name'];
            //$datas_refresh = array();
            if ($this->dual_cost) {
                $datas['cost'] = array_map(create_function('$v', 'return (string) $v != "" ? $v * ' . $this->coef . ' : "";'), $datas['cost']);
                $datas_refresh = $datas;
                $string['cost_retail'] = max($datas['cost']);
                $string['cost_whosail'] = min($datas['cost']);
                $datas['cost'] = implode(';', $datas['cost']);
            } elseif ($this->decimal) {
                $datas['cost'] = array_map(create_function('$v', 'return (string) $v != "" ? str_replace(".",",",$v *' . $this->coef . ') : "";'), $datas['cost']);
                $datas_refresh = $datas;
                $string['cost_retail'] = floatval(max($datas['cost']));
                $string['cost_whosail'] = floatval(min($datas['cost']));
                $datas['cost'] = implode(';', $datas['cost']);
            } else {
                $datas['cost'] = str_replace('.', ',', $datas['cost'] * $this->coef);
                $string['cost_retail'] = $datas['cost'];
                $string['cost_whosail'] = '';
                $datas_refresh = $datas;
            }
            $string_to_save = $datas['name'] . ';' . $datas['cost'];
            if (isset($datas['cat']) && !empty($datas['cat'])) {

                foreach ($datas['cat'] as $catId => $tree) {
                    switch ($tree['type']) {
                        case 'CATEGORY':
                            $string['category'] = $tree['name'];
                            break;
                        case 'SUBCATEGORY':
                            $string['subcategory'] = $tree['name'];
                            break;
                        case 'TYPE':
                            $string['type'] = $tree['name'];
                            break;
                        case 'SIZE':
                            $string['size'] = $tree['name'];
                            break;
                    }
                    //$string_to_save .= ';'.$tree['name'];
                }
            }

            if (isset($datas['param']) && !empty($datas['param'])) {
                if (count($datas['cost']) == 2) {
                    //$string_to_save .= ';';
                } elseif (count($datas['cost']) == 3) {
                    //$string_to_save .= '';
                } else {
                    //$string_to_save .= ';;';
                }
                foreach ($datas['param'] as $type => $tree) {
                    switch ($type) {
                        case 'STEEL':
                            $string['mark'] = $tree['name'];
                            break;
                        case 'LENGTH':
                            $string['length'] = $tree['name'];
                            break;
                        case 'STANDART':
                            $string['gost'] = $tree['name'];
                            break;
                        case 'NOTE':
                            $string['other'] = $tree['name'];
                            break;
                    }
                    //$string_to_save .= ';'.$tree['name'];
                }
            }
            $string['imagePath'] = 'URL';
            $string['imageFileName'] = $datas['picture'];
            $datas_refresh['imagePath'] = 'URL';
            $datas_refresh['imageFileName'] = $datas['picture'];
            $string_to_save = implode(';', $string);
            if (LOCAL_RUN_PARSER_PT) {
                $this->insertData($datas_refresh, $stmt, $i, $add_p);
            }
            $this->to_save[] = $string_to_save;
        }
        //p($datas);
        if (LOCAL_RUN_PARSER_PT && !$stmt->execute()) {
            throw new Exception($this->db->errorCode() . ' SQL query: INSERT INTO prices_temp (parserId, logEntryId, rawData, price1, price2, imagePath, imageFileName)');
        }
        //$this->csv();
    }

    public function connectionDB($scheme)
    {
        if (!empty($scheme)) {
            $dbConnection = new WorkDb($scheme . 'Parsing');
        } else {
            throw new Exception('Не верный параметр C');
        }
        //$dbConnection = !empty($this->parametrical_start['c']) ? new WorkDb($this->parametrical_start['c']) : throw new Exception('Не верный параметр C');
        $this->db = $dbConnection->db;
    }

    public function startPT()
    {
        $this->parametersLoad();
        $parsers_all = array($this->parametrical_start['n']);
        $this->executePT($parsers_all);
        //if($this->parametrical_start) $this->sendMail();
    }
    /*---------------------------------------*/

    /*
     * Функции для работы парсера
     */
    public function parametersLoad()
    {
        $this->formDirsArray()->createDirs();
    }

    public function start()
    {
        $this->parametersLoad();
        $parsers_all = $this->getParsers();
        $this->execute($parsers_all);
        //if($this->parametrical_start) $this->sendMail();
    }

    public function formDirsArray()
    {
        foreach ($this->cities_list as $city_id => $city) {
            $this->dirArray[] = '/files/' . current(array_keys($city));
        }
        return $this;
    }

    public function createDirs()
    {
        foreach ($this->dirArray as $dir) {
            if (is_dir(ROOT . $dir))
                @rmdir(ROOT . $dir);
            #@mkdir(ROOT . $dir);
            @mkdir(ROOT . $dir, 0755, true);
        }
    }

    public function execute($parsers)
    {
        $id_city_old = 0;
        foreach ($parsers as $parser) {
            if (is_array($parser)) {
                $this->execute($parser);
                continue;
            }
            $name_parser = $parser;
            p($name_parser . ' ');
            try {
                $current_parser = new $parser;
                /*$mail = */
                print_r($current_parser);
                $current_parser->start();
                /*$this->mail['m'][current(array_keys($mail))][] = current(array_values($mail));*/
            } catch (Exception $e) {
                $this->sendError($e->getMessage() . ' Парсер: ' . current(array_values($name_parser::$name_parser)) . ' (' . $name_parser . ') Автор: ' . $this->author);
                echo $e->getMessage() . ' Парсер: ' . current(array_values($name_parser::$name_parser)) . ' (' . $name_parser . ') Автор: ' . $this->author;
            }
            /*            if(!$this->parametrical_start && end($this->list_parsers[current(array_keys($mail))]) == $name_parser){
                    $this->sendMail();
                    }*/
            echo 'Memory used :' . convert(memory_get_usage(true)) . ' ';
        };
        echo 'Memory peak used :' . convert(memory_get_peak_usage(true)) . ' ';
    }

    /*
    ----------------------------------
    -------Постраничный парсинг-------
    ----------------------------------
    */

    # Основные ф-ции
    function gettingUrls($links, $data, $dualData = false, $shortparse = false) {
    $multiHandle = curl_multi_init();
    $curlHandles = [];
    if ($data["big_data"] || $data["title"] === "Товары") $productsData = 0;
    else $productsData = [];


    foreach ($links as $index => $link) {
        $this->logMessage($link);
        $ch = curl_init($link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CAINFO, ROOT . DIRECTORY_SEPARATOR . "curl_crt" . DIRECTORY_SEPARATOR . "cacert.pem");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getRandomUserAgent());
        curl_multi_add_handle($multiHandle, $ch);
        if ($dualData === true) $curlHandles[] = ["category" => $link, "handle" => $ch];
        else $curlHandles[] = $ch;


        if (($index + 1) % $this->batches === 0) {
            $this->executeCurlRequests($data, $multiHandle, $curlHandles, $productsData, $dualData, $shortparse);

            if ($data["big_data"] || $data["title"] === "Товары") $this->logMessage("На данный момент при обработке {$data['log']} найдено объектов: " . $productsData);
            else $this->logMessage("На данный момент при обработке {$data['log']} найдено объектов: " . count($productsData));
            sleep(0.3);
        }
    }
    if (!empty($curlHandles)) $this->executeCurlRequests($data, $multiHandle, $curlHandles, $productsData, $dualData, $shortparse);
    curl_multi_close($multiHandle);

    if ($data["big_data"]) $this->logMessage("Парсинг {$data['log']} завершен. Всего обработано: " . $productsData);
    else $this->logMessage("Парсинг {$data['log']} завершен. Всего обработано: " . count($productsData));

    return $productsData;
}
    private function executeCurlRequests($data, $multiHandle, &$curlHandles, &$productsData, $dualData, $shortparse) {
        $keys = [];
        $htmlData = [];
        $running = null;

        do {
            curl_multi_exec($multiHandle, $running);
            curl_multi_select($multiHandle);
        } while ($running > 0);

        foreach ($curlHandles as $curlData) {
            if ($dualData === true) {
                $ch = $curlData["handle"];
                $category = $curlData["category"];
                $html = curl_multi_getcontent($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode === 200) {
                    $keys[] = $category;
                    $htmlData[$category] = $html;
                } elseif ($httpCode === 429) {
                    $this->logMessage("Слишком много запросов для: " . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) . ". Ожидание 60 секунд.");
                    sleep(60);
                } else $this->logMessage("Ошибка при запросе: $httpCode для ссылки: " . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));
            }
            else {
                $ch = $curlData;
                $html = curl_multi_getcontent($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($httpCode === 200) {
                    $htmlData[] = $html;
                } elseif ($httpCode === 429) {
                    $this->logMessage("Слишком много запросов для: " . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) . ". Ожидание 60 секунд.");
                    sleep(60);
                } else $this->logMessage("Ошибка при запросе: $httpCode для ссылки: " . curl_getinfo($ch, CURLINFO_EFFECTIVE_URL));

            }
            curl_multi_remove_handle($multiHandle, $ch);
            curl_close($ch);
        }

        $this->queryElements($htmlData, $data, $productsData, $keys, $dualData, $shortparse);
        $curlHandles = [];
    }
    private function queryElements($productsHtml, $data, &$productsData, $keys = null, $dualData, $shortparse) {

        #-----------------------------------------------------------------------------------------------------------------------------------------
        #
        # Основная ф-ция обработки резов разделена на 4 модуля :
        # 1. Категории - сбор ссылок на категории
        # 2. Пагинация - сбор ссылок на каждую страницу с товарами в категории
        # 3. Ссылок на товары - при коротком парсинге : Сбор инфы про товары , при длинном : сбор ссылок на товары по каждой ссылке из пагинации
        # 4. Товары(При использовании детального парсинга) Собирает инфу о товаре с его страницы
        #
        #-----------------------------------------------------------------------------------------------------------------------------------------
        #
        # Памятка по массиву $data
        # "log" - Что будет написано в логах при выполнении этого этапа
        # "title" - Наименование категории
        #
        # 1."title" = Категории:
        #   "category_selector" - Селектор для категории
        #
        # 2."title" = Пагинация:
        #   "paginate_selector" - Селектор на навигацию на странице
        #   "last_button_id" - Порядок с конца кнопки в навигации, которая отвечает за последнюю страницу
        #   "url_argument" - Аргумент под которым передается номер страницы в url
        #   "html_argument" - Аргумент, с помощью которого извлекаем из элемента номер последней странциы
        #
        # 3."title" = Ссылки на товары:
        #   "title_selector" - Массив селекторов, где может храниться ссылка на товар(берется первый, корректно отработавший)
        #   "title_html_argument" - аргумент для ссылки
        #   "price_html_argument" - аргумент для цены
        #   "price_selector" - Массив селекторов, где может быть цена на товар
        #   "price_ban_list" - Массив с недопустимыми ценами
        #   "big_data" - если хотим сохранять данные в промежуточный файл во время парсинга
        #
        # 4."title" = Товары:
        #   "price_selector" - Массив селекторов, где может быть цена на товар
        #   "title_selector" - Массив селекторов, где может быть цена на товар
        #   "title_html_argument" - аргумент для ссылки
        #   "price_html_argument" - аргумент для цены
        #   "price_order" - При наличии нескольких цен на товар, порядок какую брать(с начала)
        #   "big_data" - если хотим сохранять данные в промежуточный файл во время парсинга
        #
        # ----------------------------------------------------------------------------------------------------------------------------------------
        # Очистка цены
        $clean_price = function ($price) {
            $price = preg_replace('/\/.*/', '', $price);
            $price = preg_replace('/[^\d,.-]/', '', $price);
            $price = str_replace(',', '.', $price);
            $price = (float)$price;
            $price = floor($price);
            $price = number_format($price, 2, '.', '');
            return $price;
        };
        $price_valid = function ($price, $ban_list) {

            if ($price === "0.00") $valid = false;
            else $valid = true;
            return $valid;

//            if (count($ban_list) > 0) {
//                // Если бан-прайсов больше одного
//                $pattern = '/' . implode('|', array_map(function ($item) {
//                        return preg_quote($item, '/');
//                    }, $ban_list)) . '/';
//                if (!(preg_match($pattern, $price)) && (!empty($price))) $valid = true;
//
//            } else {
//                # Бан-прайс 1
//                switch ($ban_list[0]) {
//                    case "0.00":
//                        if (!($price === $ban_list[0])) $valid = true;
//                        break;
//                    default:
//                        $pattern = '/' . preg_quote($ban_list[0], "/") . "/";
//                        if (!(preg_match($pattern, $price)) and (!empty($price))) $valid = true;
//                        break;
//                }
//            }
        };
        $choose_selector = function ($xpath, $productsHtml) use ($data) {
            if ($data["title_selector"] == "link") {
                for ($i = 0; $i < count($data["price_selector"]); $i++) {
                    if (count($data["price_selector"]) > 0) $priceNodes = $xpath->query($data["price_selector"][$i]);
                    break;
                }
                $titleNodes = [array_keys($productsHtml)];
            } else {
                for ($i = 0; $i < count($data["title_selector"]); $i++) {
                    $titleNodes = $xpath->query($data["title_selector"][$i]);
                    if (count($data["description_selector"]) > 0) $descriptionNodes = $xpath->query($data["description_selector"][$i]);

                    if ($titleNodes->length > 0) {
                        if (count($data["price_selector"]) > 0) $priceNodes = $xpath->query($data["price_selector"][$i]);
                        break;
                    }
                }
            }
            return [$titleNodes ?? null, $priceNodes ?? null, $descriptionNodes ?? null];
        };
        $price_title_clear = function ($titleNode, $priceNode, $descriptionNode, $file) use ($clean_price, $price_valid, $shortparse, $data, &$productsData) {
            # Извлекаем наименование
            if (!is_string($titleNode)) {
                if ($data["title_html_argument"]) $title = $this->site_link . trim($titleNode->getAttribute($data["title_html_argument"]));
                else $title = trim($titleNode->nodeValue);
            } else
                $title = $titleNode;

            if ($data["price_html_argument"]) $price = trim($priceNode->getAttribute($data["price_html_argument"]));
             else $price = trim($priceNode->nodeValue);

            if ($descriptionNode) $title = $title . " " . $descriptionNode->nodeValue;

            # Работаем с ценой
            $price = $clean_price($price);
            $valid = $price_valid($price, $data["price_ban_list"]);


            if ($valid) {
                if (!$shortparse && $data["title"] === "Ссылки на товары") $productsData[] = $title;
                else {
                    if (data["big_data"]) {
                        fputcsv($file, [$title, $price]);
                        $productsData++;
                    } else {
                        $this->items[] = array("name" => $title, "cost" => $price);
                        $productsData++;
                    }
                }
            }
        };

        foreach ($productsHtml as $key => $html) {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($html);
            libxml_clear_errors();
            $xpath = new DOMXPath($dom);
            if ($data["big_data"]) {
                $size = filesize($this->tempFilePath);
                $file = fopen($this->tempFilePath, 'a');
                if ($size === false) fputcsv($file, ['Title', 'Price']);

            }

            if ($data["title"] === "Пагинация") {
                # Nodes
                $paginationItems = $xpath->query($data["paginate_selector"]);
                $paginationLinks = ["$key"];

                # Нахождение последней страницы
                if ($paginationItems->length >= 2) {
                    $penultimateItem = $paginationItems->item($paginationItems->length - $data["last_button_id"]);
                    if ($data["html_argument"] === "nodeValue") $last_page = $penultimateItem->nodeValue;
                    if ($data["html_argument"] === "href/") {
                        $url = $penultimateItem->getAttribute("href");
                        preg_match('/\/page\/(\d+)\//', $url, $matches);
                        $last_page = $matches[1];
                    }
                    if ($data["html_argument"] === "href=") {
                        $url = $penultimateItem->getAttribute("href");
                        $page_arg = preg_quote($data["url_argument"], "/");
                        preg_match("/{$page_arg}(\d+)/", $url, $matches);
                        $last_page = $matches[1];
                    }
                }
                else {
                    $this->logMessage("Найдено " . count($paginationLinks) . " ссылок на пагинацию для категории: " . $key);
                    $productsData = array_merge($productsData, $paginationLinks);
                    continue;
                }

                # Создание ссылок на все страницы
                for ($i = 2; $i <= (int)$last_page; $i++) {
                    $current_page = "$key/{$data['url_argument']}$i";
                    if (!in_array($current_page, $paginationLinks)) $paginationLinks[] = $current_page;

                }
                $this->logMessage("Найдено " . count($paginationLinks) . " ссылок на пагинацию для категории: " . $key);
                $productsData = array_merge($productsData, $paginationLinks);
            }
            elseif ($data["title"] === "Категории") {
                # Категории
                $categoryLinks = $xpath->query($data["category_selector"]);
                foreach ($categoryLinks as $link) $productsData[] = $this->site_link . $link->nodeValue;

            }
            elseif ($data["title"] === "Ссылки на товары") {
                if ($shortparse === true) {
                    $nodes = $choose_selector($xpath, null);
                    $titleNodes = $nodes[0];
                    $priceNodes = $nodes[1];
                    $descriptionNodes = $nodes[2];
                    if ($titleNodes->length > 0 && $priceNodes->length > 0) {
                        for ($i = 0; $i < $titleNodes->length; $i++) {
                            $priceNode = $priceNodes->item($i);
                            $titleNode = $titleNodes->item($i);
                            if ($descriptionNodes->length > 0) $descriptionNode = $descriptionNodes->item($i);

                            if ($titleNode && $priceNode) $price_title_clear($titleNode, $priceNode, $descriptionNodes, $file);
                        }
                    }
                } else {
                    # Парсинг ссылок
                    if (count($data["price_selector"]) > 0) {
                        $nodes = $choose_selector($xpath, null);
                        $titleNodes = $nodes[0];
                        $priceNodes = $nodes[1];
                        $descriptionNodes = $nodes[2];
                        if ($titleNodes->length > 0 && $priceNodes->length > 0) {
                            for ($i = 0; $i < $titleNodes->length; $i++) {
                                $priceNode = $priceNodes->item($i);
                                $titleNode = $titleNodes->item($i);
                                if ($descriptionNodes->length > 0) $descriptionNode = $descriptionNodes->item($i);
                                if ($titleNode && $priceNode) $price_title_clear($titleNode, $priceNode, $descriptionNodes, $file);
                            }
                        }
                    }
                    else {
                        # Парсинг ссылок без проверки цены
                        # Подбираем нужный селектор для сайтов с разной структурой html
                        $nodes = $choose_selector($xpath, null);
                        $titleNodes = $nodes[0];
                        for ($i = 0; $i < $titleNodes->length; $i++) {
                            $titleNode = $titleNodes->item($i);

                            if ($titleNode) {
                                $title = $this->site_link . $titleNode->getAttribute("href");
                                $productsData[] = $title;
                            }
                        }
                    }
                }
            }
            elseif ($data["title"] === "Товары") {

                if ($data["title_selector"] === "link") {
                    $nodes = $choose_selector($xpath, $productsHtml);
                    $priceNodes = $nodes[1];
                    $descriptionNodes = $nodes[2];
                    if ($priceNodes->length > 0) {
                        $titleNode = $key;
                        $priceNode = $priceNodes->item($i);
                        if ($descriptionNodes->length > 0) $descriptionNode = $descriptionNodes->item($i);

                        if ($titleNode && $priceNode) $price_title_clear($titleNode, $priceNode, $descriptionNodes, $file);
                    }
                } else {
                    $nodes = $choose_selector($xpath, $productsHtml);
                    $titleNodes = $nodes[0];
                    $priceNodes = $nodes[1];
                    $descriptionNodes = $nodes[2];
                    if ($titleNodes->length > 0 && $priceNodes->length > 0) {
                        $priceNode = $priceNodes->item($data["price_order"]);
                        $titleNode = $titleNodes->item($i);
                        if ($descriptionNodes->length > 0) $descriptionNode = $descriptionNodes->item($i);
                        if ($titleNode && $priceNode) $price_title_clear($titleNode, $priceNode, $descriptionNodes, $file);
                    }
                }
            }
            if ($data["big_data"] ?? false) {
                fclose($file);
            }

        }
    }

    # Доп ф-ции
    public function parseSave($big_data = true) {
        if ($big_data) {
            if (($handle = fopen($this->tempFilePath, "r")) !== false) {
                $header = fgetcsv($handle);
                while (($row = fgetcsv($handle)) !== false) {
                    if (strpos($row[0], 'https') === 0) {
                        if (!in_array($row[0], array_column($this->items, 'name'))) {
                            $this->items[] = array("name" => $row[0], "cost" => $row[1]);
                            $count++;
                        }
                    } else {
                        $this->items[] = array("name" => $row[0], "cost" => $row[1]);
                        $count++;
                    }
                }
                fclose($handle);
                #unlink($this->tempFilePath);
            }
        }
        $minutes = floor($this->parse_time / 60);
        $seconds = $this->parse_time % 60;
        $peakMemory = memory_get_peak_usage(true) / 1024 / 1024;
        $this->logMessage("Пиковое использование: {$peakMemory}MB.");
        $this->logMessage("Время выполнения: {$minutes} минут(ы) и " . round($seconds, 2) . " секунд(ы)");
        $this->logMessage("Всего товаров спарсено : $this->productCount");
    }
    public function logMessage($message) {
        echo date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    }
    public function getRandomUserAgent() {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0.5481.178 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.5359.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.5249.119 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 12_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.64 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.90 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:101.0) Gecko/20100101 Firefox/101.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:98.0) Gecko/20100101 Firefox/98.0',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:92.0) Gecko/20100101 Firefox/92.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:91.0) Gecko/20100101 Firefox/91.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 11.0; rv:83.0) Gecko/20100101 Firefox/83.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 11_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Safari/605.1.15',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.1.2 Safari/605.1.15',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.818.42 Safari/537.36 Edg/90.0.818.42',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.705.50 Safari/537.36 Edg/88.0.705.50',
            'Mozilla/5.0 (Linux; Android 10; SM-G973F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.152 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Mobile Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (iPad; CPU OS 14_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36 OPR/81.0.4196.31',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36 OPR/78.0.4093.147',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36 OPR/78.0.4093.147',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; AS; rv:11.0) like Gecko',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; Media Center PC 6.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; InfoPath.3; .NET4.0C; .NET4.0E; Zune 4.7)',
        ];
        return $userAgents[array_rand($userAgents)];
    }
    public function formDirArray() {
        $s = DIRECTORY_SEPARATOR;
        $name = static::$name_parser;
        $this->dirArray["root"] = $s . "files" . $s . current(array_keys($this->cities_list[$this->city_id])) . $s . current(array_keys($name));
        $this->dirArray["temp"] = $this->dirArray["root"] . $s . "temporary";
        $this->tempFilePath = ROOT . $this->dirArray["temp"] . $s . "temp_products.csv";
        unlink($this->tempFilePath);

        $this->createDirs();
    }

    /*
   ----------------------------------
   -------Постраничный парсинг-------
   ----------------------------------
   */

    public function csv($path_to_save = false)
    {
        $this->saveCsv($this->to_save, 'full', $path_to_save);
        if (!empty($this->to_save_new)) {
            $temp = $this->document_name;
            $this->document_name = 'new_pos_' . $this->document_name;
            $this->saveCsv($this->to_save_new, 'new_pos');
            $this->document_name = $temp;
        }
    }

    public function saveCsv($to_save, $type = 'full', $path_to_save = false)
    {
        ///$name = ROOT.'/'.$this->dirArray[$type].'/'.$this->document_name;
        if ($path_to_save) {
            $name = $path_to_save;
        } else {
            $name = ROOT . '/' . $this->dirArray[$type] . '/' . $this->document_name;
        }
        file_put_contents($name, '');
        $csv = new CSV($name);
        if ($this->iconv) {
            $csv->setCSV(array_map(create_function('$v', 'return iconv("utf-8", "windows-1251", $v);'), $to_save));
        } else {
            $csv->setCSV($to_save);
        }
        //$csv->setCSV(array_map(create_function('$v', 'return iconv("utf-8", "windows-1251", $v);'), $to_save));
    }

    public function obNullPosition()
    {
        $obNull = new DJEMForeach(R('DJEM'));
        $obNull->Path('main.metal.price.*')->Where('_link1=' . $this->price_id)->Fields('retail, wholesale');
        foreach ($obNull as $item) {
            $item->{'wholesale'} = "99999999999";
            $item->{'retail'} = "99999999999";
            $item->Store();
        }
    }

    public function refreshPosition($datas)
    {
        //return false;
        $null = new DJEMForeach(R('DJEM'));
        ///p($data['name']);
        if (!$this->iconv) {
            $datas['name'] = iconv("windows-1251", "utf-8", $datas['name']);
        }
        if (!$this->price_id || !is_numeric($this->price_id)) {
            throw new Exception('Ошибка: не правильный price_id.');
        }
        $null->Path('main.metal.price.*')->Where('_link1=' . $this->price_id . ' AND old_price = "' . preg_replace('/"/', '', $datas['name']) . '"')->Fields('old_price, retail, wholesale');
        //$null->Path('main.metal.price.*')->Where('_link1=? && old_price = "?"', $this->price_id, preg_replace('/"/','',$datas['name']))->Fields('old_price, retail, wholesale');
        //print_r($null);
        $flag_of_new_pos = true;
        foreach ($null as $item) {
            $flag_of_new_pos = false;
            if (!empty($item)) {
                $item->{'retail'} = $datas['cost'];
                if ($this->dual_cost || $this->decimal) {
                    if (count($datas['cost']) != 1) {
                        $item->{'wholesale'} = min($datas['cost']) != 0 ? min($datas['cost']) : '';
                    }
                    $item->{'retail'} = max($datas['cost']) != 0 ? max($datas['cost']) : "99999999999";
                }
                $item->Store();
            }
        }
        return $flag_of_new_pos;
    }

    public function save($path_to_save = false)
    {
        if (!ISSERVER) {
            $this->to_save = array();
            $this->to_save_new = array();
            if (empty($this->items)) {
                throw new Exception('Ошибка: Пустой массив items.');
            }
            //if(ISSERVER) $this->obNullPosition();
            //Категория  Подкатегория  Тип Диаметр Длина, размер Марка стали ГОСТ Кол-во Цена опт Ценароз Примечания
            $this->to_save[0] = 'Прокат; Наименование; Вид; характеристика; Длина; Марка стали; ГОСТ; Кол; Цена опт; Цена роз; Примечание; Наименование Организации; цена1; цена2; Розн; Путь до файла; Имя файла';
            foreach ($this->items as $datas) {
                $string['category'] = '';
                $string['subcategory'] = '';
                $string['type'] = '';
                $string['size'] = '';
                $string['length'] = '';
                $string['mark'] = '';
                $string['gost'] = '';
                $string['count'] = '';
                $string['cost_whosail'] = '';
                $string['cost_retail'] = '';
                $string['other'] = '';
                $string['begin'] = '';
                $string['cost1'] = '';
                $string['cost2'] = '';
                $string['rozn'] = '';
                $string['imagePath'] = $datas['imagePath'];
                $string['imageFileName'] = $datas['imageFileName'];
                if (empty($datas)) {
                    continue;
                }
                $datas['name'] = trim(str_replace('Ø', '', $datas['name']));
                $string['begin'] = $datas['name'];
                //$datas_refresh = array();
                if ($this->dual_cost) {
                    $datas['cost'] = array_map(create_function('$v', 'return (string) $v != "" ? $v * ' . $this->coef . ' : "";'), $datas['cost']);
                    $datas_refresh = $datas;
                    $string['cost_retail'] = max($datas['cost']);

                    if (count($datas['cost']) != 1) {
                        $string['cost_whosail'] = min($datas['cost']);
                    }
                    $datas['cost'] = implode(';', $datas['cost']);
                } elseif ($this->decimal) {
                    $datas['cost'] = array_map(create_function('$v', 'return (string) $v != "" ? str_replace(".",",",$v *' . $this->coef . ') : "";'), $datas['cost']);
                    $datas_refresh = $datas;
                    $string['cost_retail'] = max($datas['cost']);
                    if (count($datas['cost']) != 1) {
                        $string['cost_whosail'] = min($datas['cost']);
                    }
                    $datas['cost'] = implode(';', $datas['cost']);
                } else {
                    $datas['cost'] = str_replace('.', ',', $datas['cost'] * $this->coef);
                    $string['cost_retail'] = $datas['cost'];
                    $string['cost_whosail'] = '';
                    $datas_refresh = $datas;
                }
                $string_to_save = $datas['name'] . ';' . $datas['cost'];
                if (isset($datas['cat']) && !empty($datas['cat'])) {
                    if (count($datas['cost']) == 2) {
                        $string_to_save .= ';';
                    } elseif (count($datas['cost']) == 3) {
                        $string_to_save .= '';
                    } else {
                        $string_to_save .= ';;';
                    }
                    foreach ($datas['cat'] as $catId => $tree) {
                        switch ($tree['type']) {
                            case 'CATEGORY':
                                $string['category'] = $tree['name'];
                                break;
                            case 'SUBCATEGORY':
                                $string['subcategory'] = $tree['name'];
                                break;
                            case 'TYPE':
                                $string['type'] = $tree['name'];
                                break;
                            case 'SIZE':
                                $string['size'] = $tree['name'];
                                break;
                        }
                        $string_to_save .= ';' . $tree['name'];
                    }
                }
                $string_to_save = implode(';', $string);
                if (ISSERVER /*&& $this->refreshPosition($datas_refresh)*/) {
                    $this->to_save_new[] = $string_to_save;
                }
                $this->to_save[] = $string_to_save;
            }
            $this->csv($path_to_save);
        }
    }

    public function getParsers()
    {
        $parsers = array();
        if ($this->class_name) {
            return array($this->class_name);
        }
        if (!is_null($this->city_id)) {
            if (is_array($this->city_id)) {
                foreach ($this->city_id as $city_id) {
                    if (!empty($this->list_parsers[$city_id])) {
                        natsort($this->list_parsers[$city_id]);
                        $parsers[$city_id] = $this->list_parsers[$city_id];
                    }
                }
            } elseif (!empty($this->list_parsers[$this->city_id])) {
                natsort($this->list_parsers[$this->city_id]);
                $parsers = $this->list_parsers[$this->city_id];
            }
        }
        if (!empty($parsers)) {
            return $parsers;
        }
        return $this->list_parsers;
        //return !is_null($this->city_id) && !empty($this->list_parsers[$this->city_id]) ? $this->list_parsers[$this->city_id] : $this->list_parsers;
    }

    /*
     * Функции для работы с прайсами
     */
    public function getDocument($file_name = '', $extended_parameters = NULL)
    {
        $document_name = 'temp_' . md5(time()) . $this->document_extended;
        if ($file_name) {
            $document_name = $file_name . $this->document_extended;
        }
        $this->curl = curl_init($this->document_url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        if ($extended_parameters) {
            $this->document_list[$extended_parameters] = ROOT . '/' . $this->dirArray['temp'] . '/' . $document_name;
        } else {
            $this->document_list[] = ROOT . '/' . $this->dirArray['temp'] . '/' . $document_name;
        }
        switch ($this->price_type) {
            case 'web':
                $cookie = tempnam("/tmp", "CURLCOOKIE");
                curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                curl_setopt($this->curl, CURLOPT_COOKIEJAR, $cookie);
                curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                $result = curl_exec_follow($this->curl);
                echo curl_error($this->curl);
                if (!empty($result)) {
                    file_put_contents(end($this->document_list), $result);
                } else {
                    throw new Exception('Ошибка: cкачивания файла. ' . $this->document_url . ' ');
                }
                sleep(1);
                break;
            case 'safe':
                $connection = proxyConnector::getIstance();
                $connection->launch($this->document_url, null);
                $result = $connection->getProxyData();
                /*curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                $result = curl_exec_follow($this->curl);*/
                if (!empty($result['return'])) {
                    file_put_contents(end($this->document_list), $result['return']);
                } else {
                    throw new Exception('Ошибка: cкачивания файла. ' . $this->document_url . ' ');
                }
                sleep(1);
                break;
            case 'safe2':
                $cookie = tempnam("/tmp", "CURLCOOKIE");
                curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                curl_setopt($this->curl, CURLOPT_COOKIEJAR, $cookie);
                curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($this->curl, CURLOPT_MAXREDIRS, 100);
                if (!empty($this->curlPostData)) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->curlPostData);
                    curl_setopt($this->curl, CURLOPT_POST, 1);
                }
                $result = curl_exec($this->curl);
                if (!empty($result)) {
                    file_put_contents(end($this->document_list), $result);
                } else {
                    throw new Exception('Ошибка: скачивания файла. ' . $this->document_url . ' ');
                }
                break;

            case 'gzip':
                $cookie = tempnam("/tmp", "CURLCOOKIE");
                curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                curl_setopt($this->curl, CURLOPT_COOKIEJAR, $cookie);
                curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($this->curl, CURLINFO_HEADER_OUT, true);
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Accept-Encoding: gzip'));
                curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($this->curl, CURLOPT_MAXREDIRS, 100);
                if (!empty($this->curlPostData)) {
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->curlPostData);
                    curl_setopt($this->curl, CURLOPT_POST, 1);
                }
                $result = curl_exec($this->curl);
                if (!empty($result)) {
                    file_put_contents(end($this->document_list), gzinflate(substr($result, 10)));
                } else {
                    throw new Exception('Ошибка: скачивания файла. ' . $this->document_url . ' ');
                }
                break;
            case 'zip':
                $file = file_get_contents($this->document_url, FILE_USE_INCLUDE_PATH);
                file_put_contents(end($this->document_list), $file);
                break;
            case 'special':
                $file = fopen(end($this->document_list), "w");
                curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($this->curl, CURLOPT_FILE, $file);
                curl_setopt($this->curl, CURLOPT_HEADER, 0);
                curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)');
                curl_setopt($this->curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
                $result = curl_exec($this->curl);
                echo curl_error($this->curl);
                if (!empty($result)) {
                    file_put_contents(end($this->document_list), $result);
                } elseif ($result === false) {
                    throw new Exception('Ошибка: скачивания файла.' . curl_error($this->curl) . ' ' . $this->document_url . ' ');
                }
                break;
            default:
                $file = fopen(end($this->document_list), "w");
                curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($this->curl, CURLOPT_FILE, $file);
                curl_setopt($this->curl, CURLOPT_HEADER, 0);
                curl_setopt($this->curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)');
                $result = curl_exec($this->curl);
                //$error = curl_error($this->curl);
                if ($result === false) {
                    throw new Exception('Ошибка: скачивания файла.' . curl_error($this->curl) . ' ' . $this->document_url . ' ');
                }
                break;
        }

        curl_close($this->curl);
        unset($this->curl);
        return $this;
    }

    public function getDocuments()
    {
        if (!empty($this->document_urls)) {
            //echo count($this->document_urls );
            foreach ($this->document_urls as $key => $url) {
                //echo $key;
                $this->document_url = $url;
                //$this->price_id = $price_id;
                $document_name = 'temp_' . md5(time()) . '_' . $key . '_' . $this->document_extended;
                //echo $document_name.'<br />';
                $this->getDocument($document_name, $key);
            }
        }
        return $this;
    }

    public function documentLoad($path, $input_file_type = null)
    {
        if (!$input_file_type) $input_file_type = PHPExcel_IOFactory::identify($path);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
        $sheet = 0;
        $objReader = PHPExcel_IOFactory::createReader($input_file_type); // создаем объект для чтения файла
        $objReader->setReadFilter($this->filter_subset);
        $objReader->setReadDataOnly(true);
        $this->objPHPExcel = $objReader->load($path); // загружаем данные файла в объект]
        unset($objReader);
        foreach (range(0, $this->objPHPExcel->getSheetCount() - 1) as $i) {
            $sheet += count($this->objPHPExcel->getSheet($i)->toArray());
        }
        if ($sheet < $this->objPHPExcel->getSheetCount()) {
            throw new Exception('Ошибка: пустой массив $sheet для обработки.');
        }
    }

    /*
     * Функции отправки Емайла
     */
    /*public function setMessageMail(){
        $this->message .= '<br /><h4>'.$this->company_name.'  (City = '.current(array_values($this->cities_list[$this->city_id])).' Price id = '.$this->price_id.' link = '.$this->home_url.')</h4>';
        $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['full'].'/'.$this->document_name.'">FULL_POS ('.count($this->to_save).')</a>';
        if(!empty($this->to_save_new)){
            $this->message .= '<br /><a href="'.$this->our_link.$this->dirArray['new_pos'].'/new_pos_'.$this->document_name.'">NEW_POS ('.count($this->to_save_new).')</a>';
        }
    }*/
    public function sendError($message)
    {
        $mail['p'] = implode(', ', $this->emails);
        $mail['h'] = "Content-type: text/html; charset=windows-1251 \r\n";
        $mail['h'] .= "From:ebot@metal100.ru\r\n";
        $mail['s'] = iconv("utf-8", "windows-1251", 'Обнаруженна ошибка в прайсе!');
        $mail['m'] = iconv("utf-8", "windows-1251", $message);
        //$mail = array_map(create_function('$v', 'return ;'), $this->mail);
        @mail($mail['p'], $mail['s'], $mail['m'], $mail['h']);
    }

    public function sendMail()
    {
        $this->mail['p'] = $mail['p'] = implode(', ', $this->emails);
        //$this->mail['p'] = 'mrxan.ru@gmail.com';
        $this->mail['h'] = "Content-type: text/html; charset=windows-1251 \r\n";
        $this->mail['h'] .= "From:noreply@metal100.ru\r\n";
        $this->mail['s'] = '';
        $messages = $this->mail['m'];
        unset($this->mail['m']);
        foreach ($messages as $city_id => $message) {
            $this->mail['s'] = 'Обновление прайсов ' . current(array_values($this->cities_list[$city_id]));
            $this->mail = array_map(create_function('$v', 'return iconv("utf-8", "windows-1251", $v);'), $this->mail);
            if (ISSERVER) {
                $mailer = R('DJEMMail');
                $mailer->SetHtml(1);
                $mailer->Mail('windows-1251', 'no-reply@metal100.ru', $this->mail['p'], $this->mail['s'], iconv("utf-8", "windows-1251", implode('<br />', $message)));
                $mailer->Send();
                $mailer->ClearAll();
            } else {
                @mail($this->mail['p'], $this->mail['s'], iconv("utf-8", "windows-1251", implode('<br />', $message)), $this->mail['h']);
            }

        }
        $this->mail = array();
    }

    /*
     * Функции облегяающие жизнь
     */
    public function unZip($path)
    {
        //p($path);
        $path_to_save = ROOT . '/' . $this->dirArray['zip'] . '/';
        $zip = new ZipArchive(); //Создаём объект для работы с ZIP-архивами
        //Открываем архив archive.zip и делаем проверку успешности открытия
        if ($handle = opendir($path_to_save)) {
            while (false !== ($file = readdir($handle)))
                if ($file != "." && $file != "..") unlink($path_to_save . $file);
            closedir($handle);
        }
        if ($zip->open($path) === true) {
            $zip->extractTo($path_to_save); //Извлекаем файлы в указанную директорию
            $zip->close(); //Завершаем работу с архивом
            if ($this->rename) {
                if ($dh = opendir($path_to_save)) {
                    $n = 0;
                    while (($file = readdir($dh)) !== false) {
                        if ($n >= 2) {
                            rename($path_to_save . $file, $path_to_save . $n . '.xls');
                        }
                        $n++;
                    }
                    closedir($dh);
                }
            }
        } else throw new Exception("Архива не существует! "); //Выводим уведомление об ошибке
    }

    public function n($letter)
    {
        return ord($letter) - 65;
    }

    public function num($letters)
    {
        $numbers = array();
        foreach (str_split($letters) as $letter) {
            $numbers[] = ord($letter) - 65;
        }
        return $numbers;
    }

    public function propusk()
    {

    }

    public static function getKursUSD()
    {
        $html = file_get_html('http://www.cbr.ru/scripts/XML_daily.asp');
        if ($html) {
            $curs = str_replace(',', '.', $html->find('Valute#R01235 Value', 0)->plaintext);
        } else {
            $curs = self::getKursUSD();
        }
        return $curs;
    }

    public static function getKursEUR()
    {
        $html = file_get_html('http://www.cbr.ru/scripts/XML_daily.asp');
        if ($html) {
            $curs = str_replace(',', '.', $html->find('Valute#R01239 Value', 0)->plaintext);
        } else {
            $curs = self::getKursEUR();
        }
        return $curs;
    }
}

class CSV
{
    private $_csv_file = null;

    /**
     * @param string $csv_file - путь до csv-файла
     */
    public function __construct($csv_file)
    {
        if (file_exists($csv_file)) { //Если файл существует
            $this->_csv_file = $csv_file; //Записываем путь к файлу в переменную
        } else { //Если файл не найден то вызываем исключение
            throw new Exception("Файл \"$csv_file\" не найден");
        }
    }

    public function setCSV(array $csv)
    {
        //Открываем csv для до-записи,
        //если указать w, то  ифнормация которая была в csv будет затерта
        $handle = fopen($this->_csv_file, "a");

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
    public function getCSV()
    {
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

class MyReadFilter implements PHPExcel_Reader_IReadFilter
{
    var $from, $to, $range, $cols, $only;

    public function __construct($cost_column, $range = true)
    {
        $this->from['horizontal'] = $cost_column['horizontal']['from']['char'];
        $this->to['horizontal'] = $cost_column['horizontal']['to']['char'];
        $this->from['vertical'] = $cost_column['vertical']['from']['numeric'];
        $this->to['vertical'] = $cost_column['vertical']['to']['numeric'];
        $this->cols = !$range ? $cost_column['columns'] : array();
        $this->range = $range;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        if ($row >= $this->from['vertical']) {
            if (!is_null($this->to['vertical']) && $row > $this->to['vertical']) {
                return false;
            }
            if ($this->range) {
                if (in_array($column, range($this->from['horizontal'], $this->to['horizontal']))) {
                    return true;
                }
            } else {
                if (in_array($column, $this->cols)) {
                    return true;
                }
            }
        }
        return false;
    }
}

function clear_array($array)
{
    $new_array = array();
    if (empty($array)) {
        return array();
    }
    foreach ($array as $key => $value) {
        if ($value === NULL || $value == '' || trim($value) == '' || trim($value, '&nbsp;') == '') continue;
        $new_array[$key] = $value;

    }
    return $new_array;
}

class CParMainMC extends CParMain
{
    public $list_parsers = array(
        1 => array(
            'CParMcMoscow',
            'CParTSKKomplektologiyaMC',
            'CParStalProMc',
            'CParMetTransTumen',
        ),
        2 => array(
            'CParMcSpb'

        ),
        4 => array(
            "CParStalEnergo",
        ),
        5 => array(
            'CParTriadaMarketKazan',


        ),
        7 => array(
            'CParMcNn',
        ),
        8 => array(
            'CParMcNovosib',
        ),
        9 => array(
            'CParMcRostov',
            'CParMcRostovNew',
            'CParAlianceRostov',
            'CParAlianceRostovNew',
        ),
        21 => array(
            'CParMcKursk'
        ),
        23 => array(
            'CParMcBryansk',
        ),
        30 => array(
            'CParMcSamara',
            'CParTriadaMarketSamara',
        ),
        32 => array(
            'CParTriadaMarketVolgograd',
        ),
        35 => array(
            'CParMcKrasnodar',
        ),
        36 => array(
            'CParTriadaMarketUliyanovsk',
        ),
        37 => array(
            'CParMcBalakovo',
            'CParTriadaMarketSaratov'
        ),
        38 => array(
            'CParMcPenza',
            'CParTriadaMarketPenza',
        ),
        43 => array(
            'CParMcSmolensk'
        ),
        44 => array(
            'CParMcTaganrok'
        ),
        45 => array(
            'CParMcBarnaul'
        ),
        46 => array(
            'CParMcHabarovsk'
        ),
        53 => array(
            'CParMcChelyabinsk'
        ),
        55 => array(
            'CParTriadaMarketAstrahan'
        ),
        146 => array(
            'CParMcEkaterinburg'
        ),
        147 => array(
            'CParMcUfa'
        ),
        148 => array(
            'CParMcPerm'
        ),
        149 => array(
            'CParMcCheboksary'
        )
    );
}


