<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 
?>


	<p>Выберите тип:</p> 


	<ul style="float:left" class="formBox">

<select id="mySelectId" name="mySelect" class="w1" style="width: 168px;">

<?php $foreach__n = new DJEMForeach(R('DJEM'));
$foreach__n->Path('main.metal.shop.*');
$foreach__n->Where('_id in \'835,982,1003,980,376934,4028211,979,55418,977,976,33069,975,974,973,971,966,3846593,3846564,855,856,858,859,457818,457820,861,4062591,3843044,3843045,13427,3843046,13430,13429,4028207,55425,55486,371506,7538875,491911,55363,55366,55367,55494,55495,55497,457834,457835,457836,55563,55564,55565,55567,457830,55568,55569,55570,55744,55747,55792,55793,55794,55805,55806,55807,55821,55822\' ');
$foreach__n->Sort('_name');
$foreach__n->Fields('_id','_name','_parent_id','_type','level_sis');
$foreach_n__total = $foreach__n->Size();
$foreach_n__count = 0; foreach ($foreach__n as $foreach_n) { ++$foreach_n__count; ?>	
				

<?php if ($foreach_n->{'level_sis'} == 'on' || $foreach_n->{'_type'} == 221) { ?> <option <?php if ($foreach_n->{'_id'} == (isset($_REQUEST['par2'])?$_REQUEST['par2']:'')) { ?>selected<?php } ?> value="<?php echo $foreach_n->{'_id'}; ?>"><?php if ($foreach_n->{'_type'} == 221) { ?><?php echo R('DJEM')->Load($foreach_n->{'_parent_id'})->{'_name'}; ?><?php } ?> <?php echo $foreach_n->{'_name'}; ?></option><?php } ?> 
	<?php }; unset($foreach__n); ?>
</select>	

<script>

$('#mySelectId option').click(function(){
   

   
$("#calc_w").val("тонны");
$("#calc_l").val("метры");

  $.post(
  "/system/php/modules/document7546807.php",
  {
    par1: postVar=$(this).find('a').html(),
    par2: $(this).attr('value'),
    
  },
  onAjaxSuccess
);

function onAjaxSuccess(data)
{

$("#odintakoy").html(data);


}	


});

</script>


	</ul>
	
	
	
		<p>Выберите размер:</p>
		<ul class="vidy" id="result" >
<select id="asd" style="width: 168px;">
	<?php $foreach__n = new DJEMForeach(R('DJEM'));
$foreach__n->Path(R('DJEM')->Load((isset($_REQUEST['par2'])?$_REQUEST['par2']:''))->{'_path'}.'.*');
$foreach__n->Where('factor!=\'\'');
$foreach__n->Sort('#_name');
$foreach__n->Fields('_id','_name','factor');
$foreach_n__total = $foreach__n->Size();
$foreach_n__count = 0; foreach ($foreach__n as $foreach_n) { ++$foreach_n__count; ?>	
	 	<option <?php if ((isset($_REQUEST['par4'])?$_REQUEST['par4']:'') == $foreach_n->{'_id'}) { ?>selected<?php } ?> value="<?php echo R('DJEMScript')->Replace($foreach_n->{'factor'},',','.'); ?>" id="<?php echo $foreach_n->{'_id'}; ?>"> <?php echo $foreach_n->{'_name'}; ?></option>

	<?php }; unset($foreach__n); ?>	</select>
	</ul>

















<script>

$('#asd option').click(function(){




b=$(this).attr('value');
$("#calc_stal_koef").val(b);
if('<?php echo htmlspecialchars((isset($_REQUEST['par5'])?$_REQUEST['par5']:'')); ?>'!='тонны'){
$('#calc_w').val(<?php echo htmlspecialchars((isset($_REQUEST['par5'])?$_REQUEST['par5']:'')); ?>);
}



calc(1,'');


});
</script>




<div class="calc_container" style="position: relative; top: 5px;">



	<div class="input_wrap ">
    <div class="left_bord_wrap">
    </div>
    
    <input id="calc_l" class="calc_input calc_grey inp_text" onkeyup="calc(0,'')" onfocus="calc_grey(this.id,0,'')" onblur="calc_grey(this.id,0,'')" value="метры" />
    <div class="right_bord_wrap">
    </div></div>
    <div class="input_wrap">
    <div class="left_bord_wrap">
    </div>
    
        <input id="calc_w" class="calc_input calc_grey inp_text" onkeyup="calc(1,'')" onfocus="calc_grey(this.id,0,'')" onblur="calc_grey(this.id,0,'')" value="тонны" />
    
        
    <div class="right_bord_wrap">
    </div></div>
 
    <input type="hidden" id="calc_koef" value="1" /> 
    <input type="hidden" id="calc_stal_koef"  value="<?php echo htmlspecialchars((isset($_REQUEST['par3'])?$_REQUEST['par3']:'')); ?>" />
    <input type="hidden" id="calc_w_label" value="тонны" />
    <input type="hidden" id="calc_l_label" value="метры" />
    <input type="hidden" id="calc_last_w_to_l" value="" />
  
    </div>	








	
	<?php $foreach__ww = new DJEMForeach(R('DJEM'));
$foreach__ww->Path('main.metal.price.*');
$foreach__ww->Where('fit2=='.'?'.' && retail!=\'non\' && retail!=99999999999',(isset($_REQUEST['par4'])?$_REQUEST['par4']:''));
$foreach__ww->Sort('#retail');
$foreach__ww->Limit(1);
$foreach__ww->Fields('retail');
$foreach_ww__total = $foreach__ww->Size();
$foreach_ww__count = 0; foreach ($foreach__ww as $foreach_ww) { ++$foreach_ww__count; ?>	
-------<?php echo htmlspecialchars((isset($_REQUEST['par5'])?$_REQUEST['par5']:'')); ?>	--------
	
<?php if ((isset($_REQUEST['par5'])?$_REQUEST['par5']:'') != 'тонны' && (isset($_REQUEST['par5'])?$_REQUEST['par5']:'')) { ?>  <?php $var['arka'] = ($foreach_ww->{'retail'}*(isset($_REQUEST['par5'])?$_REQUEST['par5']:'')); ?> <?php echo $var['arka']; ?>
<?php } ?>
нет
	<?php }; unset($foreach__ww); ?>




<script>

$('#asd option').click(function(){



  $.post(
  "/system/php/modules/document7546807.php",
  {
    par2: $("#mySelectId option:selected").attr('value'),
    par3: $(this).attr('value'),
    par4: $(this).attr('id'),
  },
  onAjaxSuccess
);

function onAjaxSuccess(data)
{

$("#odintakoy").html(data);


}	


});

</script>	







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
 $('#calc_l'+kida).val(l+" "+"M2");
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
