 <?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 
?>   
  
<?php $tmp_djem_document__ = new DJEMDocument(R('DJEM'));$var['create:id'] = $tmp_djem_document__->Set('_parent_id', '461531')->Set('_name', 'В прайсе ошибка')->Set('_type', '461532')->Store();unset($tmp_djem_document__); ?> <?php if ($var['create:id']) { ?> 
       <?php R('DJEM')->Load($var['create:id'])->Set('_name', 'В прайсе указана не верная цена')->Store(); ?>       <?php R('DJEM')->Load($var['create:id'])->Set('id_price', (isset($_REQUEST['param1'])?$_REQUEST['param1']:''))->Store(); ?>         <?php } ?>    