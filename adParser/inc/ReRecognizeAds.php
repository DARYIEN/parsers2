<?php
/**
 * Created by IntelliJ IDEA.
 * User: Феликс
 * Date: 24.11.2015
 * Time: 10:13
 */
include_once "CreateAd.php";
class ReRecognizeAds extends CreateAd{
    private $file;
    private $beginPosition = null;
    private $limit = null;
    public function __construct($label = 'metal100_local', $limit = null){
        $this->configDb = $label;
        $this->pathToParsingTool = "https://parsingtool.".str_replace("_local","",$label).".ru";
        if(is_file('tempId.log')) $arrayLog = file('tempId.log');
        if(isset($arrayLog) && count($arrayLog) > 0)
        $this->beginPosition = end($arrayLog);
        $this->limit = $limit;
    }
    public function init(){
        $this->connectionDB($this->configDb);
    }
    public function getAllAds(){
        $query = "SELECT adId, title, text FROM ads ";
        if($this->beginPosition != null) $query .= 'WHERE adId > '.$this->beginPosition.' ORDER BY adId DESC';
        if($this->limit != null) $query .= 'LIMIT '.$this->limit;
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }
    public function recognize(){
        $ads = $this->getAllAds();
        foreach($ads as $i => $ad){
            $tempAd = array();
            $tempAd['content'] = $ad['text'];
            $ad['categories'] = $this->getCategoriesFromAd(array_merge($ad,$tempAd));
            //print_r(array_merge($ad,$tempAd));
            if(!empty($ad['categories']) && isset($ad['categories'][1]) && !empty($ad['categories'][1])) $this->updateCategories(array_merge($ad,$tempAd));
        }
    }
    private function updateCategories($ad){
        $query = "UPDATE ads SET categoryId = :categoryId, sizeId = :sizeId, typeId = :typeId WHERE adId = :adId";
        $stmt = $this->db->prepare($query);
        try {
            $this->db->beginTransaction();
            $stmt->bindParam(':adId', $ad['adId']);
            $stmt->bindParam(':categoryId', $ad["categories"][1]);
            $stmt->bindParam(':sizeId', $ad["categories"][2]);
            $stmt->bindParam(':typeId', $ad["categories"][3]);
            $stmt->execute();
            $this->db->commit();
            $this->writeLog($ad['adId']);
        } catch (PDOException $e) {
            $this->db->rollBack();
            print_r($e->getMessage());
        }
    }
    private function writeLog($id){
        $this->file = fopen("tempId.log","a+");
        fwrite($this->file,$id."\r\n");
        fclose($this->file);
    }
}