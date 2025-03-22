<?php
require_once '../../adMainer.php';
include_once 'ReRecognizeAds.php';

$reNew = new ReRecognizeAds('metal100');
$reNew->init();
$reNew->recognize();