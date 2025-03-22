<?php
	 		if(!empty($_GET['id'])) {
	 			require_once('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
					$banner=$dj->load($_GET['id']);
					
						if($banner) {
							
							
							// просмотры по дням
								if (date("j",$banner->click_date) == date("j")) {
									$day= explode("\r\n",$banner->click_day);
									$banner->click_day_count+=1;
									$day[count($day) - 2]=date("d.m")." - Переходов:".$banner->click_day_count;
									$banner->click_day=implode("\r\n",$day); 
							    } else {
							   		 $banner->click_day_count=1;
									 $banner->click_day.=date("d.m")." - Переходов:1\r\n";
								}
								
								// просмотров в этом месяце 
								if (date("n",$banner->click_date) == date("n")) {
									$banner->click_month+=1;  
							    } else {
									 $banner->click_month=1;
								}	 
												                           
							$banner->click_date=time();
			 				// суммарное количество просмотров                               
			 				$banner->click_all+=1;  
			 				$banner->Store();
			 				header('Location: http://'.$_GET['url']);
		 				} 
	 				
	 				
	 		}