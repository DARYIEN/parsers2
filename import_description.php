<?php
/**
 * Created by PhpStorm.
 * User: Михаил
 * Date: 13.10.14
 * Time: 16:13
 */

include_once('main.php');
$memcache_obj = new Memcache;
$memcache_obj->connect('127.0.0.1', 11211) or die('Could not connect');
$options = new Options();
$allParsers = $options->list_parsers;
$parsersData = array();
$parsersData = @$memcache_obj->get('our_var');
//echo str_pad('',1024);
//echo str_repeat(' ', 1024 * 64)."\n";
//@ob_flush();
//flush();
$countNoId = 0;
$contUndefinedParsers = 0;
$i=0;
if(empty($parsersData)){
    foreach($allParsers as $parsers){
        foreach($parsers as $parser){
            $currentParser = new $parser();
            $description = '';
            if(empty($currentParser->price_id)){
                $countNoId++;
                $djemIdParser = current(array_keys($currentParser->document_urls));
            }else{
                $djemIdParser = $currentParser->price_id;
            }
            if(isset($currentParser->home_url))$description = $currentParser->home_url;
            if(isset($currentParser->document_url))$description = $currentParser->document_url;
            if(isset($currentParser->document_urls)){
                $description = implode('<br>', $currentParser->document_urls);
            }

            $parsersData[] = array(
                'nameParser' => $parser,
                'djemIdParser' => $djemIdParser,
                'description' => $description,
            );
            if($i < 5)$i++;else break 2;
        }
    }
    echo "\n";
    echo "Прайсов всего ".count($parsersData)."\n";
    echo "Прайсов без ID ".$countNoId."\n";
    echo "Прайсов для которых не удвлось отыскать ID ".$contUndefinedParsers."\n";
    //flush();
    print_r($parsersData);
}