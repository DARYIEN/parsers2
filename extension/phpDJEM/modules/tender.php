
<?php

require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
$parent1 = R('DJEM')->Parent($_REQUEST["razmer"], 4)->{'msort'};
$parent2 = R('DJEM')->Parent($_REQUEST["razmer"], 5)->{'_id'};
//$name = $_REQUEST["id"];
$name = $_REQUEST["razmer"];
$type = $_REQUEST["type"];
$id = $_REQUEST["razmer"];
$parent3 = $_REQUEST["procat"];
$parent4 = str_replace(",",".",R('DJEM')->Load($id)->factor);

if ($_COOKIE["city"]) { 
    if ($_REQUEST["city"]!="" && $_COOKIE["city"]!=$_REQUEST["city"]) {
     setcookie("city",$_REQUEST["city"],0,"/");
     $city=$_REQUEST["city"];   
    } else {
    $city= $_COOKIE["city"]; 
    }
     

     } else
     
    {
    	if ($_REQUEST["city"]==""){
    	
     $city='4344728';
                 
 }
else{
    $city=$_REQUEST["city"];
} }


echo '<div style="height: 67px;">';
if (R('DJEM')->Parent($_REQUEST["razmer"], 4)->{'level_sis'} == 'on') { ?>

<h1 style="float: left; display: block; width: 520px; overflow: hidden; position: absolute;"><?php echo $parent1.' ';  echo R('DJEM')->Load($name)->msort; echo ' '.$_REQUEST['filter']; echo ', '.R('DJEM')->Load($city)->_name;?></h1>
<?php } else { ?>
<h1 style="float: left; display: block; width: 520px; overflow: hidden; position: absolute;"><?php echo $parent1.' '; echo R('DJEM')->Load($parent2)->msort.' ';  echo R('DJEM')->Load($name)->msort; echo ' '.$_REQUEST['filter']; echo ', '.R('DJEM')->Load($city)->_name; }?></h1>
<a style="float:right; margin-left: 10px;" class="button-border1 button-border" href="/applications.phtml">Добавить объявление о продаже</a>
<a style="float:right; margin-left: 10px;" class="button-border1 button-border" href="/add_price.phtml">Разместить прайс</a>
<?php
 include($_SERVER['DOCUMENT_ROOT'] . '/system/includes/document7324850.phtml');
?>

<a style="float:right" href="http://www.metal100.ru/favorits" class="button-border1 button-border">Избранное</a>
</div>

<?php 

if ($parent4) {






?>



<div style="top: 147px;" class="calc_container">



	<div class="input_wrap ">
    <div class="left_bord_wrap">
    </div><input style="width: 110px;" id="calc_w" class="calc_input calc_grey inp_text" onkeyup="calc(1,'')" onfocus="calc_grey(this.id,0,'')" onblur="calc_grey(this.id,0,'')" value="тонны" />
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
  
    </div>	

<div class="clear"></div>
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
 $('#calc_l'+kida).val(l+" "+"<?php echo R('DJEM')->Load(R('DJEM')->Parent($_REQUEST["razmer"], 4)->{'_id'})->mera; ?>");
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





<div class="scrolling">







        <table bordercolor="#dedede" cellspacing="0" class="tablesorter" id="table-price">
      <?php if ($_REQUEST['sort_type'] == '' || $_REQUEST['sort_type'] == '-#') {
    $w = '#';
} else {
    $w = '-#';
} ?> 
        <thead>
        <tr  class="sort" url="<?php echo R('DJEM')->Load($id)->{'_url'}; ?>" >
        <th sort_type="<?php echo $w; ?>" class="header" sort="_name">Наименование <?php if($_REQUEST['sort']=='_name') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; }  ?></th> 
        <th sort_type="<?php echo $w; ?>" class="header" sort="size">длина, размер <?php if($_REQUEST['sort']=='size') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; }  ?></th> 
                 
        <th sort_type="<?php echo $w; ?>" class="header" sort="steel">Марка стали  <?php if($_REQUEST['sort']=='steel') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; }  ?></th> 
                <th sort_type="<?php echo $w; ?>" class="header" sort="Ghost">Гост <?php if($_REQUEST['sort']=='Ghost') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; }  ?></th>
          <th sort_type="<?php echo $w; ?>" class="header" sort="wholesale">цена опт  <?php if($_REQUEST['sort']=='wholesale') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; }  ?></th>
               <th sort_type="<?php echo $w; ?>" style="width: 50px;" class="header" sort="retail">цена роз <?php if($_REQUEST['sort']=='retail') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; } if (!$_REQUEST['sort']) echo '&uarr;&darr;'  ?></th>
          
         <th sort_type="<?php echo $w; ?>" class="header" sort="Notes">Примечания (кол-во) <?php if($_REQUEST['sort']=='Notes') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; }  ?></th> 
        <th sort_type="<?php echo $w; ?>" class="header" sort="name_of_organization">Наименование организации <?php if($_REQUEST['sort']=='name_of_organization') if($w=='-#'){ echo ' &uarr;';} else { echo ' &darr;'; }  ?></th>
      <th  class="header" style="width: 70px;" >Добавить в избранное<br /><input title="Добавить всё" type="checkbox"  class="add_fav_all"></th>
        
                 </tr>
                 </thead>
           
                 
         <tbody>
          
                  
            <?php if ($_REQUEST['sort']) { 
                 $var['sor'] = $w . $_REQUEST['sort'];
                 
   } else { 
    $var['sor'] = '-favored,#retail';  } 
    
     $foreach__b =new DJEMForeach(R('DJEM'));
$foreach__b->Path('main.metal.tender.$');

    if ($type == '220') {
    ?>
 <style>
/*для двухуровневой системы!*/
#right-container{
    width:933px;
}

</style>

<?php }
    $foreach__b->Where(' fit2 = \'' . $name . '\' && city= \'' . $city . '\''); 
   $n=trim($_REQUEST['filter']);
   
if ($_REQUEST['filter'] && $_REQUEST['filter']!='Любая')  $foreach__b->Where(' fit2 = \'' . $name . '\'  && city= \'' . $city . '\'  && '.$_REQUEST['fit_type'].'= \''.$n.'\'');
  





$foreach__b->Sort($var['sor']);
$foreach__b->Fields('Ghost', 'Notes', '_modify_time', '_name', 'characteristic',
    'name_of_organization', 'number', 'retail', 'size', 'steel', 'view', 'wholesale');
$foreach_b__total = $foreach__b->Size();
$foreach_b__count = 0;
foreach ($foreach__b as $foreach_b) {
    ++$foreach_b__count;
    
    if ($foreach_b->{'retail'}!="99999999999" || $foreach_b->{'wholesale'}!="99999999999") {
    
     ?>
         
        <tr <? if ($foreach_b->{'favored'}=='on' && $foreach_b__count<4) {echo "class='activ'";} ?> docid="<?php echo $foreach_b->{'_id'}; ?>"> 
        
        <td sort="_name"><?php echo $parent1 . '<br>';
    if ($foreach_b->{'fit1'} != 'non') {
        echo R('DJEM')->Load($parent2)->_name;
    } ?> <?php echo
R('DJEM')->Load($name)->_name; ?></td>
        <td  sort="size"><?php echo $foreach_b->{'size'}; ?></td>
        
         
        <td  sort="steel"><?php echo $foreach_b->{'steel'}; ?></td>
         <td  sort="Ghost"><?php echo $foreach_b->{'Ghost'}; ?></td>
     <td sort="wholesale" style="white-space: nowrap"><?php if ($foreach_b->{'wholesale'}!="99999999999" && $foreach_b->{'wholesale'}!="non") {  echo '<span title="Сообщить о неверной цене" class="complaint"></span>'.$foreach_b->{'wholesale'}; } else { echo "-"; } ?></td>       
         <td  sort="retail" style="white-space: nowrap"><?php if ($foreach_b->{'retail'}!="99999999999" && $foreach_b->{'retail'}!="non") {  echo $foreach_b->{'retail'}; } else { echo "-"; }   ?></td>
    
        
        
         <td sort="Notes"><?php echo $foreach_b->{'Notes'}; ?> <?php echo $foreach_b->{
'number'}; ?></td>
        <td sort="name_of_organization"><div style=""><div class="tips"  id="<?php echo
$foreach_b->{'_id'}; ?>">
<div class="move_area"><h3>Адрес и телефон предприятия:</h3></div>
        
      <? if ($foreach_b->{'company'}) { ?>
      
        <?php $djem = R('DJEM');
        $doc = $djem->Load($foreach_b->{'company'}); ?>
        <div class="wrap_org">
        <table id="sh">
            <tr><td >Название организации:</td><td><?php echo $doc->_name; ?></td></tr>
            <tr><td>Телефон:</td><td><?php echo $doc->teleph; ?></td></tr>
            <tr><td>Адрес офиса:</td><td><? echo $doc->adress; ?></td></tr>
            <tr><td>Адрес сайта:</td><td><noindex><?php echo str_replace('<a','<a rel="nofollow"',$doc->website_address); ?></noindex></td></tr>
            <tr><td>Email:</td><td><a href="mailto:<?php echo $doc->email; ?>"><?php echo $doc->email; ?></a></td></tr>
            <tr><td>Адрес склада:</td><td><? echo $doc->adress2; ?></td></tr>
            <tr><td>Описание:</td><td style="text-align: left;"><?php echo substr($doc->activity_description,0,250); ?></td></tr>
        </table>
      
      </div>
      
      
      
      
      
      
      
      
      
      
      
      
      
      
      
        <? } else { ?>
         <div class="wrap_org">

        <table id="sh">
            <tr><td>Название организации:</td><td>Название вашей организации</td></tr>
            <tr><td>Телефон:</td><td>Телефон вашей организации</td></tr>
            <tr><td>Адрес офиса:</td><td>Адрес вашей организации</td></tr>
            <tr><td>Адрес сайта:</td><td><a href="/">Адрес сайта вашей организации</a></td></tr>
            <tr><td>Email:</td><td><a href="/">Электронная почта вашей организации</a></td></tr>
            <tr><td>Адрес склада:</td><td>Адрес вашей организации</td></tr>
            <tr><td>Описание:</td><td style="text-align: left;">Описание вашей организации</td></tr>
        </table>
         </div>
        <? } ?>
        <div class="cls"></div><div class="clear"></div></div></div><span class="org" org="<?php echo
$foreach_b->{'_id'}; ?>" style="cursor:pointer;"><?php 

    echo R('DJEM')->Load($foreach_b->{'company'})->{'_name'}; 


 ?></span></td>
       
     <td>
     
  <?php 
     $mystring = $_COOKIE['add_fav'];
$findme   = $foreach_b->{'_id'};
$posit = strpos($mystring, $findme);

     if($posit!==false){
         echo "<input checked type='checkbox'/>";
     } else {
              echo "<input class='add_fav' id='".$foreach_b->{'_id'}."'  type='checkbox' />";  
     }
     
     ?> </td>
             
         </tr>
       
         <?php 
     if ($foreach_b->{'steel'}) $marka[]= $foreach_b->{'steel'}; 
     if ($foreach_b->{'Ghost'}) $Ghost[]= $foreach_b->{'Ghost'};     
         
         } }
;
unset($foreach__b);

echo "<div class='filt' style='float: left;position: absolute;top: -46px; width: 520px;'>Выбор стали: <a class='filter'>Все</a>,  ";
echo $n;
foreach (array_unique($marka) as $i) {
    echo "  <a type='steel' value='".trim($i)."' class='filter'> ".$i."</a>,";
}
echo $n;
//echo "  <a value='' class='filter'>не указанно</a>";
echo '<br>';

echo "Выбор ГОСТ: <a class='filter'>Все</a>,  ";
foreach (array_unique($Ghost) as $i) {
    if ($_REQUEST['filter']==$i) {
        echo "  <b> ".$i."</b>,";   
    } else{
       echo "  <a type='Ghost' value='".trim($i)."' class='filter'> ".$i."</a>,";   
       
    }
}
//echo "  <a value='' class='filter'>не указанно</a>";
echo '</div>';
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
         
       
         
</div>





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
    city: <?php echo $_REQUEST['city'] ?>,
    type: '<?php echo $_REQUEST['type'] ?>',
    sort: $(this).attr('sort'),
    sort_type:'<?php echo $w ?>',
    filter: '<?php echo str_replace("\\","\\\\",$_REQUEST['filter']) ?>',
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
    city: <?php echo $_REQUEST['city'] ?>,
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