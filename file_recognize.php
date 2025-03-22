<?php
include_once('main.php');

$items[] = getopt("name:cost:");
$option = getopt("c:");
if(empty($items[0])){
    $items[0]['name'] = "КРУГ AISI 321, необточенный 160 6000";
    $items[0]['cost'] = 20222;
}
if(empty($option)){
    $option["c"] = "metal100";
}
$recognize = new RecognizeFile($option, $items);
$recognize->init();
$items = $recognize->output;
//$obj = new CParMain();
//$obj->items = $items;
//$obj->to_save = 'sources/price_vialmet_recognized.csv';
//$obj->save('sources/price_vialmet_recognized.csv');
p($items);
