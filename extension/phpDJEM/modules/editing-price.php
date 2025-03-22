<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 
?>  
<?php

   
$djem = R('DJEM');
$doc = $djem->Load($_POST['param2']);
print $doc->_name;   // Выведет имя документа с ID 14
$doc->$_POST['param3'] = $_POST['param1'];
$doc->Store();

?>
