<?php
include_once "array.Cities.php";
class CreateAd{
    private $purifier;
    protected $db;
    public $ad;
    public $userId;
    public $configDb;
    public $pathToParsingTool;
    public function __construct($ad, $config = "metal100"){
        $this->ad = $ad;
        $this->configDb = $config;
        $this->pathToParsingTool = $config == "metal100" ? "https://pt.metal100.ru" : "https://pt.".$config.".ru";
    }
    public function init(){
        $purifierConfig = HTMLPurifier_Config::createDefault();
        $purifierConfig->set('AutoFormat.RemoveEmpty', true); //Вычищаем пустые теги
        $purifierConfig->set('AutoFormat.AutoParagraph', true);
        $purifierConfig->set('HTML.Nofollow', true); //Делаем ссылки недоступными для поисковиков
        $purifierConfig->set('HTML.Allowed', 'br,p,b,a[href],i,strong,em,p'); //Вычищаем все теги кроме перечисленных
        $this->setPurifier(new HTMLPurifier($purifierConfig));
        $this->connectionDB($this->configDb);
        $this->createAd();
    }

        // Create new Ad in database
    private function createAd()
    {
        // print_r($this->ad);
        if (! empty($this->ad['title']) && ! empty($this->ad['date'])) {
            $ad = $this->prepareAdFields($this->ad);
            if (! empty($ad['title']) && trim($ad['title']) != '' && $this->checkAd($ad)) {
                $ad['cities'] = (! isset($ad['cities']) || empty($ad['cities'])) ? $this->getCitiesFromAd($ad) : $ad['cities'];
                if (empty($ad['cities']))
                    $ad['cities'] = array(
                        4 => "Москва"
                    );
                $ad['email'] = (! isset($ad['email']) || empty($ad['email'])) ? $this->getEmailFromAd($ad) : $ad['email'];
                $ad['category'] = $this->getCategoryFromAd($ad);
                $ad['categories'] = $this->getCategoriesFromAd($ad);
                if (! isset($ad['type']))
                    $ad['type'] = "SELL";
                if (sizeof($ad['categories']) > 0 && isset($ad["categories"][1]) && !empty($ad["categories"][1])) {
                    if ($ad["categories"][1] == '') {
                        $ad["categories"][1] = null;
                    }
                    if (sizeof($ad['categories']) > 1 && $ad["categories"][2] == '') {
                        $ad["categories"][2] = null;
                    }
                    if (sizeof($ad['categories']) > 2 && $ad["categories"][3] == '') {
                        $ad["categories"][3] = null;
                    }
                    $this->insertAd($ad);
                }
            } else {
                print_r($ad);               
                echo 'We checked the ad it already exsist in the DB!';                
            }
        }
    }

    //Checks for dublicates
    function checkAd($ad) {
        
        $query = "SELECT adId FROM ads WHERE title = :title";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":title", $ad["title"]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($rows);
        
        if (count($rows) > 0)
            echo "Found similar ad with title: ".$ad["title"];
        
        $query = "SELECT name FROM stopwords WHERE LOCATE(LOWER(TRIM(name)), LOWER(:content))>0 LIMIT 1";
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(":content", $ad["title"]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count += count($rows);

        if (count($rows) > 0)
            echo "Found stopword '".$rows[0]["name"]."' in the ad title: ".$ad["title"];
            
        $stmt->bindParam(":content", $ad["email"]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count += count($rows);

        if (count($rows) > 0)
            echo "Found stopword '".$rows[0]["name"]."' in the ad email: ".$ad["email"];
            
        $stmt->bindParam(":content", $ad["phone"]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count += count($rows);

        if (count($rows) > 0)
            echo "Found stopword '".$rows[0]["name"]."' in the ad phone: ".$ad["phone"];
            
        $stmt->bindParam(":content", $ad["org"]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count += count($rows);

        if (count($rows) > 0)
            echo "Found stopword '".$rows[0]["name"]."' in the ad org: ".$ad["org"];
            
        $stmt->bindParam(":content", $ad["content"]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count += count($rows);

        if (count($rows) > 0)
            echo "Found stopword '".$rows[0]["name"]."' in the ad content: ".$ad["content"];
            
        return $count == 0 ? true : false;
    }

    //Fix, format and prepare fields before publishing
    function prepareAdFields($ad) {
        $ad['title'] = preg_replace('/(=)+/', '=',preg_replace('/(_)+/', '_',preg_replace('/\s+/', ' ',self::cutRestSymbols(strip_tags(trim(html_entity_decode($ad['title'], ENT_COMPAT | 48, 'UTF-8')))))));
        $transText = preg_replace('/(_)+/', '_', self::transUrl($ad['title']));
        $start = 0;
        if($transText{0} == '_') $start = 1;
        $ad['latinTitle'] = trim(substr($transText,$start,150));
        $ad['content'] = trim(html_entity_decode($ad['content'], ENT_COMPAT | 48, 'UTF-8'));
        $ad['address'] = strip_tags(trim(html_entity_decode($ad['address'], ENT_COMPAT | 48, 'UTF-8')));
        $ad['phone'] = strip_tags(trim(html_entity_decode($ad['phone'], ENT_COMPAT | 48, 'UTF-8')));
        $ad['org'] = strip_tags(trim(html_entity_decode($ad['org'], ENT_COMPAT | 48, 'UTF-8')));
        //Очищаем и фиксим html-код, удаляем все неизвестные кодеровке utf-8 символы
        $ad['content'] = $this->purifier->purify($ad['content']);
        return $ad;
    }

    //Returns array with cities
    function getCitiesFromAd($ad) {
        /*$content = strip_tags($ad['address']);
        $allCities = $this->getAllCities();
        $adCities = Array();
        $charsToScreen = '/ ';
        if(!empty($content)) {
            foreach ($allCities as $city) {
                if (strlen($city["name"]) > 0) {
                    preg_match('/' . addcslashes(trim($city["name"]), $charsToScreen) . '/iu', $content, $matches);
                    if(!empty($matches) && !empty($matches[0])) {
                        $adCities[$city["regionId"]] = $city["name"];
                        break;
                    }
                }
            }
        }*/

	    $content = strip_tags($ad['address']);
        $allCities = Cities::$cities;
        $adCities = Array();
        if(!empty($content)) {
            foreach ($allCities as $cityId => $patterns) {
	            foreach($patterns as $pattern){
	                preg_match($pattern.'iu', $content, $matches);
	                if(!empty($matches) && !empty($matches[0])) {
	                    $adCities[$cityId] = $pattern;
	                    break 2;
	                }
	            }
            }
        }
        return $adCities;
    }

    private function getAllCities(){
        $query = "SELECT name, regionId FROM regions WHERE visible = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    private function insertAd($ad){
        $query = "INSERT INTO ads (userId, categoryId, title, latinTitle, text, company, address, phone, email, regionId, created, type) VALUES (:userId, :categoryId, :title, :latinTitle, :text, :company, :address, :phone, :email, :regionId, :created, :type)";
        $stmt = $this->db->prepare($query);
        
        try {
	    $cityIds = array_keys($ad["cities"]);
            $cityId = end($cityIds);
	
            $this->db->beginTransaction();
            $stmt->bindParam(':userId', $this->userId);
            $stmt->bindParam(':categoryId', $ad["category"][0]);
            $stmt->bindParam(':title', $ad["title"]);
            $stmt->bindParam(':latinTitle', $ad["latinTitle"]);
            $content = preg_replace('@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)*)@', '', $ad["content"]);
            $stmt->bindParam(':text', $content);
            $stmt->bindParam(':address', $ad["address"]);
            $stmt->bindParam(':phone', $ad["phone"]);
            $stmt->bindParam(':company', $ad["org"]);
            $stmt->bindParam(':email', $ad["email"]);
	        $stmt->bindParam(':regionId', $cityId);
            $date  = date("Y-m-d H:i:s",$ad["date"] ? $ad["date"] : time());
            $stmt->bindParam(':created', $date);
            $stmt->bindParam(':type', $ad["type"]);
            $stmt->execute();
            $adId = $this->db->lastInsertId();
            $query = "INSERT INTO ad_categories (adId, categoryId) VALUES (:adId, :categoryId)";
            echo "Inserting ". sizeof($ad["categories"])." categories for ad N".$adId;
            foreach ($ad["categories"] as $category) {
                echo "Inserting category ".$category." for ad N".$adId;
                if (!empty($category) && $category!="" && $category!="null" && is_numeric($category)) {
                    $stmt2 = $this->db->prepare($query);
                    $stmt2->bindParam(':adId', $adId);
                    $stmt2->bindParam(':categoryId', $category);
                    $stmt2->execute();
                }
            }
            $this->db->commit();
            return $adId;
        } catch (Exception $e) {
            $this->db->rollBack();
            print_r($e->getMessage());
        }
    }

    function getCategoryFromAd($ad) {
        $result = $this->recognizeCategory($ad['title'].$ad['content']);
        $result_array = explode(" ",$result);
        if(sizeof($result_array) < 1 || !isset($result_array[0]) || empty($result_array[0]))
            return array();
        return $result_array;
    }

    //Returns array with categories
    function getCategoriesFromAd($ad) {
        $result = $this->recognizeCategories($ad['title'].$ad['content']);
        $result_array = explode(" ",$result);
        if(sizeof($result_array) < 1 || !isset($result_array[0]) || empty($result_array[0]))
           return array();
        return $result_array;
    }


    public function recognizeCategories($text){
        $curl = curl_init($this->pathToParsingTool."/identify/categoryIdForAds");
        $cookie = tempnam("/tmp", "CURLCOOKIE");
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "textString='".preg_replace("/\s+/"," ",preg_replace("/,\s/"," ", $text))."'&type=allCategories");
        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($result === false || in_array($httpcode, array(502, 500, 404, 400)) || $result == 0){
            return '';
        }
        return trim($result);
    }

    public function recognizeCategory($text){
        $curl = curl_init($this->pathToParsingTool."/identify/categoryIdForAds");
        $cookie = tempnam("/tmp", "CURLCOOKIE");
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie );
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "textString='".preg_replace("/\s+/"," ",preg_replace("/,\s/"," ", $text))."'&type=category");
        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($result === false || in_array($httpcode, array(502, 500, 404, 400)) || $result == 0){
            return '';
        }
        return trim($result);
    }


    function getEmailFromAd($ad) {
        $content = $ad['content'];
        $matches = array();
        $pattern = '/[A-Za-z0-9_-]+@[A-Za-z0-9_-]+\.([A-Za-z0-9_-][A-Za-z0-9_]+)/';
        preg_match($pattern,$content,$matches);
        if(sizeof($matches) > 0 && filter_var($matches[0], FILTER_VALIDATE_EMAIL)) {
            return $matches[0];
        } else {
            return '';
        }
    }

    function connectionDB($scheme){
        if(!empty($scheme)){
            $dbConnection = new WorkDb($scheme.'Site');
        }else{
            throw new Exception('Не верный параметр C');
        }
        $this->setDb($dbConnection->db);
    }

    /**
     * @return mixed
     */
    public function getPurifier()
    {
        return $this->purifier;
    }

    /**
     * @param mixed $purifier
     */
    public function setPurifier($purifier)
    {
        $this->purifier = $purifier;
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }
    static function  transUrl($str)
    {
        $tr = array(
            "А"=>"a",
            "Б"=>"b",
            "В"=>"v",
            "Г"=>"g",
            "Д"=>"d",
            "Е"=>"e",
            "Ё"=>"e",
            "Ж"=>"j",
            "З"=>"z",
            "И"=>"i",
            "Й"=>"y",
            "К"=>"k",
            "Л"=>"l",
            "М"=>"m",
            "Н"=>"n",
            "О"=>"o",
            "П"=>"p",
            "Р"=>"r",
            "С"=>"s",
            "Т"=>"t",
            "У"=>"u",
            "Ф"=>"f",
            "Х"=>"h",
            "Ц"=>"ts",
            "Ч"=>"ch",
            "Ш"=>"sh",
            "Щ"=>"sch",
            "Ъ"=>"",
            "Ы"=>"i",
            "Ь"=>"j",
            "Э"=>"e",
            "Ю"=>"yu",
            "Я"=>"ya",
            "а"=>"a",
            "б"=>"b",
            "в"=>"v",
            "г"=>"g",
            "д"=>"d",
            "е"=>"e",
            "ё"=>"e",
            "ж"=>"j",
            "з"=>"z",
            "и"=>"i",
            "й"=>"y",
            "к"=>"k",
            "л"=>"l",
            "м"=>"m",
            "н"=>"n",
            "о"=>"o",
            "п"=>"p",
            "р"=>"r",
            "с"=>"s",
            "т"=>"t",
            "у"=>"u",
            "ф"=>"f",
            "х"=>"h",
            "ц"=>"ts",
            "ч"=>"ch",
            "ш"=>"sh",
            "щ"=>"sch",
            "ъ"=>"y",
            "ы"=>"i",
            "ь"=>"j",
            "э"=>"e",
            "ю"=>"yu",
            "я"=>"ya",
            " "=> "_",
            "."=> "",
            "/"=> "_",
            ","=>"_",
            "-"=>"_",
            "("=>"",
            ")"=>"",
            "["=>"",
            "]"=>"",
            "="=>"_",
            "+"=>"_",
            "*"=>"",
            "?"=>"",
            "\""=>"",
            "'"=>"",
            "&"=>"",
            "%"=>"",
            "#"=>"",
            "@"=>"",
            "!"=>"",
            ";"=>"",
            "№"=>"",
            "^"=>"",
            ":"=>"",
            "~"=>"",
            "\\"=>"",
            "•" => "",
        );
        return strtr($str,$tr);
    }
    static public function cutRestSymbols($string){
        $tr = array(
            "."=> " ",
            "/"=> " ",
            "["=>"",
            "]"=>"",
            "+"=>" ",
            "--"=>" ",
            "*"=>" ",
            "?"=>" ",
            "\""=>" ",
            "'"=>"",
            "&"=>"",
            "%"=>"",
            "#"=>"",
            "@"=>"",
            "!"=>"",
            ";"=>"",
            "№"=>"",
            "^"=>"",
            ":"=>"",
            "~"=>"",
            "\\"=>"",
            "•" => "",
        );
        return strtr($string, $tr);
    }


}
?>

