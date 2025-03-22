#!/usr/bin/php5
<?php
include_once('main.php');
$error_r = 0;
if($argv[1]){
    $error_r = 1;
}
$options = new Options();
for($i = 1; $i <= count($options->cities_list); $i++){ /**/
    echo 'Memory cities used before:'.convert(memory_get_usage(true)).' ';
    if(isset($options->list_parsers[$i]) && !empty($options->list_parsers[$i])){
        passthru('php /home/parsers/www/index.php '.$i.' '.$error_r);
    }
    echo 'Memory cities used :'.convert(memory_get_usage(true)).' ';
}