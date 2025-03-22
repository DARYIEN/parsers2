<?php
require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
$foreach__x = new DJEMForeach(R('DJEM'));
$foreach__x->Path('main.metal.price.*');
$foreach__x->Fields();
$foreach_x__total = $foreach__x->Size();  
$foreach_x__count = 0;
foreach ($foreach__x as $foreach_x) {
    ++$foreach_x__count; 
};
unset($foreach__x);
R('DJEM')->Load('main.metal.price')->Set('_sub_documents', $foreach_x__total)->Store();



$foreach__x = new DJEMForeach(R('DJEM'));
$foreach__x->Path('main.metal.3868918.*');
$foreach__x->Fields();
$foreach_x__total = $foreach__x->Size();   
$foreach_x__count = 0;
foreach ($foreach__x as $foreach_x) {
    ++$foreach_x__count; 
};
unset($foreach__x);
R('DJEM')->Load('main.metal.3868918')->Set('_sub_documents', $foreach_x__total)->Store();

?>