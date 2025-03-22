<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 
?>   
  
<?php $tmp_djem_document__ = new DJEMDocument(R('DJEM'));$var['create:id'] = $tmp_djem_document__->Set('_parent_id', '461531')->Set('_name', 'Новый заказ')->Set('_type', '3781089')->Store();unset($tmp_djem_document__); ?>


 <?php if ($var['create:id']) { ?>            - документ успешно создан
  <?php R('DJEM')->Load($var['create:id'])->Set('_name', 'Поступила новая заявка на рекламу')->Store(); ?>       <?php R('DJEM')->Load($var['create:id'])->Set('name_org', (isset($_REQUEST['param1'])?$_REQUEST['param1']:''))->Store(); ?>       <?php R('DJEM')->Load($var['create:id'])->Set('inn', (isset($_REQUEST['param2'])?$_REQUEST['param2']:''))->Store(); ?>        <?php R('DJEM')->Load($var['create:id'])->Set('kpp', (isset($_REQUEST['param3'])?$_REQUEST['param3']:''))->Store(); ?>        <?php R('DJEM')->Load($var['create:id'])->Set('yr_adres', (isset($_REQUEST['param4'])?$_REQUEST['param4']:''))->Store(); ?>       <?php R('DJEM')->Load($var['create:id'])->Set('poch_adres', (isset($_REQUEST['param5'])?$_REQUEST['param5']:''))->Store(); ?>        <?php R('DJEM')->Load($var['create:id'])->Set('fase', (isset($_REQUEST['param6'])?$_REQUEST['param6']:''))->Store(); ?>       <?php R('DJEM')->Load($var['create:id'])->Set('tel', (isset($_REQUEST['param7'])?$_REQUEST['param7']:''))->Store(); ?>        <?php R('DJEM')->Load($var['create:id'])->Set('fax', (isset($_REQUEST['param8'])?$_REQUEST['param8']:''))->Store(); ?>        <?php R('DJEM')->Load($var['create:id'])->Set('email', (isset($_REQUEST['param8'])?$_REQUEST['param8']:''))->Store(); ?>        <?php R('DJEM')->Load($var['create:id'])->Set('sposob', ' '.(isset($_REQUEST['param9'])?$_REQUEST['param9']:'').' '.(isset($_REQUEST['param10'])?$_REQUEST['param10']:''))->Store(); ?>
   <?php } ?>


    