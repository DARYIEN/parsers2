<?php
include_once('main.php');
try{
    /**
     * 88.198.17.165
    metal100 - 1oWADa3Oc6
     **/
    $option = getopt("l:c:");
    if(empty($option)){
        $option['l'] = '164';
        $option['c'] = 'metal100';
    }
    $recognize = new Recognizer($option);
    $recognize->init();
    $items = $recognize->output;
}catch(Exception $e){
    echo $e->getMessage();
}
