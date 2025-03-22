<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 
?>   
<?php $tmp_djem_document__ = new DJEMDocument(R('DJEM'));$var['create:id'] = $tmp_djem_document__->Set('_parent_id', '461531')->Set('_name', 'Заявка на позицию')->Set('_type', '7654199')->Store();unset($tmp_djem_document__); ?><?php R('DJEM')->Load($var['create:id'])->Set('_name', (isset($_REQUEST['nazv'])?$_REQUEST['nazv']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('size', (isset($_REQUEST['dlin'])?$_REQUEST['dlin']:''))->Store(); ?>


<?php R('DJEM')->Load($var['create:id'])->Set('wholesale', (isset($_REQUEST['opt'])?$_REQUEST['opt']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('retail', (isset($_REQUEST['rozn'])?$_REQUEST['rozn']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('Notes', (isset($_REQUEST['prim'])?$_REQUEST['prim']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('organization', (isset($_REQUEST['name_of'])?$_REQUEST['name_of']:''))->Store(); ?> 

<?php R('DJEM')->Load($var['create:id'])->Set('telephone', (isset($_REQUEST['tel'])?$_REQUEST['tel']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('sait', (isset($_REQUEST['sait'])?$_REQUEST['sait']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('email', (isset($_REQUEST['email'])?$_REQUEST['email']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('sklad', (isset($_REQUEST['sklad'])?$_REQUEST['sklad']:''))->Store(); ?>
<?php R('DJEM')->Load($var['create:id'])->Set('office', (isset($_REQUEST['adres'])?$_REQUEST['adres']:''))->Store(); ?>

<?php R('DJEM')->Load($var['create:id'])->Set('Ghost', (isset($_REQUEST['ghost'])?$_REQUEST['ghost']:''))->Store(); ?>


<?php 
$ad=R('DJEM')->Load($_REQUEST["nazv"])->_id;

$doc=R('DJEM');

$new_name=$doc->Parent($ad, 3)->_name.' '.$doc->Parent($ad, 4)->_name.' '.$doc->Parent($ad, 5)->_name;

$newDoc = new DJEMDocument($djem);
$newDoc->_type = 7663382;
$newDoc->_parent_id = 461531;
$newDoc->txt = $new_name;
$newDoc->Store(); 
?>