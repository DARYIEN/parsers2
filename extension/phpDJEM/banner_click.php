<?php
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/config.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/document690.php');
	$djem = R('DJEM');
	$banners = new Banners($djem);
	$bannerId = intval($_REQUEST['id']);
	$redirect = $banners->Click($bannerId);
	if ($redirect === false) {
		$redrect = '/';
	}
	header("Location: $redirect");
	exit;
?>