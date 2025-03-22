<?php
	require_once('/var/www/sergey/data/www/plastom.ujob.su//system/php/config.php'); 		
?>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" href="/style.css" type="text/css"/>
	</head>	
	<body class="djemPanelView">
		<div class="orderPage">
			<div class="right">
				<?php echo "<a href='DJEM://" . $_SERVER['HTTP_HOST'] . "/id=" . $_GET['order'] . "'>Редактировать</a>";?>	
			</div>
			<h2 style="margin-bottom: 0 !important;">Состав заявки:</h2>
			<?php
				$categories = R('DJEM')->GetForeach()->Path('main.metal.shop.*')->Where('_type = 220')->ToArray();
				$order = R('DJEM')->Load($_GET['order']);			
			?>				
			<div class="orderRow">
				<?php
					$orderItems = json_decode($order->order);
					if($order->order != '') {
				?>				
				<div style="width: 75%;">					
					<table class="orderTable">
						<thead>
							<tr>
								<th colspan="2">Наименование</th>													
								<th>Размер</th>
								<th>Кол-во</th>
								<th>Длина</th>
								<th>Сталь</th>
								<th>Цена (не больше)</th>
								<th>Примечание</th>
							</tr>
						</thead>
						<tbody style="font-size: 14px;">
							<?php 
								$orderItemIndex = 0;
								foreach($orderItems as $orderItem) {
							?>
								<tr>
									<td><?php echo ++$orderItemIndex; ?></td>
									<td><strong><?php echo $categories[$orderItem->id]; ?></strong> <?php echo $orderItem->type ? $orderItem->type : ''; ?></td>														
									<td class="right"><?php echo $orderItem->size ? $orderItem->size : '-'; ?></td>
									<td class="right nowrap"><?php echo $orderItem->quantity ? $orderItem->quantity . ' ' . $orderItem->unit : '-'; ?></td>
									<td class="right"><?php echo $orderItem->length ? $orderItem->length : '-'; ?></td>
									<td class="right"><?php echo $orderItem->steelType ? $orderItem->steelType : '-'; ?></td>
									<td class="right"><?php echo $orderItem->maxPrice ? $orderItem->maxPrice : '-'; ?></td>
									<td><?php echo $orderItem->extra ? $orderItem->extra : '-'; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
					
					<?php if($order->comment) {?>
					<div style="display: inline-block; width: 50%; margin-bottom: 20px;">	
						<div style="color: #999; font-size: 12px;">Комментарий к заявке:</div>				
						<p><?php echo $order->comment; ?></p>
					</div>
					<?php }?>						
				</div>						
				
				<?php
					$offers = R('DJEM')->GetForeach()->Path('main.offers.$')->Where('_link1 = "?"',$order->_id);
					echo '<h2>Ответы (' . $offers->Size() . '):</h2>';
					echo '<div>';
					
					if($offers->Size() > 0) {
						echo '<ol style="margin: 0 0 0 25px;">';			
						foreach($offers as $offer) {
							$seller = R('DJEM')->Load($offer->_link2);							
							echo '<li class="margin-bottom-20"><strong>';
							echo $seller->name_org ? $seller->name_org . ' (' . $seller->persona . ')' : $seller->persona;
							echo '</strong> <span class="grey size-12">телефон: ' . $offer->phone . ', e-mail: ' . $seller->email . '</span>';
							if($offer->comment)
								echo '<div style="margin-top: 3px;"><em>' . $offer->comment . '</em></div>';
							echo '</li>';
						}
						echo '</ol>';					
					} else {
						echo 'Ответов на заявку пока не поступало';
					}
					echo '</div>';
				?>				
						
				<?php
					} else {
						echo 'Выберите заявку для просмотра';
					}	
				?>
			</div>												
		</div>							

	
	</body>
</html>