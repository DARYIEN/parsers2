<?php
require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php'); 
?>




-11---<?php echo htmlspecialchars((isset($_REQUEST['check1'])?$_REQUEST['check1']:'')); ?>--11--



<table class="bulletin" id="table-bulletin">
<th style="text-align: left;">
Кат-я
</th>
<th style="text-align: left;">
Заголовок объявления
</th>
<th style="text-align: left;">
Наименование организации
</th>
<th style="text-align: left;">
Город
</th>
<th style="text-align: left;">
Время
</th>

<?php $foreach__b = new DJEMForeach(R('DJEM'));
$foreach__b->Path('main.metal.Advertisement.$');
$foreach__b->Where('_type!=\'36\' && _name!=\'\' && opublic==\'on\' && prodam!=\'on\'');
$foreach__b->Sort('-_last_publish_time');
$foreach__b->Fields('_last_publish_time','_name','_type','city','name_of_organization','size','textob');
$foreach_b__total = $foreach__b->Size();
$foreach_b__count = 0; foreach ($foreach__b as $foreach_b) { ++$foreach_b__count; ?>

<tr>
<td>
--
</td>
<td>
<?php if ($foreach_b->{'_type'} == 4024466) { ?><?php echo $foreach_b->{'_name'}; ?> 
<?php echo $foreach_b->{'size'}; ?>
<?php } ?><?php if ($foreach_b->{'_type'} == 461532) { ?><?php echo R('DJEMScript')->CutSpace($foreach_b->{'textob'},60); ?> 
<?php } ?></td><td>
<?php echo $foreach_b->{'name_of_organization'}; ?>
</td><td>
<?php echo R('DJEM')->Load($foreach_b->{'city'})->{'_name'}; ?></td><td>
<?php echo R('DJEMScript')->Time('%d.%mm.%y %h:%m', $foreach_b->{'_last_publish_time'}); ?>
</td></tr>

<?php }; unset($foreach__b); ?>




<?php $foreach__f = new DJEMForeach(R('DJEM'));
$foreach__f->Path('main.metal.price.$');
$foreach__f->Where('advertisement==\'on\'  && city=='.'?',(isset($_REQUEST['par2'])?$_REQUEST['par2']:''));
$foreach__f->Sort('-_create_time');
$foreach__f->Limit(',50');
$foreach__f->Fields('Ghost','_create_time','_name','city','company','fit1','fit2','number','retail','steel','wholesale');
$foreach_f__total = $foreach__f->Size();
$foreach_f__count = 0; foreach ($foreach__f as $foreach_f) { ++$foreach_f__count; ?>


<tr>

<td>
К
</td>

<td>
<?php echo R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace($foreach_f->{'_name'},'Лист гк','Лист горячекатаный'),'Полоса нж','Полоса нержавеющая'),'арматура нж','арматура нержавеющая'),'нж','нержавеющий'),'Арматура','Стальная арматура Класса'),'Балка (двутавр)',' Балка двутавровая стальная №'),'Швеллер','Швеллер стальной'),'РП','равнополочный'),'НП',' не равнополочный'),'Тавр','стальной'),'чечев','чечевичный'),'Сталь','Листовая сталь'),'Оцинкованный','Оцинкованный стальной'),'Профиль','Профиль металлический замкнутый'),'Листы','Листы Просечно Вытяжные'),'ВГП','ВГП (водогазопровдные)'),'оцинк','оцинкованные'),'гк','горячекатанные'),'хк','холоднокатанные'),'ППУ','в изоляции ППУ'),'прочие',' '),'эс','электросварные'),'гофр','гофрированные'),'Запорная арматура нж',''),'бш','бесшовные'),'Труб. арматура бу',''); ?> 
<?php if ($foreach_f->{'fit1'} != 'non') { ?>
<?php echo R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEMScript')->Replace(R('DJEM')->Load($foreach_f->{'fit1'})->{'_name'},'квадр','квадратные'),'прямоуг','прямоугольные'),'прямоуг','прямоугольные'),'фланцы','фланцы стальные'),'тройники','тройники стальные'),'кран шаровый','кран шаровый стальной'),'фильтр','фильтр  стальной'),'дисковые затворы','дисковые затворы  стальные'),'муфта','муфта стальная'),'переход','переход стальной'),'заглушка','заглушки стальные'),'вл','высоколегированная'),'х/к',''),'оцинк','оцинкованная'),'бгр нам','большегрузочной намотки'),'оцинк пров бгр','оцинкованной проволки большегрузочной намотки'),'лист',''),'уголок РП','равнополочный'),'уголок НП',' не равнополочный'),'РП','равнополочный'),'НП',' не равнополочный'); ?><?php } ?>
 Размер
<?php echo R('DJEM')->Load($foreach_f->{'fit2'})->{'_name'}; ?><?php if ($foreach_f->{'steel'}) { ?> Марка стали
<?php echo $foreach_f->{'steel'}; ?>
<?php } ?><?php if ($foreach_f->{'Ghost'}) { ?> <?php echo $foreach_f->{'Ghost'}; ?>
<?php } ?><?php if ($foreach_f->{'number'}) { ?> Кол
<?php echo $foreach_f->{'number'}; ?>
<?php } ?><ins>

<?php if ($foreach_f->{'wholesale'} && $foreach_f->{'wholesale'} < 1000000 && $foreach_f->{'wholesale'} > 0 || $foreach_f->{'retail'} && $foreach_f->{'retail'} < 1000000 && $foreach_f->{'retail'} > 0) { ?> Цена:  
                                       
<?php } ?>

<?php if ($foreach_f->{'wholesale'} && $foreach_f->{'wholesale'} < 1000000 && $foreach_f->{'wholesale'} > 0) { ?>от   
 <?php echo $foreach_f->{'wholesale'}; ?>                                             
<?php } ?><?php if ($foreach_f->{'retail'} && $foreach_f->{'retail'} < 1000000 && $foreach_f->{'retail'} > 0) { ?>до
<?php echo $foreach_f->{'retail'}; ?>
<?php } ?></ins>
</td><td>
<?php echo R('DJEM')->Load($foreach_f->{'company'})->{'_name'}; ?></td><td>
<?php echo R('DJEM')->Load($foreach_f->{'city'})->{'_name'}; ?></td><td>
<?php echo R('DJEMScript')->Time('%d.%mm.%y %h:%m', $foreach_f->{'_create_time'}); ?>
</td></tr>





<?php }; unset($foreach__f); ?>

<?php $foreach__y = new DJEMForeach(R('DJEM'));
$foreach__y->Path('main.metal.price.$');
$foreach__y->Where('advertisement==\'on\' && city=='.'?',(isset($_REQUEST['par2'])?$_REQUEST['par2']:''));
$foreach__y->Sort('-_create_time');
$foreach__y->Fields();
$foreach_y__total = $foreach__y->Size();
$foreach_y__count = 0; foreach ($foreach__y as $foreach_y) { ++$foreach_y__count; ?>

 <?php }; unset($foreach__y); ?>
<?php $var['total'] = ($foreach_y__total/50); ?>



<tr >
<td colspan="5" style="border: 1px solid rgb(237, 239, 240);">
<ul>
 <?php $loop_i_step=(int)(1);for($loop_i=(int)(1);$loop_i<=$var['total'];$loop_i+=$loop_i_step) {?> 

  <li style="float:left;margin-left:5px"><a href="/system/php/modules/document7560193.php?page=<?php echo $loop_i;?>"><?php echo $loop_i;?></a></li>

<?php } ?> 
</ul>
</td>
</tr>



