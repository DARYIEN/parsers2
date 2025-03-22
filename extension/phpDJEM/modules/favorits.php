
<?php

require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
$parent1 = R('DJEM')->Parent($_REQUEST["razmer"], 4)->{'msort'};
$parent2 = R('DJEM')->Parent($_REQUEST["razmer"], 5)->{'_id'};
//$name = $_REQUEST["id"];
$name = $_REQUEST["razmer"];
$type = $_REQUEST["type"];
$id = $_REQUEST["razmer"];
$parent3 = $_REQUEST["procat"];
$parent4 = $_REQUEST["factor"];





?>
<h1>Избранное</h1>
<div class="characteristic"></div>










<div class="scrolling">







        <table bordercolor="#dedede" cellspacing="0" class="tablesorter" id="table-price">
      <?php if ($_REQUEST['sort_type'] == '' || $_REQUEST['sort_type'] == '#') {
    $w = '-#';
} else {
    $w = '#';
} ?> 
        <thead>
        <tr  class="sort" url="<?php echo R('DJEM')->Load($id)->{'_url'}; ?>" >
        <th sort_type="<?php echo $w; ?>" class="header" sort="_name">Наименование</th> 
        <th sort_type="<?php echo $w; ?>" class="header" sort="size">длина, размер</th> 
                 
        <th sort_type="<?php echo $w; ?>" class="header" sort="steel">Марка стали
        
        	<ul class="drop" style="width:219px ;">
  <li>
  <a city="4344728">Москва</a>
  </li>

    
                    

                    
		<li><a city="">
        11

        </a></li>

                                        
	</ul>
    </th> 
                <th sort_type="<?php echo $w; ?>" class="header" sort="Ghost">Гост</th>
                <th sort_type="<?php echo $w; ?>" class="header" sort="retail">цена опт</th>
          <th sort_type="<?php echo $w; ?>" class="header" sort="wholesale">цена роз</th>
         <th sort_type="<?php echo $w; ?>" class="header" sort="Notes">Примечания (кол-во)</th> 
        <th sort_type="<?php echo $w; ?>" class="header" sort="name_of_organization">Наименование организации</th>
      <th  class="header" ><a class="delete_all">Удалить всё</a></th>
        
                 </tr>
                 </thead>
           
                 
         <tbody>
          
                  
            <?php if ((isset($_REQUEST['sort']) ? $_REQUEST['sort'] : '') !=
'sort') { ?>  
                <?php $var['sor'] = $w . (isset($_REQUEST['sort']) ? $_REQUEST['sort'] :
''); ?>     <?php } else { ?>   
                                                                                                 
                  <?php $var['sor'] = 'date'; ?>     <?php } ?>         <?php $foreach__b =
new DJEMForeach(R('DJEM'));
$foreach__b->Path('main.metal.tender.$,main.metal.price.$');


    $foreach__b->Where(' _id in \''.$_COOKIE['add_fav'].'\'' );






$foreach__b->Sort($var['sor']);
$foreach__b->Fields('Ghost', 'Notes', '_modify_time', '_name', 'characteristic',
    'name_of_organization', 'number', 'retail', 'size', 'steel', 'view', 'wholesale');
    $foreach__b->Limit(100);
$foreach_b__total = $foreach__b->Size();
$foreach_b__count = 0;
foreach ($foreach__b as $foreach_b) {
    ++$foreach_b__count; ?>
         
        <tr docid="<?php echo $foreach_b->{'_id'}; ?>"> 
        
        <td sort="_name"><?php
        echo '<a href="/prodazha'.str_replace(".phtml",'',R('DJEM')->Parent($foreach_b->{'fit2'}, 4)->{'_url'}).'/'.$foreach_b->{'fit2'}.'/'.$city.'">';
        if ($foreach_b->{'fit1'}!='non') {
         echo $foreach_b->{'_name'}.' '.R('DJEM')->Load($foreach_b->{'fit1'})->{'_name'}.' '.R('DJEM')->Load($foreach_b->{'fit2'})->{'_name'};   
        } else {
         echo $foreach_b->{'_name'}.' '.R('DJEM')->Load($foreach_b->{'fit2'})->{'_name'};   
        }
          ?></a></td>
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
       
     <td><a id="<?php echo $foreach_b->{'_id'}; ?>" class="delete_fav">Удалить из избранного</a></td>
             
         </tr>
         
         <?php }
;
unset($foreach__b);

if ($foreach_b__total == 0) {

?>
         
         <style>
/*для двухуровневой системы!*/
#right-container{
    width:828px;
    

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
 $('#izb2').click(function() {
    
          $.post(
  "/system/djemscript/Zagruzka-v-izbrannom.php",
  {
    namel2:$('#namel2').val(),
    name2: $('#log2').val(),
    pas2: $('#pas2').val(),
    
  },
  onAjaxSuccess
);
 
function onAjaxSuccess(data)
{
  // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
  $.cookie('add_fav', data);
  $('#meghide2').hide();
  document.location.reload(); 
}
           
});




  $('#izb').click(function() {
    
          $.post(
  "/system/djemscript/Sohranenie.php",
  {
    namel:$('#namel').val(),
    name: $('#log').val(),
    pas: $('#pas').val(),
    email: $('#email').val()
  },
  onAjaxSuccess
);
 
function onAjaxSuccess(data)
{
    alert('Ваша заявка успешно сохранена');
    $('#meghide').hide();
    $('#meghide').find('input').val("");
  // Здесь мы получаем данные, отправленные сервером и выводим их на экран.
  
}
           
});

  $('.in_save').click(function() {   
           $('#meghide2').show(); 
           $('#meghide').hide();         
});


  $('.save').click(function() {
    $('#meghide2').hide(); 
           $('#meghide').show(); 
           
});






$('.add_fav').click(function() {
    
           $.cookie("add_fav", $.cookie("add_fav")+','+$(this).attr('id'), { expires : 100,path    : '/'  });
           alert('Добавленно в избранное');   
           
});

$('.delete_fav').click(function() {
    str = $.cookie("add_fav");

           $.cookie("add_fav",str.replace(','+$(this).attr('id'),""), { expires : 100,path    : '/'  });
           alert('Удаленно из избранного');  
           location.reload(); 
});

$('.delete_all').click(function() {

           $.cookie("add_fav",null, { expires : 100,path    : '/'  });
           alert('Все удаленно из избранного');  
           location.reload(); 
});

$('.org').click(function(){ 
    $('.tips').hide();
    $(this).parent().find('.tips').css({'top':$(window).scrollTop()-100}).show();
    
   

            });

            $('.cls').click(function(){ 
    $(this).parent().hide();
            });
            
            
          


$(function() {
        $( ".tips" ).draggable({ handle: ".move_area" });
        
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