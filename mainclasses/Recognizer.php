<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 30.06.14
 * Time: 12:18
 */
/**
 * Class Recognizer
 * output array(
 *  0 =>name => name,
 *      id_categories => array(
 *          parent_category_id => 0
 *          children_categories_id => array(
 *
 *          )
 *      )
 * )
 *
 */
class Recognizer {
    public $input;
    public $options;
    public $str;
    public $output;
    public $db;
    public $categories;
    public $type;
    public $selectParameters;
    public $treeIds = array();
    public $treeRoot = 0;
    public $dbParameters = array();

    public function __construct($options){
        $this->options = $options;
        //$this->str = $input;
    }
    public function connectDb($type = 'Site'){
        $dbConnection = new WorkDb($this->options['c'].$type);
        $this->db = $dbConnection->db;
        return $this;
    }
    public function init(){
        $this->input = $this->getItems();
        $this->output = $this->input;

        $this->type = 'cat';
        $memcache = new Memcache();
        $memcache->connect("127.0.0.1","11211");
        $this->categories = $memcache->get("hierarchy_recognizer");
        if(!$this->categories){
            $this->categories = $this->getChildren();
            $memcache->set("hierarchy_recognize", $this->categories, false, 3600);
        }
        $this->start();

        $this->type = 'param';
        $this->categories = $memcache->get("params_recognizer");
        if(!$this->categories){
            $this->categories = $this->getParamCat();
            $memcache->set("params_recognizer", $this->categories, false, 3600);
        }
        $this->start();
        $this->updateItems();
    }
    protected function start(){
        switch($this->type){
            case 'cat':
                foreach($this->input as $numItem => $item){
                    $this->str = $item['name'];
                    $this->recognize($this->categories[0],0);
                    $this->output[$numItem][$this->type] = $this->treeIds;
                    $this->treeIds = array();
                    //if($numItem == 20) break;
                }
                break;
            case 'param':
                foreach($this->input as $numItem => $item){
                    $this->str = $item['name'];
                    $this->recognizeParam($this->categories,0);
                    $this->output[$numItem][$this->type] = $this->treeIds;
                    $this->treeIds = array();
                    //if($numItem == 20) break;
                }
                break;
        }
        return $this;
    }

    /**
     * Работа с входными данными
     */
    /*******************************************************************************************************************/
    /**
     * Функция для определения дерева ID
     * @var $this - mixed
     * @var $strings - mixed
     * @return $this;
     */
    public function recognize($categories, $parent_id = 0){
        $string = str_replace(array('/',"\\"),array("",""),$this->str);
        //p($string);
        //uasort($categories,'customSortTruth');
        foreach($categories as $id => $data){
            if($this->checkWord($string,$data)){
                $this->treeIds[$parent_id]['id'] = $data['id'];
                $this->treeIds[$parent_id]['type'] = $data['type'];
                $this->treeIds[$parent_id]['name'] = $data['name'];
                //$children = $this->getChildren($id);
                if(isset($this->categories[$data['id']]) && !empty($this->categories[$data['id']])){
                    $this->recognize($this->categories[$data['id']],$data['id']);
                }
                break;
            }else{
                continue;
            }
        }
    }

    public function recognizeParam($categories){
        $string = str_replace(array('/',"\\"),array("",""),$this->str);
        foreach($categories as $category){
            //$tree = $this->checkWord($strings,$data);
            //uasort($category,'customSortTruth');
            //p($category);
            foreach($category as $id => $data){
                if($this->checkWord($string,$data)){
                    $this->treeIds[$data['type']]['id'] = $id;
                    $this->treeIds[$data['type']]['type'] = $data['type'];
                    $this->treeIds[$data['type']]['name'] = $data['name'];
                    break;
                }else{
                    continue;
                }
            }
        }
    }
    public function checkWord($strings, $data){
        if(!isset($data['wordminus'])) $data['wordminus'] = array();
        if(!isset($data['wordplus'])) $data['wordplus'] = array();
        //p($data);
        if($this->identity($strings,$data['wordminus'])){
            //p($data);
             //echo "MINUS <br />";
            return false;
        }
        if($this->identity($strings,$data['wordplus'])){
            //p($data);
             //echo "PLUS <br />";
            return true;
        }
        return false;
    }

    public function identity($strings, $patterns){
        if(!empty($patterns)){
            //usort($patterns,'customSort');
            $patterns = $this->delMetSymbols($patterns);

            foreach($patterns as $pattern){
                //p(str_replace('/','\/',$pattern));
                $add_e = '\b';
                $add_b = '\b';
                $add_postfix = '';
                $add_prefix = '';
                //$test =  iconv('utf-8','cp1251',$pattern);

                if(mb_substr($pattern,0,1,'utf-8') == '&'){
                    $add_e = '';
                    $pattern = str_replace("&", "", $pattern);
                }

                /*if(mb_strpos($pattern,'$',null,'utf-8')){
                    $add_e = '';
                }*/
                if(is_numeric($pattern)){
                    $add_postfix = '(?!(\.|\,|\-))';
                    $add_prefix = '(?<!(\.|\,|\-))';
                }
                $regexp = '/'.$add_b.$add_prefix.$pattern.$add_postfix.$add_e.'/iu';
                //p($regexp);
                if(preg_match($regexp, $strings.' ')){
                    //p('/'.$add_b.str_replace(array('*','/','&','$','(',')'),array('\*','\/','','','\(','\)'),trim($pattern)).$add_e.'/iu'.' ');
                    return true;
                }
            }
        }
        return false;
    }

    public function delMetSymbols($array){
        return str_replace(array('*','/','$','(',')','.','^','+','?','{','[',']','|'),array('\*','\/','','\(','\)','\.','\^','\+','\?','\{','\[','\]','\|'),$array);
    }

    public function getParamCat(){
        $this->connectDb();
        $query_str = 'SELECT params.paramId as id, params.name, params.type, keywords_params.name as word, keywords_params.negative as n  FROM params INNER JOIN keywords_params ON keywords_params.paramId = params.paramId';
        $query = $this->db->query($query_str);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        //p($rows);
        foreach($rows as $row){
            $childrens[$row['type']][$row['id']]['id'] = $row['id'];
            $childrens[$row['type']][$row['id']]['name'] = $row['name'];
            $childrens[$row['type']][$row['id']]['type'] = $row['type'];
            if(isset($row['n']) && $row['n'] == 1)
                $childrens[$row['type']][$row['id']]['wordminus'][] = $row['word'];
            else
                $childrens[$row['type']][$row['id']]['wordplus'][] = $row['word'];
        }

        uasort($childrens["STEEL"],'customSortTruth');
        uasort($childrens["STANDARD"],'customSortTruth');
        uasort($childrens["NOTE"],'customSortTruth');
        uasort($childrens["LENGTH"],'customSortTruth');
        return isset($childrens) && !empty($childrens) ? $childrens : array();
    }
    public function getChildren(){
        $this->connectDb();
        $query_str = 'SELECT c.categoryId, c.shortName as name, c.type, c.level, c.parentId, kc.name as word, kc.negative as n from categories c inner join keywords_categories kc on c.categoryId = kc.categoryId';
        $query = $this->db->query($query_str);
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        $childrens = array();
        foreach($rows as $row){
            $childrens[$row['parentId']][$row['categoryId']]['id'] = $row['categoryId'];
            $childrens[$row['parentId']][$row['categoryId']]['name'] = $row['name'];
            $childrens[$row['parentId']][$row['categoryId']]['type'] = $row['type'];
            if(isset($row['n']) && $row['n'] == 1)
                $childrens[$row['parentId']][$row['categoryId']]['wordminus'][] = $row['word'];
            else
                $childrens[$row['parentId']][$row['categoryId']]['wordplus'][] = $row['word'];
        }

        //memcache_set($m, md5($query_str), $childrens, false, 3600*10);
        return isset($childrens) && !empty($childrens) ? $childrens : array();
    }

    private function updateItems(){
        $this->connectDb('Parsing');
        $str = 'UPDATE prices_temp SET categoryId = :categoryId, subCategoryId = :subcategoryId, sizeId = :sizeId, typeId = :typeId, param1 = :length, param2 = :steel, param3 = :standard, param4 = :note, imagePath = :imagePath, imageFileName = :imageFileName WHERE priceItemId = :priceItemId';
        $query = $this->db->prepare($str);
        $categoryId = $subcategoryId = $priceItemId = $sizeId = $typeId = $length = $steel = $standard = $note = $imagePath = $imageFileName = NULL;
        $query->bindParam(':priceItemId', $priceItemId);
        $query->bindParam(':categoryId', $categoryId);
        $query->bindParam(':subcategoryId', $subcategoryId);
        $query->bindParam(':sizeId', $sizeId);
        $query->bindParam(':typeId', $typeId);
        $query->bindParam(':length', $length);
        $query->bindParam(':standard', $standard);
        $query->bindParam(':steel', $steel);
        $query->bindParam(':note', $note);
        $query->bindParam(':imagePath', $imagePath);
        $query->bindParam(':imageFileName', $imageFileName);
        foreach($this->output as $item){
            $categoryId = $subcategoryId = $priceItemId = $sizeId = $typeId = $length = $steel = $standard = $note = $imagePath = $imageFileName = NULL;
            $priceItemId = $item['priceItemId'];
            $imagePath = 'URL';
            $imageFileName = $item['picture'];
            if(isset($item['cat'])){
                foreach($item['cat'] as $data){
                    if($data['type'] == 'TYPE') $typeId = $data['id'];
                    if($data['type'] == 'SIZE') $sizeId = $data['id'];
                    if($data['type'] == 'CATEGORY') $categoryId = $data['id'];
                    if($data['type'] == 'SUBCATEGORY') $subcategoryId = $data['id'];
                }
            }
            if(isset($item['param'])){
                foreach($item['param'] as $data){
                    if($data['type'] == 'STEEL') $steel = $data['id'];
                    if($data['type'] == 'LENGTH') $length = $data['id'];
                    if($data['type'] == 'STANDARD') $standard = $data['id'];
                    if($data['type'] == 'NOTE') $note = $data['id'];
                }
            }
            $query->execute();
        }
    }
    public function getItems(){
        $this->connectDb('Parsing');
        $str = 'SELECT priceItemId, logEntryId, rawData as name FROM prices_temp WHERE logEntryId = ? AND postponed = 1';
        $query = $this->db->prepare($str);
        $query->bindParam(1, $this->options['l']);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    /*******************************************************************************************************************/
}

class RecognizeFile extends Recognizer{
    public function __construct($options, $input = array()){
        $this->options = $options;
        $this->input = $input;
    }
    public function init(){
        $this->output = $this->input;
        $this->type = 'cat';
        $memcache = new Memcache();
        $memcache->connect("127.0.0.1","11211");
        $this->categories = $memcache->get("hierarchy_recognizer_".$this->options['c']);
        if(!$this->categories){
            $this->categories = $this->getChildren();
            $memcache->set("hierarchy_recognizer_".$this->options['c'], $this->categories, false, 3600);
        }
        $this->start();

        $this->type = 'param';
        $this->categories = $memcache->get("params_recognizer_".$this->options['c']);
        if(!$this->categories){
            $this->categories = $this->getParamCat();
            $memcache->set("params_recognizer_".$this->options['c'], $this->categories, false, 3600);
        }

        $this->start();
    }
}

class RecognizeCacheRebuild extends Recognizer{
    public function __construct($options){
        $this->options = $options;
    }
    public function rebuildCache(){
        $memcache = new Memcache();
        $memcache->connect("127.0.0.1","11211");
        $memcache->delete("hierarchy_recognizer_".$this->options['c']);
        $memcache->set("hierarchy_recognizer_".$this->options['c'], $this->getChildren(), false, 3600);
        $memcache->delete("params_recognizer_".$this->options['c']);
        $memcache->set("params_recognizer_".$this->options['c'],  $this->getParamCat(), false, 3600);
    }
}