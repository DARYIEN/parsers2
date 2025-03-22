<?php
include_once('main.php');
$options = getopt("c:");
if(empty($options)){
    $options['c'] = 'metal100';
}

$recognize = new RecognizeCacheRebuild($options);
$recognize->rebuildCache();
