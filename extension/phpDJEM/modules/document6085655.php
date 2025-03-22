<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 

$forid = R('DJEM')->Load($_REQUEST['forid'])->_id;

$stoim = $_REQUEST['stoim'];
$kolich = $_REQUEST['kolich'];
$char_avto =$_REQUEST['char_avto'];
$char_manual =$_REQUEST['char_manual'];


$list=$_COOKIE["main_data"].$forid.','.$stoim.','.$kolich.','.$char_avto.','.$char_manual.';';

setcookie("main_data",$list,0,'/'); 


$pieces = explode(";",substr($list, 0, -1));
foreach ($pieces as $value) {
    
    $gotov =explode(",",$value);
    $i=0;
    foreach ($gotov as $value2)
    {
    $i++;
    if ($i==1){   
	$bla=R('DJEM')->Parent($value2,4)->_name.' '.R('DJEM')->Parent($value2,5)->_name.' '.R('DJEM')->Load($value2)->_name;		
        echo 'Наименование : '.$bla.'</br>';
        
        }
        
        //if($i==2){
        //echo 'Стоимость:'.$value2.'</br>';
        //}
        
        if($i==3){
        echo 'Количество:'.$value2.'</br>';
        }
        
        
    }
    echo '<hr />';
}



?>


