<?php
header("Location: ".$_POST["url"]);
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 


$newDoc = new DJEMDocument($djem);
$newDoc->_type = 6136135;
$newDoc->_parent_id = 461531;



$ad=$newDoc->_id;
?>






<?php
$newDoc->_name = 'Свбодная заявка';
$newDoc->org = $_POST["name"];
$newDoc->txt = $_POST["text"];
$newDoc->adres = $_POST["adres"];
$newDoc->telephone = $_POST["telephone"];
$newDoc->email = $_POST["Email"];
if($_POST["other"]=="other"){
$newDoc->other = 'on';
}
if($_POST["prodam"]=="prodam"){
$newDoc->prodam = 'on';
}
if($_POST["kuplu"]=="kuplu"){
$newDoc->kuplu = 'on';
}
if($_POST["pred_usl"]=="pred_usl"){
$newDoc->pred_uslugi = 'on';
}
if($_POST["spros_usl"]=="spros_usl"){
$newDoc->Spros_uslugi = 'on';
}

// В PHP 4.1.0 и более ранних версиях следует использовать $HTTP_POST_FILES
// вместо $_FILES.

$uploaddir = '/var/www/sergey/data/www/plastom.ujob.su/112/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);


move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);


 if($_FILES['userfile']['name']){  //проверка если имя временно закинутого файла существует,то создаем поле в файле
$newDoc->upfile = $uploadfile{$_POST["adres"]};
}
//chmod($uploadfile,0777); // в случае Unix хостинга возможно нужно расскоментировать

 
 
 



if($_POST["dni"]=='10'){
$newDoc->des = 'on';
}
elseif($_POST["dni"]=='20'){

$newDoc->dvad = 'on';
}
elseif($_POST["dni"]=='30'){

$newDoc->trid = 'on';

}


if($_POST["cat"]){

$newDoc->catalog = 'on';

	}
	if($_POST["dos"]){
	$newDoc->doska = 'on';
	}

$newDocId = $newDoc->Store();  // Сохранит документ в папке с ID 14








    ////////////////////////////Отправим письмо 



$newDoc = new DJEMDocument($djem);
$newDoc->_type = 7663382;
$newDoc->_parent_id = 461531;
$newDoc->txt = 'Новое предложение '.$_POST["text"];
$newDoc->Store(); 

?> 

