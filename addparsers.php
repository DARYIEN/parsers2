<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 05.09.14
 * Time: 10:26
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
$dsn = 'mysql:dbname=metal100;host=88.198.17.165';
$user = 'metal100';
$password = '1oWADa3Oc6';
try {
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    $dbh->query ( "SET NAMES utf8" );
    echo 'Success'."\n";
} catch (PDOException $e) {
    echo 'Effor: ' . $e->getMessage();
}
$qerySelect = $dbh->prepare('SELECT companyId, name FROM metal100.companies WHERE phone is null and regionId = 127');
$qerySelect->execute();
$result = $qerySelect->fetchAll();
print_r($result);

$active = 1;
$parserType = 0;
$begin = 'Парсер компании ';
$end = ' (добавлен автоматически)';
$insert = $dbh->prepare('INSERT INTO metal100_parsingtool.parsers (companyId, name, description, active, parserType) VALUES (:companyId, :name, :description, :active, :parserType)');
try {
    $dbh->beginTransaction();
    foreach($result as $key => $row){
        $description = $begin.$row['name'].$end;
        $insert->bindParam(':companyId', $row['companyId']);
        $insert->bindParam(':name', $row['name']);
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