<?php
ini_set("memory_limit", "3200M");
ini_set('max_execution_time', 94564565);
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once('main.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);
$options = new Options();
$allParsers = $options->list_parsers;
$parsersData = array();
$countNoId = 0;
$contUndefinedParsers = 0;
echo '<!--';
foreach($allParsers as $parsers){
    foreach($parsers as $parser){
        $currentParser = new $parser();
       if(empty($currentParser->price_id)){
           $countNoId++;
           $djemIdParser = current(array_keys($currentParser->document_urls));
       }else{
           $djemIdParser = $currentParser->price_id;
       }
        if(empty($djemIdParser))$contUndefinedParsers++;
        $parsersData[] = array(
            'nameParser' => $parser,
            'djemIdParser' => $djemIdParser,
            'status' => 'Запускается без ошибок, но не подключен ни к какой компании. То есть существует и предположительно способен работать',
        );
    }
}
echo "\n";
echo "Прайсов всего ".count($parsersData)."\n";
echo "Прайсов без ID ".$countNoId."\n";
echo "Прайсов для которых не удвлось отыскать ID ".$contUndefinedParsers."\n";
include_once ROOT.'/extension/phpDJEM/config.php';
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
        $parsersData[$key]['status'] = 'Запускается без ошибок, но задан не существующий в джеме id прайса для куплю';
        $pricesNoCompanyDjem++;
    }
    if(empty($parsersData[$key]['djemIdCompany'])){
        $pricesNoCompanyDjemUnseted++;
    }
}
echo "Прайсов для которых не удвлось отыскать компанию в джеме ".$pricesNoCompanyDjem."\n";
echo "Всвязи с этим удалено ".$pricesNoCompanyDjemUnseted." компаний\n";
echo "Прайсов осталось в живых ".count($parsersData)."\n";
$pricesNoCompanyNewBase = 0;
foreach($parsersData as $key => $parser){
    foreach($result as $company){
        if($parser['djemIdCompany'] == $company['djemId']){
           $parsersData[$key]['companyId'] = $company['companyId'];
           $parsersData[$key]['nameCompany'] = $company['name'];
           $parsersData[$key]['status'] = 'Запускается без ошибок, правильно подключен к джему и к менеджеру парсеров';
        }
    }
    if(empty($parsersData[$key]['companyId'])){
       // $parsersData[$key]['status'] = 'Запускается без ошибок, правильно подключен к джему, но с новой базой какие тто проблемы';
        $pricesNoCompanyNewBase++;
    }
}
echo "Прайсов для которых не удвлось отыскать компанию в новой базе ".$pricesNoCompanyNewBase."\n";
echo "Прайсов осталось в живых ".count($parsersData)."\n";
echo '-->';
?>
<html>
<head></head>
<body>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css">
<script>
    $(document).ready(function() {
        $('#example').dataTable();
    } );
</script>
<table id="example">
    <thead>
        <th>Имя парсера</th>
        <th>Имя компании</th>
        <th>Ид в джеме</th>
        <th>Ид в новой базе</th>
        <th>Статус</th>
    </thead>
    <tbody>
    <?php foreach($parsersData as $parser){?>
    <tr>
        <td><?php echo @$parser['nameParser']?></td>
        <td><?php echo @$parser['nameCompany']?></td>
        <td><?php echo @$parser['djemIdParser']?></td>
        <td><?php echo @$parser['companyId']?></td>
        <td><?php echo @$parser['status']?></td>
    </tr>
    <?php }?>
    </tbody>
</table>
</body>
</html>