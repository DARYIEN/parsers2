<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 20.10.14
 * Time: 17:48
 */
include_once('main.php');
$options = new Options();
$allParsers = $options->list_parsers;
/*$allParsers = array(array(
    "CParUralMetallEnergo",
    "CParStalProMoscow",
    "CParExpressMetall",
));*/
$parsersData = array();

foreach($allParsers as $parsers){
    foreach($parsers as $parser){
        $urls = array();
        $description = '';
        $currentParser = new $parser();
        if(empty($currentParser->price_id)){
            $djemIdParser = current(array_keys($currentParser->document_urls));
            $djemIdParser = current(array_keys($currentParser->document_urls));
        }else{
            $djemIdParser = $currentParser->price_id;
        }
        if(isset($currentParser->document_url)) $urls = array($currentParser->document_url);
        if(isset($currentParser->document_urls)) $urls = $currentParser->document_urls;
        foreach($urls as $key  => $url){
            $description .='<a href="'.$url.'">'.$url.'</a><br>';
        }
        $parsersData[] = array(
            'nameParser' => $parser,
            'djemIdParser' => $djemIdParser,
            'urls' => $urls,
            'description' => $description,
        );
    }
}


require_once "extension/phpDJEM/config.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
/*$dsn = 'mysql:dbname=metal100_parsingtool;host=localhost';
$user = 'root';
$password = '';*/
$dsn = 'mysql:dbname=metal100_parsingtool;host=88.198.17.165';
$user = 'metal100';
$password = '1oWADa3Oc6';
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

        flush();
        unset($parsersData[$key]);
        $pricesNoCompanyDjemUnseted++;


    }
}
$qerySelect = $dbh->prepare('SELECT companyId, djemId, name FROM metal100.companies WHERE STATUS = "CHECKED"');
$qerySelect->execute();
$result = $qerySelect->fetchAll();
foreach($parsersData as $key => $parser){
    foreach($result as $company){
        if($parser['djemIdCompany'] == $company['djemId']){
            $parsersData[$key]['companyId'] = $company['companyId'];
            $parsersData[$key]['nameCompany'] = $company['name'];
            break;
        }
    }
}
/*
$qerySe = $dbh->prepare('SELECT companyId, name, description FROM metal100_parsingtool.parsers');
$qerySe->execute();
$qeryRE = $qerySe->fetchAll();
foreach($parsersData as $key => $row){
    foreach($qeryRE as $row2){
        if($row['companyId'] === $row2['companyId']){
            $parsersData[$key]['description'] = $row2['description'].$row['urls'];
        }
    }
}
*/
$begin = 'Парсер компании ';
$end = ' (добавлен автоматически)';
$insert = $dbh->prepare('UPDATE metal100_parsingtool.parsers SET sources = :sources, description = :description WHERE companyId = :companyId');
try {
    $dbh->beginTransaction();
    foreach($parsersData as $key => $row){
        $description = $begin.$row['nameCompany'].$end;
        $sources = '<p>Сайт: <a href="http://'.parse_url(current($row['urls']), PHP_URL_HOST).'">'.parse_url(current($row['urls']), PHP_URL_HOST).'</a></p>';
        $sources .= '<p>'.$row['description'].'</p>';
        $insert->bindParam(':companyId', $row['companyId']);
        $insert->bindParam(':description', $description);
        $insert->bindParam(':sources', $sources);
        $insert->execute();
    }
    $dbh->commit();
    echo 'записи добавлены';
} catch (PDOException $e) {
    $dbh->rollBack();
}
//print_r($parsersData);