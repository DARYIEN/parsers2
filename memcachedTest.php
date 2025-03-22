<?php
$memcache = new Memcache();
$memcache->connect('127.0.0.1', 11211);
$childrens = $memcache->get('123');
if(!$childrens){
   // $memcache->set();
    $memcache->set('123', array(1,2,3,4,5,6,7,8), false, 3600);
}
print_r($childrens);