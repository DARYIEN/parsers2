
<?php

require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
$parent1 = R('DJEM')->Load($id)->msort;
$idd = $id;
$parent2 = R('DJEM')->Parent($_REQUEST["razmer"], 5)->{'_id'};
//$name = $_REQUEST["id"];
$name = $_REQUEST["razmer"];
$type = $_REQUEST["type"];
$id = $_REQUEST["razmer"];
$parent3 = $_REQUEST["procat"];
$parent4 = str_replace(",", ".", R('DJEM')->Load($id)->factor);

$trans = array(
    "вл" => "высоколегированная",
    "гофр" => "гофрированная",
    "нж" => "нержавеющая",
    "эc" => "электросварные",
    "э/c" => "электросварные",
    "эл сварные" => "электросварные",
    "оцинк" => "оцинковка",
    "квадр" => "квадрат",
    "квад" => "квадрат");

if ($_COOKIE["city"]) {
    if ($_REQUEST["city"] != "" && $_COOKIE["city"] != $_REQUEST["city"]) {
        setcookie("city", $_REQUEST["city"], 0, "/");
        $city = $_REQUEST["city"];
    } else {
        $city = $_COOKIE["city"];
    }


} else {
    if ($_REQUEST["city"] == "") {

        $city = '4344728';

    } else {
        $city = $_REQUEST["city"];
    }
}


    if (R('DJEM')->Load($id)->h1) {
        echo "<h1 style='width: 485px;'>".R('DJEM')->Load($id)->h1."</h1>";
    } else {
if (R('DJEM')->Parent($_REQUEST["razmer"], 4)->{'level_sis'} == 'on') {

    ?>

<h1 style="width: 485px;"><?php
    echo strtr($parent1, $trans) . ' ';
    echo strtr(R('DJEM')->Load($parent2)->msort, $trans);
    echo ' ' . strtr($_REQUEST['filter'], $trans);
    echo ', ' . R('DJEM')->Load($city)->_name;
?></h1>

<?php } else { ?>

<h1 style="width: 485px;"><?php


    echo strtr($parent1, $trans) . ' ';
    echo strtr(R('DJEM')->Load($parent2)->msort, $trans) . ' ';
    echo R('DJEM')->Load($name)->msort;
    echo ' ' . $_REQUEST['filter'];
    echo ', ' . R('DJEM')->Load($city)->_name;

}
?></h1>
<?php } ?>
<div class="clear"></div>
<div style="top: 113px;" class="calc_container">

<span class="button-border2" style="text-decoration: none; position: relative; top:4px;  color: #385574;">Калькулятор:</span>

	<div class="input_wrap ">
    <div class="left_bord_wrap">
    </div><input style="width: 113px;" id="calc_w" class="calc_input calc_grey inp_text" onkeyup="calc(1,'')" onfocus="calc_grey(this.id,0,'')" onblur="calc_grey(this.id,0,'')" value="тонны" />
    <div class="right_bord_wrap">
    </div></div>
    <div class="input_wrap">
    <div class="left_bord_wrap">
    </div>
    <input style="width: 110px;" id="calc_l" class="calc_input calc_grey inp_text" onkeyup="calc(0,'')" onfocus="calc_grey(this.id,0,'')" onblur="calc_grey(this.id,0,'')" value="метры" />
    <div class="right_bord_wrap">
    </div></div>
 
    <input type="hidden" id="calc_koef" value="1" /> 
    <input type="hidden" id="calc_stal_koef" value="<?php echo $parent4; ?>" />
    <input type="hidden" id="calc_w_label" value="тонны" />
    <input type="hidden" id="calc_l_label" value="метры" />
    <input type="hidden" id="calc_last_w_to_l" value="" />
  <div style="clear: both;width: 100%;"></div>
    </div>	

<div class="clear"></div>

<div class="characteristic" >
<div  >
<img src="/image/poisk2.png" style="float: left; margin-right: 5px; margin-top: 9px;"/>
<span><a class="button-border1 button-border2" href="/applications.phtml">Добавить заявку на покупку</a></span>
</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/system/includes/document7324850.phtml');
?>
<div >
<img style="float: left; margin-right: 5px; margin-top: 8px;" src="/image/poisk1.png"/>
<span><a style="float:left" href="http://www.metal100.ru/favorits" class="button-border1 button-border2">Избранное</a></span>

</div>
</div>


<?php

if ($parent4) {

?>

   <script>
    function calc_grey(id,prinydit,kida){
var e=$('#'+id)
var calc_text=$('#'+id+'_label').val();
if(e.val()==calc_text){ e.val(''); e.removeClass('calc_grey'); }
else{
if(!e.val() || prinydit){ e.val(calc_text); e.addClass('calc_grey'); }
}
}
function calc(w_to_l,kida){
var w,l;
calc_koef=$('#calc_koef'+kida).val();
calc_stal_koef=$('#calc_stal_koef'+kida).val();
if(w_to_l==1){
w=$('#calc_w'+kida).val();
l=w.replace(',', '.')/(calc_koef*calc_stal_koef)*1000;
if(l){l=Math.round(l*100)/100;
 $('#calc_l'+kida).val(l+" "+"<?php echo R('DJEM')->Load(R('DJEM')->Parent($_REQUEST["razmer"],
4)->{'_id'})->mera; ?>");
  $('#calc_l'+kida).removeClass('calc_grey'); }
else calc_grey('calc_l',1,kida);
$('#calc_last_w_to_l'+kida).val('');
}
else{
l=$('#calc_l'+kida).val();
w=l.replace(',', '.')*(calc_koef*calc_stal_koef)/1000;
if(w){ w=Math.round(w*100)/100;
 $('#calc_w'+kida).val(w+" "+"т");
  $('#calc_w'+kida).removeClass('calc_grey');}
else calc_grey('calc_w',1,kida);
$('#calc_last_w_to_l'+kida).val('');
}
}

</script>

<?php }

?>



<div style="clear: both;width: 100%;"></div>

<div class="scrolling">







        <table bordercolor="#dedede" cellspacing="0" class="tablesorter" id="table-price">
      <?php if ($_REQUEST['sort_type'] == '' || $_REQUEST['sort_type'] == '-#') {
    $w = '#';
} else {
    $w = '-#';
} ?> 
        <thead>
        <tr  class="sort" url="<?php echo R('DJEM')->Load($id)->{'_url'}; ?>" >
        <th  sort_type="<?php echo $w; ?>" class="header" sort="_name">Наименование <?php if ($_REQUEST['sort'] ==
'_name')
    if ($w == '-#') {
        echo ' &uarr;';
    } else {
        echo ' &darr;';
    } ?></th> 
        <th style="width: 350px;" sort_type="<?php echo $w; ?>" class="header" sort="size">Характеристики </th> 
           


               <th sort_type="<?php echo $w; ?>" style="width: 50px;" class="header" sort="retail">Цена оптовая/розничная <?php if ($_REQUEST['sort'] ==
'retail')
    if ($w == '-#') {
        echo ' &uarr;';
    } else {
        echo ' &darr;';
    }
    if (!$_REQUEST['sort'])
        echo '&uarr;&darr;' ?></th>
          
       <th sort_type="<?php echo $w; ?>" class="header" sort="size">Компания </th>      
     
      <th  class="header" style="width: 24px;" ><br /><input title="Добавить всё в избранное" type="checkbox"  class="add_fav_all"></th>
        
                 </tr>
                 </thead>
           
                 
         <tbody>
        <tr>

        </tr>  
                  
            <?php if ($_REQUEST['sort']) {
        $var['sor'] = $w . $_REQUEST['sort'];

    } else {
        $var['sor'] = '-favored,#retail';
    }

    $foreach__b = new DJEMForeach(R('DJEM'));
$foreach__b->Path('main.metal.prise.$');

if ($type == '220') {
?>
 <style>
/*для двухуровневой системы!*/
#right-container{
    width:933px;
}

</style>

<?php }

$foreach__b->Where(' fit2 = \'' . $name . '\'  && city= \'' . $city . '\'');
$n = trim($_REQUEST['filter']);

if ($_REQUEST['filter'] && $_REQUEST['filter'] != 'Любая')
    $foreach__b->Where(' fit2 = \'' . $name . '\'  && city= \'' . $city . '\'  && ' .
        $_REQUEST['fit_type'] . '= \'' . $n . '\'');


$foreach__b->Sort($var['sor']);
$foreach__b->Fields('Ghost', 'Notes', '_modify_time', '_name', 'characteristic',
    'name_of_organization', 'number', 'retail', 'size', 'steel', 'view', 'wholesale');
$foreach_b__total = $foreach__b->Size();
$foreach_b__count = 0;
foreach ($foreach__b as $foreach_b) {
    ++$foreach_b__count;

    if ($foreach_b->{'retail'} != "99999999999" || $foreach_b->{'wholesale'} !="99999999999") {

?>
         
        <tr  <? if ($foreach_b->{'favored'} == 'on' && $foreach_b__count < 4) {
            echo "class='activ'";
        } ?> docid="<?php echo $foreach_b->{'_id'}; ?>"> 
        
        <td class="org" org="<?php echo $foreach_b->{'_id'}; ?>" style="font-weight: bold; white-space: nowrap;" sort="_name"><?php echo $parent1 . ' ';
        if ($foreach_b->{'fit1'} != 'non') {
            echo R('DJEM')->Load($parent2)->_name;
        } ?> <?php echo R('DJEM')->Load($name)->_name; ?></td>
        <td style="text-align: left;" class="org" org="<?php echo $foreach_b->{'_id'}; ?>"  sort="size">
                <? if ($foreach_b->{'size'}) { ?><i>Длина:</i> <?php echo $foreach_b->{'size'}; } ?>
        <? if ($foreach_b->{'steel'}) { ?><i>Сталь:</i> <?php echo $foreach_b->{'steel'}; } ?>
        <? if ($foreach_b->{'Ghost'}) { ?><i>Гост:</i> <?php echo str_replace("ГОСТ","",$foreach_b->{'Ghost'}); } ?>
        <?php echo $foreach_b->{'Notes'} ?> 
        </td>
        
     
  
         <? //'<span title="Сообщить о неверной цене" class="complaint"></span>' ?>         
      
         <td style="white-space: nowrap;" class="org" org="<?php echo $foreach_b->{'_id'}; ?>"  sort="retail" style="white-space: nowrap"><b><?php
         if ($foreach_b->{
'wholesale'} != "99999999999" && $foreach_b->{'wholesale'} != "non") {
            echo str_replace(",0","",number_format($foreach_b->{'wholesale'}, 1, ',', ' ')) . ' / ';
        }
         
         
          if ($foreach_b->{
'retail'} != "99999999999" && $foreach_b->{'retail'} != "non") {
            echo str_replace(",0","",number_format($foreach_b->{'retail'}, 1, ',', ' ')) . ' р.';
        } else {
            echo "-";
        } ?></b></td>
    
            <td style="text-align: left; white-space: nowrap;" class="org" org="<?php echo $foreach_b->{'_id'}; ?>" >
        
       
        <?php echo "<span style='color: #278DE8; text-decoration: underline;' >".R('DJEM')->Load($foreach_b->{'company'})->{'_name'}."</span><br><b>".str_replace(" ","",R('DJEM')->Load($foreach_b->{'company'})->{'teleph'})."</b>"; ?> 
        </td>
        
         
        <td sort="name_of_organization"><div style=""><div class="tips"  id="<?php echo
        $foreach_b->{'_id'}; ?>">

        
      <? if ($foreach_b->{'company'}) { 
        $djem = R('DJEM');
            $doc = $djem->Load($foreach_b->{'company'}); ?>
        <div class="wrap_org">
        <?php
            $transl = array("<a" => "<noindex><a rel='nofollow'", "a>" => "a> </noindex>");
?> 
        
        <table style="border: 0;" id="sh">
        <tr id="leftra"><td id="leftra"  class="move_area" colspan="2"><h3 style="margin: 0;padding:0;color:#385574;font-size:14px;">Адрес и телефон вашего предприятия:</h3></td></tr>
            <tr id="leftra"><td>Название организации:</td><td id="leftra"><?php echo strtr($doc->_name, $transl); ?></td></tr>
            <tr id="leftra"><td>Телефон:</td><td id="leftra"><?php echo strtr($doc->teleph, $transl); ?></td></tr>
            <tr id="leftra"><td>Адрес офиса:</td><td id="leftra"><?php echo strtr($doc->adress, $transl); ?></td></tr>
            <tr id="leftra"><td>Адрес сайта:</td><td id="leftra"><?php echo strtr($doc->website_address, $transl); ?></td></tr>
            <tr id="leftra"><td>Email:</td><td id="leftra"><a rel="nofollow" href="mailto:<?php echo strtr($doc->email, $transl); ?>"><?php echo $doc->email; ?></a></td></tr>
            <tr id="leftra"><td>Адрес склада:</td><td id="leftra"><?php echo strtr($doc->adress2, $transl); ?></td></tr>
            <tr id="leftra" style="border-bottom:none!important;"><td  style="border-bottom:none!important;">Описание:</td><td id="leftra" style="text-align: left;border-bottom:none!important;"><?php echo $doc->activity_description; ?></td></tr>
        </table>
     
      </div>
      
      
        <? } else { ?>
         <div class="wrap_org">

        <table id="sh">
            <tr  id="leftra"><td>Название организации:</td><td id="leftra">Название вашей организации</td></tr>
            <tr id="leftra"><td>Телефон:</td><td id="leftra">Телефон вашей организации</td></tr>
            <tr id="leftra"><td>Адрес офиса:</td><td id="leftra">Адрес вашей организации</td></tr>
            <tr id="leftra"><td>Адрес сайта:</td><td id="leftra"><a href="/">Адрес сайта вашей организации</a></td></tr>
            <tr id="leftra"><td>Email:</td><td id="leftra"><a href="/">Электронная почта вашей организации</a></td></tr>
            <tr id="leftra"><td>Адрес склада:</td><td id="leftra">Адрес вашей организации</td></tr>
            <tr id="leftra" style="border-bottom:none!important;border-bottom:none!important;"><td style="border-bottom:none!important;">Описание:</td><td id="leftra" style="text-align: left;border-bottom:none!important;">Описание вашей организации</td></tr>
        </table>
         </div>
        <? } ?>
        <div class="cls"></div><div class="clear"></div></div></div>
     
  <?php
        $mystring = $_COOKIE['add_fav'];
        $findme = $foreach_b->{'_id'};
        $posit = strpos($mystring, $findme);

        if ($posit !== false) {
            echo "<input checked type='checkbox'/>";
        } else {
            echo "<input title='Добавить в избранное' class='add_fav' id='" . $foreach_b->{'_id'} .
                "'  type='checkbox' />";
        }

?> </td>
             
         </tr>
       
         <?php
        if ($foreach_b->{'steel'})
            $marka[] = $foreach_b->{'steel'};
        if ($foreach_b->{'Ghost'})
            $Ghost[] = $foreach_b->{'Ghost'};

    }
}
;
unset($foreach__b);


if ($foreach_b__total == 0) {

?>
         
         <style>
/*для двухуровневой системы!*/
#right-container{
    width:828px;
    

}
#test tr:hover td{
    background:#F2F2F2!important;
    
}
</style>
         
         <tr><td id="wraning" colspan="10">Извините,но по вашему результату ничего не найдено.Попробуйте поискать в других разделах. </td></tr>
         
         <?php
}
?>          
         </tbody>
         </table> 
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
</div>
<div style="clear:both;"></div>
         
  <?  echo R('DJEM')->Load($id)->seo_text; ?>
         
<script type="text/javascript">

		$('.pane').scrollTo('#<? echo $id; ?>');

</script>


<script>
$('.add_fav').click(function() {
    if($.cookie("add_fav")==null) {
            $.cookie("add_fav", $(this).attr('id'), { expires : 100,path    : '/'  });  
           
    } else {
              $.cookie("add_fav", $.cookie("add_fav")+','+$(this).attr('id'), { expires : 100,path    : '/'  });
    }
     
           alert('Добавленно в избранное');   
});

$('.add_fav_all').click(function() {
    if($.cookie("add_fav")==null) { $.cookie("add_fav", $('.add_fav:first').attr('id'), { expires : 100,path    : '/'  }); }
    $(".add_fav").each(function(indx, element){
  $.cookie("add_fav", $.cookie("add_fav")+','+$(element).attr('id'), { expires : 100,path    : '/'  });
  $(element).attr("checked","checked");
});
    
           
           alert('Добавленны все пункты избранное');   
});

$('.org').click(function(){ 
   
    $('.tips').hide();


    
    var element = $(this).parent().find('.tips');

 
    element.css("top", (($(window).height() - element.outerHeight()) / 2) + $(window).scrollTop()-300 + "px");
    element.css("left", (($(window).width() - element.outerWidth()) / 2) + $(window).scrollLeft()-400 + "px");

element.show();

});



            $('.cls').click(function(){ 
    $(this).parent().hide();
            });
            
            
    $(".sort .header").click(function(){
        $.post(
  "/system/php/modules/table.php",
  {
   razmer: <?php echo $_REQUEST["razmer"] ?>, 
    city: 4344728,
    type: '<?php echo $_REQUEST['type'] ?>',
    sort: $(this).attr('sort'),
    sort_type:'<?php echo $w ?>',
    filter: '<?php echo str_replace("\\", "\\\\", $_REQUEST['filter']) ?>',
    fit_type:'<?php echo $_REQUEST['fit_type'] ?>'
  },
  onAjaxSuccess
);
 
function onAjaxSuccess(data)
{
      filt=$('.filt').html();
  $('.table').html(data);
   $('.filt').html(filt);
}
    });  
    
    
        $(".filter").live('click',function(){
            $(".filter").css({"font-weight":"normal"});
            $(this).css({"font-weight":"bold"});
        $.post(
  "/system/php/modules/table.php",
  {
   razmer: <?php echo $_REQUEST["razmer"] ?>, 
    city: 4344728,
    type: '<?php echo $_REQUEST['type'] ?>',
    sort: '<?php echo $_REQUEST['sort'] ?>',
    sort_type:'<?php echo $w ?>',
    filter: $(this).attr('value'),
    fit_type:$(this).attr('type')
  },
  onAjaxfit
);
 
function onAjaxfit(data)
{

  filt=$('.filt').html();
  $('.table').html(data);

  $('.filt').html(filt);
}
    });      
    


$(function() {
        $( ".tips" ).draggable({ handle: ".move_area" ,containment:'document'});
        
    });


</script>






<script>
$('.complaint').click(function(){
if (confirm("Вы хотите сообщить о неверной цене?")) {
alert("Сообщение принято");
var a=$(this).parent().parent().attr('docid');
$.post(
  "/system/php/modules/error-message.php",
  {
  
    param1: a, 
    param2: 2
    
 
  },
  AjaxSuccess

);

  
} else {

}
function AjaxSuccess(data)
{
  alert(data);

}
}); 
</script>