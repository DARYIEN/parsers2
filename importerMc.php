<?php
include_once('main.php');
$memcache_obj = new Memcache;
$memcache_obj->connect('127.0.0.1', 11211) or die('Could not connect');
$options = new CParMainMC();
$allParsers = $options->list_parsers;
$parsersData = array();
$parsersData = @$memcache_obj->get('our_var');
echo str_pad('',1024);
echo str_repeat(' ', 1024 * 64)."\n";
@ob_flush();
flush();
$countNoId = 0;
$contUndefinedParsers = 0;
if(empty($parsersData)){
    foreach($allParsers as $parsers){
        foreach($parsers as $parser){
            $currentParser = new $parser();
           if(empty($currentParser->price_id)){
               $countNoId++;
               $djemIdParser = current(array_keys($currentParser->document_urls));
              /* echo "\n";
               echo 'Найден id'.$djemIdParser."\n";*/
           }else{
               $djemIdParser = $currentParser->price_id;
           }
            if(empty($djemIdParser)){
                $djemIdParser = current($currentParser->prices);
                $djemIdParser = $djemIdParser['price_id'];
            }
            if(empty($djemIdParser))$contUndefinedParsers++;
            $parsersData[] = array(
                'nameParser' => $parser,
                'djemIdParser' => $djemIdParser,
            );
        }
    }
    echo "\n";
    echo "Прайсов всего ".count($parsersData)."\n";
    echo "Прайсов без ID ".$countNoId."\n";
    echo "Прайсов для которых не удвлось отыскать ID ".$contUndefinedParsers."\n";
    print_r($parsersData);
    flush();
    require_once "extension/phpDJEM/config.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $dsn = 'mysql:dbname=metal100_parsingtool;host=localhost';
    $user = 'root';
    $password = '';
    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        $dbh->query ( "SET NAMES utf8" );
        echo 'Success'."\n";
        flush();
    } catch (PDOException $e) {
        echo 'Effor: ' . $e->getMessage();
    }

    $qerySelect = $dbh->prepare('SELECT companyId, djemId, name FROM metal100.companies WHERE STATUS = "CHECKED"');
    $qerySelect->execute();
    $result = $qerySelect->fetchAll();
    $pricesNoCompanyDjem = 0;
    $pricesNoCompanyDjemUnseted = 0;
    foreach($parsersData as $key => $parser){
        try{
            $parsersData[$key]['djemIdCompany'] = R('DJEM')->Load($parser['djemIdParser'])->company;
        }catch (Exception $e){
            echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            $parsersData[$key]['djemIdCompany'] = NULL;
            $pricesNoCompanyDjem++;
        }
        if(empty($parsersData[$key]['djemIdCompany'])){
            echo "Удаляем\n";
            p($parsersData[$key]);
            flush();
            unset($parsersData[$key]);
            $pricesNoCompanyDjemUnseted++;


        }
    }
    echo "Прайсов для которых не удвлось отыскать компанию в джеме ".$pricesNoCompanyDjem."\n";
    echo "Всвязи с этим удалено ".$pricesNoCompanyDjemUnseted." компаний\n";
    echo "Прайсов осталось в живых ".count($parsersData)."\n";
    flush();
   /* die();*/
    /*$memcache_obj->set('our_var',$parsersData);*/
}else{
    echo 'Кеш нас всех спас'."\n";
    require_once "extension/phpDJEM/config.php";
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
    $dsn = 'mysql:dbname=metal100_parsingtool;host=localhost';
    $user = 'root';
    $password = '';
    try {
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
        $dbh->query ( "SET NAMES utf8" );
        echo 'Success'."\n";
        flush();
    } catch (PDOException $e) {
        echo 'Effor: ' . $e->getMessage();
    }

    $qerySelect = $dbh->prepare('SELECT companyId, djemId, name FROM metal100.companies WHERE STATUS = "CHECKED"');
    $qerySelect->execute();
    $result = $qerySelect->fetchAll();
}
$memcache_obj->close();
$pricesNoCompanyNewBase = 0;
$insertCompanies = $dbh->prepare('INSERT INTO metal100.companies (djemId, name, phone, url, office, warehouse, email, regionId, status, callForwarding, phone2) VALUES (:djemId, :name, :phone, :url, :office, :warehouse, :email, :regionId, :status, :callForwarding, :phone2)');
foreach($parsersData as $key => $parser){
    foreach($result as $company){
        if($parser['djemIdCompany'] == $company['djemId']){
           $parsersData[$key]['companyId'] = $company['companyId'];
           $parsersData[$key]['nameCompany'] = $company['name'];
            break;
        }

    }
    if(empty($parsersData[$key]['companyId'])){
        $companyDjem = R('DJEM')->Load($parsersData[$key]['djemIdCompany']);
        $newCompany = array(
            'djemId' => $parsersData[$key]['djemIdCompany'],
            'regionId' => getRegion($dbh, R('DJEM')->Load($companyDjem->_parent_id)->_name),
            'name' => trim($companyDjem->_name),
            'phone' => trim($companyDjem->teleph),
            'phone2' => trim($companyDjem->callForwardingPhone),
            'callForwarding' => trim($companyDjem->callForwardingStatus),
            'email' => trim($companyDjem->email),
            'office' => trim($companyDjem->adress),
            'warehouse' => trim($companyDjem->adress2),
            'url' => trim($companyDjem->url),
            'status' => 'CHECKED',
        );
        try {
            $dbh->beginTransaction();
                $insertCompanies->bindParam(':djemId', $newCompany['djemId']);
                $insertCompanies->bindParam(':name', $newCompany['name']);
                $insertCompanies->bindParam(':phone', $newCompany['phone']);
                $insertCompanies->bindParam(':phone2', $newCompany['phone2']);
                $insertCompanies->bindParam(':url', $newCompany['url']);
                $insertCompanies->bindParam(':office', $newCompany['office']);
                $insertCompanies->bindParam(':warehouse', $newCompany['warehouse']);
                $insertCompanies->bindParam(':email', $newCompany['email']);
                $insertCompanies->bindParam(':regionId', $newCompany['regionId']);
                $insertCompanies->bindParam(':status', $newCompany['status']);
                $insertCompanies->bindParam(':callForwarding', $newCompany['callForwarding']);
                $insertCompanies->execute();
                $parsersData[$key]['companyId'] = $dbh->lastInsertId();
                $parsersData[$key]['nameCompany'] = $newCompany['name'];
            $dbh->commit();
            //print_r($array);
            echo 'записи добавлены';
        } catch (PDOException $e) {
            $dbh->rollBack();
        }
        echo "Удаляем\n";
        p($parsersData[$key]);
        flush();
        unset($parsersData[$key]);
        $pricesNoCompanyNewBase++;
    }
}

echo "Прайсов для которых не удвлось отыскать компанию в новой базе ".$pricesNoCompanyNewBase."\n";
echo "Прайсов осталось в живых ".count($parsersData)."\n";
flush();
//p($parsersData);
//p($result);
$active = 1;
$parserType = 0;
$begin = 'Парсер компании ';
$end = ' (добавлен автоматически)';
$insert = $dbh->prepare('INSERT INTO metal100_parsingtool.parsers (companyId, name, description, active, parserType) VALUES (:companyId, :name, :description, :active, :parserType)');
try {
    $dbh->beginTransaction();
    foreach($parsersData as $key => $row){
        $description = $begin.$row['nameCompany'].$end;
        $insert->bindParam(':companyId', $row['companyId']);
        $insert->bindParam(':name', $row['nameParser']);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':active', $active);
        $insert->bindParam(':parserType', $parserType);
        $insert->execute();

    }
    $dbh->commit();
    echo 'записи добавлены';
} catch (PDOException $e) {
    $dbh->rollBack();
}


/**
 * Вспомогательные функции
 */
function getRegion($dbh, $cityName){
    if(!empty($cityName)){
        $sql = "SELECT regionId FROM metal100.regions WHERE name = '$cityName'";
        $result = $dbh->query($sql);
        if(!empty($result)){
            $row = $result->fetch();
            return $row['regionId'];
        }
    }
    return NULL;
}