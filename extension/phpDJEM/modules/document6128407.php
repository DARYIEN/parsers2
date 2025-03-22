<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 
?>   
<?php  
$telephone=$_REQUEST["telephone"];
$Email=$_REQUEST["Email"];
$dni=$_REQUEST["dni"];
$razmes=$_REQUEST["razmes"];

$prodam=$_REQUEST["prodam"];
$kuplu=$_REQUEST["kuplu"];
$pred_usl=$_REQUEST["pred_usl"];
$spros_usl=$_REQUEST["spros_usl"];
$other=$_REQUEST["other"];




$pieces = explode(";",substr($_COOKIE["main_data"], 0, -1));



$k=0;
foreach ($pieces as $value) {
$k++;
?> 
  
<?php $tmp_djem_document__ = new DJEMDocument(R('DJEM'));$var['create:id'] = $tmp_djem_document__->Set('_parent_id', '461531')->Set('_name', 'Обычная заявка')->Set('_type', '7654195')->Store();unset($tmp_djem_document__); ?>    <?php if ($prodam) { ?>  
<?php R('DJEM')->Load($var['create:id'])->Set('prodam', 'on')->Store(); ?><?php } ?><?php if ($kuplu) { ?><?php R('DJEM')->Load($var['create:id'])->Set('kuplu', 'on')->Store(); ?><?php } ?><?php if ($pred_usl) { ?><?php R('DJEM')->Load($var['create:id'])->Set('pred_uslugi', 'on')->Store(); ?><?php } ?><?php if ($spros_usl) { ?><?php R('DJEM')->Load($var['create:id'])->Set('Spros_uslugi', 'on')->Store(); ?><?php } ?><?php if ((isset($_REQUEST['other'])?$_REQUEST['other']:'')) { ?><?php R('DJEM')->Load($var['create:id'])->Set('other', 'on')->Store(); ?><?php } ?>


<?php 
    $gotov =explode(",",$value);
    $i=0;
    foreach ($gotov as $value2){
    $i++;

if($i==1){
$idtovar=$value;

$type1=R('DJEM')->load(R('DJEM')->load($value2)->_parent_id)->_type;
if($type1=='221'){
$ima=R('DJEM')->Parent($value2,3)->_name.' '.R('DJEM')->Parent($value2,4)->_name.' '.R('DJEM')->Parent($value2,5)->_name.' '.R('DJEM')->load($value2)->_name;

}elseif($type1=='220'){
$ima=R('DJEM')->Parent($value2,3)->_name.' '.R('DJEM')->Parent($value2,4)->_name.' '.R('DJEM')->load($value2)->_name;
}
?> 

	<?php R('DJEM')->Load($var['create:id'])->Set('vid', $ima)->Store(); ?>

<?php



//Узнаем название всего продукта в зависимости от вложенности структуры
//записываем в $hold_perem список с названием
$type=R('DJEM')->load(R('DJEM')->load($value2)->_parent_id)->_type;
if($type=='221'){
$hold_perem=$hold_perem.'</br>'.R('DJEM')->Parent($value2,3)->_name.' '.R('DJEM')->Parent($value2,4)->_name.' '.R('DJEM')->Parent($value2,5)->_name.' '.R('DJEM')->load($value2)->_name.'</br>';

}elseif($type=='220'){
$hold_perem=$hold_perem.'</br>'.R('DJEM')->Parent($value2,3)->_name.' '.R('DJEM')->Parent($value2,4)->_name.' '.R('DJEM')->load($value2)->_name.'</br>';
}







 
}elseif($i==2){

?>
	<?php R('DJEM')->Load($var['create:id'])->Set('vtor', $value2)->Store(); ?>

<?php

}elseif($i==3){
$size=$value;
?>	
	<?php R('DJEM')->Load($var['create:id'])->Set('tret', $value2)->Store(); ?>  
	  

 <?php

}elseif($i==4){
$size=$value;
?>	
	<?php R('DJEM')->Load($var['create:id'])->Set('char_avto', $value2)->Store(); ?>  
	  

 <?php

}elseif($i==5){

?>	
	<?php R('DJEM')->Load($var['create:id'])->Set('char_manual', $value2)->Store(); ?>  
	  
<?php 
  }  
  
  
 ?>
 
 
 <?php if ($dni == 10) { ?> <?php R('DJEM')->Load($var['create:id'])->Set('des', 'on')->Store(); ?> <?php } ?> 
  <?php if ($dni == 20) { ?> <?php R('DJEM')->Load($var['create:id'])->Set('dvad', 'on')->Store(); ?> <?php } ?> 
   <?php if ($dni == 30) { ?> <?php R('DJEM')->Load($var['create:id'])->Set('trid', 'on')->Store(); ?> <?php } ?> 
 
 
 
 
 
    <?php if ($razmes == 'catalog') { ?>    
 <?php R('DJEM')->Load($var['create:id'])->Set('catalog', 'on')->Store(); ?> 
 <?php } ?>  
  
      <?php if ($razmes == 'doska') { ?>    
 <?php R('DJEM')->Load($var['create:id'])->Set('doska', 'on')->Store(); ?> 
 <?php } ?>
 
  <?php R('DJEM')->Load($var['create:id'])->Set('telephone', $telephone)->Store(); ?>  <?php R('DJEM')->Load($var['create:id'])->Set('email', $Email)->Store(); ?>  
	  
<?php 
  }  
  
  
  

    
    }
    


?>

 




<?php $tmp_djem_document__ = new DJEMDocument(R('DJEM'));$var['create:id'] = $tmp_djem_document__->Set('_parent_id', '461531')->Set('_name', 'Рассылка')->Set('_type', '7663382')->Store();unset($tmp_djem_document__); ?><?php R('DJEM')->Load($var['create:id'])->Set('txt', $hold_perem)->Store(); ?>






   <?php 
  ////////////////////////   
   
      
   
  //После успешной отправки мы убираем печеньки,ибо нам нужно пустой блок с заявками
   setcookie ("main_data", "", time() - 3600,'/'); 
   
   
   
   
   ?>
    


 
 


   
        
      
      


