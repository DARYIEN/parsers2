<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 

?>

<?php $foreach__v = new DJEMForeach(R('DJEM'));
$foreach__v->Path((isset($_REQUEST['par2'])?$_REQUEST['par2']:'').'.$');
$foreach__v->Sort('_sort');
$foreach__v->Limit(1);
$foreach__v->Fields();
$foreach_v__total = $foreach__v->Size();
$foreach_v__count = 0; foreach ($foreach__v as $foreach_v) { ++$foreach_v__count; ?>
<tr class="delete new selectos"><td colspan=2>
<form onsubmit="return false;">
<?php if (R('DJEM')->Load((isset($_REQUEST['par2'])?$_REQUEST['par2']:''))->{'_type'} == 221 || R('DJEM')->Load((isset($_REQUEST['par2'])?$_REQUEST['par2']:''))->{'level_sis'} == 'on') { ?>Выберите размер

<input id="char_avto"  href="javascript:void(0);" value="Введите размер" onfocus="if (value == 'Введите размер') {value =''}" path="<?php echo htmlspecialchars((isset($_REQUEST['par2'])?$_REQUEST['par2']:'')); ?>" city="<?php echo $city; ?>" type="text" ad=""  onclick="d($(this))" class="slct2 sin" style="background:url('/image/new_arrow2.png') no-repeat; border: medium none;"  />

<?php } else { ?>Выберите <?php if (R('DJEM')->Load((isset($_REQUEST['par2'])?$_REQUEST['par2']:''))->{'_type'} == 220) { ?>вид<?php } else { ?><?php if (R('DJEM')->Load((isset($_REQUEST['par2'])?$_REQUEST['par2']:''))->{'_type'} == 35) { ?> название<?php } ?>  <?php } ?><a href="javascript:void(0);" onclick="d($(this))" class="slct2" style="background:url('/image/new_arrow2.png') no-repeat;">Выберите:</a>

<?php } ?>	<ul class="drop" style="width:222px;">
		
		<?php $foreach__v = new DJEMForeach(R('DJEM'));
$foreach__v->Path((isset($_REQUEST['par2'])?$_REQUEST['par2']:'').'.$');
$foreach__v->Where('city has \''.'?'.'\' || _type!=36',$city);
$foreach__v->Sort('_name');
$foreach__v->Fields('_level','_name','_path','_type');
$foreach_v__total = $foreach__v->Size();
$foreach_v__count = 0; foreach ($foreach__v as $foreach_v) { ++$foreach_v__count; ?>
		
		<li  onclick=" l($(this)); $('.sin').val('<?php echo $foreach_v->{'_name'}; ?>');  "><a level="<?php echo $foreach_v->{'_level'}; ?>" value="<?php echo $foreach_v->{'_path'}; ?>" tip="<?php echo $foreach_v->{'_type'}; ?>" ><?php echo $foreach_v->{'_name'}; ?></a></li>
		  <?php }; unset($foreach__v); ?>		 <?php if ($foreach_v__total == 0) { ?>		 <li><a level="6" >Нет в наличие</a></li>
		 <?php } ?>		  
	
	</ul>
	<input type="hidden" id="select"  /></form>
	</td>
	</tr>


	<?php }; unset($foreach__v); ?>	<?php if ($foreach_v__total == 0) { ?>	
	
	

	


	
	
	

	<?php
	$coco = R('DJEM')->Load($_REQUEST["par2"])->_id;
	?>

	



	
	
	

	
	
	


  <script>




$('.click102').click(function(){

  alert('Ваше объявление добавлено');
$.post(
  "/tmp/create-listings.php",
  {
  //На всякий случай есть кука отражающая уровень селектов ,называеться selectlevel,передадим через ajax и замутим проверку (значение равно 3 или 4)
 //Будем брать значение скрытого инпута у селектов и у обычных инпутоы ниже селектов
    selectlevel: $.cookie("selectlevel"),
    param1: $('.glav_form tr.selectos').eq(1).find('input').attr('value'),  //Вывод значения 1 позиции
   // param2: $('.glav_form tr.selectos').eq(2).find('input').attr('value'),  //Вывод значения 2 позиции
    param3: $('.glav_form tr.selectos').eq(-1).find('input').attr('value'),	//и т.д пошло поехало....
   //   param4: $('.glav_form tr.selectos').eq(4).find('input').attr('value'),  
    
    
    
    param5: $('.glav_form tr').find('input.size').val(),    		//Размер
    param6: $('.glav_form tr').find('input.number').val(),		//Количество
    param7: $('.glav_form tr').find('input.steel').val(),			//Марка стали
    param8: $('.glav_form tr').find('input.wholesale').val(), 	//Цена опт
    param9: $('.glav_form tr').find('input.retail').val(),		//Цена розн		
    param10: $('.glav_form tr').find('input.Notes').val(),		//Примечания  
     param11: $('.glav_form tr').find('input.name_of_organization').val(),		//Название организации
    param12: $('.glav_form tr').find('input.Ghost').val()	//Гост
    
   	
 
 
  },
  onAjaxSuccess

);
function onAjaxSuccess(data){


}

});



</script> 
        	
    
	

	<?php } ?>	





