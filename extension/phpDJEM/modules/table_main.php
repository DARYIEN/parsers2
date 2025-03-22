
<?php

require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');



$trans = array(
    "вл" => "высоколегированная",
    "гофр" => "гофрированная",
    "нж" => "нержавеющая",
    "эc" => "электросварные",
    "э/c" => "электросварные",
    "эл сварные" => "электросварные",
    "оцинк" => "оцинковка",
    "квадр" => "квадрат",
    "квад" => "квадрат",
    "гк" => "горячекатаный",
    "хк" => "холоднокатаный",
    "эс" => "электросварные",
    "мельнич" => "мельничная");

?>

<div class="information" id="right-container">

<?
if ($h1) {
    echo "<h1>".$h1."</h1>";
    } elseif(R('DJEM')->Parent($_REQUEST["razmer"], 4)->{'level_sis'} == 'on') {
    ?>

<h1 ><?php
    echo $parent1 . ' ';
    echo R('DJEM')->Load($name)->msort;
    echo ' ' . $_REQUEST['filter'];
    echo ', ' . R('DJEM')->Load($city)->_name;
?></h1>
<?php 
  //  
} else { ?>
<h1 ><?php
    echo strtr($parent1, $trans) . ' ';
    echo R('DJEM')->Load($parent2)->msort . ' ';
    echo R('DJEM')->Load($name)->msort;
    echo ' ' . $_REQUEST['filter'];
    echo ', ' . R('DJEM')->Load($city)->_name;
}
?></h1>
<h2>Выберите размер:</h2>
<div>
<?
$query = R('DJEM')->GetForeach();
$vids =  R('DJEM')->GetForeach();
$vids->Path($path.'.$')->sort("#_name,msort");
foreach ($vids as $vid_item)
{
    if ($vid_item->_type!=221) {$path_item=$path;} else {$path_item=$vid_item->_path;}
$query->Path($path_item.'.*')->Where('_type=36 && city in 4344728')->sort("#_name,msort");
foreach ($query as $item)
{
   if ($vid!=R('DJEM')->Load($item->_parent_id)->_name)
   {
    $vid=R('DJEM')->Load($item->_parent_id)->_name;
  if (R('DJEM')->Load($item->_parent_id)->_type==221)  echo "</div><div class='block'><p style='font-size: 15px; font-weight: bold;font-family: PT Sans,Arial;'>".R('DJEM')->Load($id)->_name." $vid</p>";
   }
    
    echo "<a style='margin:6px; display: inline-block; width: 100px;' href='$item->url3'>$item->_name</a>";
    }

    if ($vid_item->_type!=221) break;
 }
?>
<div style="margin: 20px 10px;">
<!-- Яндекс.Директ -->
<script type="text/javascript">
yandex_partner_id = 111232;
yandex_site_bg_color = 'FFFFFF';
yandex_stat_id = 2;
yandex_site_charset = 'utf-8';
yandex_ad_format = 'direct';
yandex_font_size = 1;
yandex_direct_type = 'horizontal';
yandex_direct_limit = 4;
yandex_direct_title_font_size = 3;
yandex_direct_header_bg_color = 'FEEAC7';
yandex_direct_title_color = '385574';
yandex_direct_url_color = '006600';
yandex_direct_text_color = '000000';
yandex_direct_hover_color = '0066FF';
yandex_direct_favicon = false;
document.write('<sc'+'ript type="text/javascript" src="http://an.yandex.ru/system/context.js"></sc'+'ript>');
</script>
</div>
<h2>Обьявления</h2>
<?
$sbj = R('DJEM')->GetForeach();
$sbj->Path('main.Advertisement.$')->Where("_name like '".R('DJEM')->Load($id)->msort."' || msort like '".R('DJEM')->Load($id)->msort."'")->limit(5)->sort('random');
foreach ($sbj as $it)
{
    echo "<p style='margin:7px 0' ><a href='/advertisement/bulletin_board.phtml?cont=$it->_id'>$it->_name</a></p>";
    }
if (!count ($sbj)) echo "Обьявлений для этого раздела нет.<br>"
 
?>
<a href='/advertisement/bulletin_board.phtml'>Посмотреть все обьявления</a>

<h2>Спаравочная информация</h2>





