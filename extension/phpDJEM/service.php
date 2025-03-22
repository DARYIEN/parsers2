<?php				
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/config.php');	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/DJEMAuth.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/document8102365.php');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/8102338/document8102340.phtml');
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/8102338/document8102339.phtml');	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/functions/document8186617.phtml');	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/functions/Funkcii-dlya-raboty-s-prajs-listami-i-cenami.php');	
	require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/functions/document8222827.phtml');	
	
	
	$djemAuth = new DJEMAuth($cfg['sqlHost'], $cfg['sqlBase'], $cfg['sqlUser'], $cfg['sqlPassword']);		
	$http = new HTTP();
	$action = $http->Get('f');   // Необходимое действие мы передаем в cgi-параметре f		
		
	switch ($action) {  
		
		case 'login': 			
			if($djemAuth->Login(trim($_REQUEST['login']), md5($_REQUEST['password'])) > 0) {
				$result['redirect'] = 'http://metal100.ru' . urldecode($_REQUEST['redirect']);
				$result['status'] = 1;
			} else {
				$result['message'] = 'Неправильные логин и/или пароль.';
				$result['status'] = 0;
			}
			print json_encode($result);
			exit();
		break;	
		
		case 'fastlogin': 					
			$query = R('DJEM')->GetForeach();
						
			$login = $query->Path('main.users.$')->Where('password="' . $_REQUEST['code'] . '"')->Fetch()->_name;		
			$password = $_REQUEST['code'];
						
			$djemAuth->Login($login, $password);
			header('Location: http://metal100.ru');
			exit();
		break;	
		
		case 'user_register':			
			if($_REQUEST['username'] && $_REQUEST['email'] && $_REQUEST['password'] && $_REQUEST['city']) {
				if(!checkUser($_REQUEST)) {
					$userId = createUser($_REQUEST);
					if($userId > 0) {
						$result['message'] = 'Вы успешно зарегистрировались на сайте, реквизиты для входа на сайт были отправлены по указанному Вами адресу электронной почты.';
						$result['status'] = 1;	
					} else {
						$result['message'] = 'При регистрации произошла ошибка, попробуйте повторить операцию еще раз.';
						$result['status'] = 0;
					}
				} else {
					$result['message'] = 'Вы уже зарегистрированы в системе, пожалуйста авторизуйтесь на сайте.';
					$result['status'] = 0;
				}	
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}
			print json_encode($result);
			exit();
		break;
		
		//Редактирование персональных данных
		case 'edit_personal_info':
			$userId = $djemAuth->isAuthed();
			$user = R('DJEM')->Load($userId);
			
			if($_REQUEST['username'] && $_REQUEST['email'] && $_REQUEST['city'] && $_REQUEST['phone']) {
		        $user->persona = $_REQUEST['username'];
		        $user->_link1 = $_REQUEST['city'];
		        $user->email = $_REQUEST['email'];	
		        $user->url = preg_replace('#^https?://#', '', $_REQUEST['url']);
		        $user->telefon = $_REQUEST['phone'];
		        $user->legalEntity = $_REQUEST['legalEntity'];		        
		        //$user->name_org = $_REQUEST['org'];		        
		        //$user->url = $_REQUEST['url'];
		        if(!empty($_REQUEST['password']) && $_REQUEST['password'] == $_REQUEST['confirm_password']) {
		        	if(md5($_REQUEST['password']) != $user->password) {
		        		$user->password = md5($_REQUEST['password']);
		        	}
		        }
				$user->Store();	
				R('DJEMServer')->PublishDocument($userId);  
				
				$result['message'] = 'Новые данные успешно сохранены.';
				$result['status'] = 1;
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}		
			print json_encode($result);		
			exit();
		break;
		
		// Редактирование данных компании
		case 'edit_company_info':
			$userId = $djemAuth->isAuthed();
			$user = R('DJEM')->Load($userId);		
			
			if($_REQUEST['org'] && $_REQUEST['companyAddress']) {	
				$user->name_org = $_REQUEST['org'];
		        $user->companyAddress = $_REQUEST['companyAddress'];
		        $user->warehouseAddress = $_REQUEST['warehouseAddress'];		        
		        $user->companyDescription = $_REQUEST['companyDescription'];
		        $user->url = $_REQUEST['url'];		        		        		        
				$user->Store();	
				R('DJEMServer')->PublishDocument($userId);  
								
				$result['message'] = 'Новые данные успешно сохранены.';
				$result['status'] = 1; 
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}						
			
			print json_encode($result);		
			exit();
		break;
		
		// Восстановление пароля
		case 'remind_password':
			if($_REQUEST['email']) {
				$user = R('DJEM')->GetForeach()->Path('main.users.$')->Where('_name = "' . $_REQUEST['email'] . '"');
				if($user->Size() > 0) {
					$password = createNewPassword($user->Fetch());
					$data['user'] = $user;
					$data['password'] = $password;
					sendEmail('sendNewPasswordToUser', $data);
					$result['message'] = 'Новый пароль был выслан Вам по указанной электронной почте.';
					$result['status'] = 1;
				} else {
					$result['message'] = 'Пользователь с адресом ' . $_REQUEST['email'] . ' не зарегистрирован в системе.';
					$result['status'] = 0;				
				}				
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}
			print json_encode($result);
			exit();
		break;				
				
		//Создание объявления
		case 'create_ad':
			if($_REQUEST['title'] && $_REQUEST['username'] && $_REQUEST['city'] && $_REQUEST['email'] && $_REQUEST['phone'] && $_REQUEST['org'] && $_REQUEST['type'] && $_REQUEST['content'] && $_REQUEST['categories']) {
				//Если пароль введен, то создаем объявление и авторизуем пользоваетеля				
				if($_REQUEST['password']) {
					$userId = $djemAuth->Login(trim($_REQUEST['email']), md5($_REQUEST['password']));
					$login = $userId > 0;					
					if($login) {
						$_REQUEST['userId'] = $userId;
						$result = createAd($_REQUEST);
						$result['message'] .= ' Вы авторизованы на сайте.';
					} else {
						$result['message'] = 'Неправильные логин и/или пароль! <a href="/cabinet/remindPassword/" target="_blank">Забыли пароль?</a>';
						$result['status'] = 0;
					}				
				//В противном случае создаем объявление и пользователя
				} else {					
					if($djemAuth->isAuthed() <= 0 && !checkUser($_REQUEST)) {
						$userId = createUser($_REQUEST);
						if($userId > 0) {
							$_REQUEST['userId'] = $userId;
						}
					} else {
						$_REQUEST['userId'] = $djemAuth->isAuthed();
					}	
					$result = createAd($_REQUEST);
				}								
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}
			print json_encode($result);
			exit();
		break;
	
		case 'logoff': 
			$djemAuth->Logoff();
			session_destroy();
			if ($_REQUEST['redirect']) {
				header('Location: http://metal100.ru' . urldecode($_REQUEST['redirect']));
			} else {
				header('Location: /');
			}
			exit();
			
		break;	
		
		
		
		//Подписка на рассылки (эксперементальная)
		case 'subscribe':		
			if(sizeof($_REQUEST['categories']) > 0 && sizeof($_REQUEST['subscribtionType']) > 0) {
				$userId = $djemAuth->isAuthed();
				
				if($userId > 0) {
					$user = R('DJEM')->Load($userId);
				} elseif(checkUser($_REQUEST)) {
					$user = R('DJEM')->GetForeach()->Path('main.users.$')->Where('_name = "' . $_REQUEST['email'] . '"')->Fetch();					
				} elseif($_REQUEST['username'] && $_REQUEST['email'] && $_REQUEST['phone']) {					
					$userId = createUser($_REQUEST);
					$user = R('DJEM')->Load($userId);
				} else {
					$result['message'] = 'Заполните все необходимые поля формы.';
					$result['status'] = 0;
				}
				
				if(!empty($user)) {
					if(in_array('ads', $_REQUEST['subscribtionType'])) {
						$user->adsSubscribtion = "on";
						if(sizeof($_REQUEST['type']) == 0) {
							$user->adTypes = implode(",", array('prodam','kuplu','pred_uslugi','other'));
						} else {
							$user->adTypes = implode(",", $_REQUEST['type']);
						}
					} else {
						$user->adsSubscribtion = "";
					}					
					$user->ordersSubscribtion = in_array('order', $_REQUEST['subscribtionType']) ? "on" : "";
					$user->pricelistsSubscribtion = in_array('pricelists', $_REQUEST['subscribtionType']) ? "on" : "";
					$user->categories = $_REQUEST['categories'];
					$user->cities = $_REQUEST['cities'];
					$user->Store(); 					
					$result['message'] = 'Подписка успешно проведена.';
					$result['status'] = 1;
				}										
			} else {
				$result['message'] = 'Вы не выбрали позиции или рассылки.';
				$result['status'] = 0;
			}
			print json_encode($result);
			exit();
		break;			
		
		//Обработчик заявки
		case 'order':
			if($_REQUEST['username'] && $_REQUEST['email'] && $_REQUEST['city'] && $_REQUEST['cities'] && $_REQUEST['order'] > 0) {
				$_REQUEST['order'] = json_encode($_REQUEST['order']);
				$user = R('DJEM')->GetForeach()->Path('main.users.$')->Where('_name = "' . $_REQUEST['email'] . '"')->Limit(1);
								
				//Если пользователь зарегистрирован в системе				
				if($user->Size() > 0) {
					$userId = $user->Fetch()->__get('_id');
					$login = $userId > 0;					
					if($login) {
						$_REQUEST['userId'] = $userId;
						$result = createOrder($_REQUEST);
					} else {
						$result['message'] = 'Неправильные логин и/или пароль!';
						$result['status'] = 0;
					}				
				//В противном случае создаем заявку и пользователя
				} else {					
					if($djemAuth->isAuthed() <= 0 && !checkUser($_REQUEST)) {
						$userId = createUser($_REQUEST);
						if($userId > 0) {
							$_REQUEST['userId'] = $userId;
						}
					} else {
						$_REQUEST['userId'] = $djemAuth->isAuthed();
					}	
					$result = createOrder($_REQUEST);
				}	
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}
			//Если заявка создана, отправляем письмо-подтверждение клиенту и рассылкаем заявки подписчикам
			if($result['status'] == 1) {				
				$data = $_REQUEST;
				$data['id']	= $result['id'];								
				
				sendEmail('orderOnModeration', $data);								
				sendEmail('orderNeedToBeVerified', $data);
								
					
			}
			print json_encode($result);	
			exit();
		break;
		
		case 'add_pricelist':			
			$uploaddir = "/var/www/sergey/data/www/plastom.ujob.su/download/pricelists/";
						
			if($_FILES['file']['name'] && $_REQUEST['username'] && $_REQUEST['city'] && $_REQUEST['email'] && $_REQUEST['phone'] && $_REQUEST['org']) {
				if($djemAuth->isAuthed() > 0) {
					$userId = $djemAuth->isAuthed();
				} elseif($_REQUEST['password']) {
					$userId = $djemAuth->Login(trim($_REQUEST['email']), md5($_REQUEST['password']));
					if($userId <= 0) {
						$result['message'] = 'Введен неверный пароль.';
						$result['status'] = 0;
					}				
				} elseif($_REQUEST['username'] && $_REQUEST['city'] && $_REQUEST['email'] && $_REQUEST['phone'] && $_REQUEST['org']) {
					if(!checkUser($_REQUEST)) {
						$userId = createUser($_REQUEST);
					} else {
						$result['message'] = 'Вы уже зарегистрированы в системе, пожалуйста авторизуйтесь на сайте.';
						$result['status'] = 0;
					}
				} elseif($userId <= 0) {
					$result['message'] = 'Заполните все необходимые поля формы.';
					$result['status'] = 0;
				}
				
				if($userId > 0) {
					//Сохраняем файл и отпраляем письмо													 					 				 		
		 			if($_FILES['file']['error'] == 0) { 
		 				$filename = rand(0,1000) . str_replace(" ", "_", $_FILES['file']['name']);
						move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename);		
		 			}
			 					 							 		
			 		$_POST['filename'] = $filename;
			 		sendEmail('newPricelist', $_POST);
				}
				$result['message'] = 'Прайс-лист успешно загружен.';
				$result['status'] = 1;
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}
			print json_encode($result);
			exit();
		break;
		
		//Свободная заявка
		case 'freeFormOrder':
			$uploaddir = "/var/www/sergey/data/www/plastom.ujob.su/download/orders/";
		
			if($_REQUEST['username'] && $_REQUEST['email'] && $_REQUEST['phone'] && $_REQUEST['city'] && $_REQUEST['cities'] && $_REQUEST['order']) {
				$user = R('DJEM')->GetForeach()->Path('main.users.$')->Where('_name = "' . $_REQUEST['email'] . '"')->Limit(1);
								
				//Если пользователь зарегистрирован в системе				
				if($user->Size() > 0) {
					$userId = $user->Fetch()->__get('_id');
					if($userId > 0) {
						$_REQUEST['userId'] = $userId;
					}
				} else {
					if($djemAuth->isAuthed() <= 0 && !checkUser($_REQUEST)) {
						$userId = createUser($_REQUEST);									
						if($userId > 0) {
							$_REQUEST['userId'] = $userId;
						}
					} else {
						$_REQUEST['userId'] = $djemAuth->isAuthed();
					}
				}
				
				//Сохраняем файл и отпраляем письмо													 					 				 		
	 			if($_FILES['file']['error'] == 0) { 
	 				$filename = rand(0,1000) . str_replace(" ", "_", $_FILES['file']['name']);
					move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir . $filename);		
	 			}
	 			$_REQUEST['filename'] = $filename;
	 			
				$result['message'] = 'Заявка отправлена на модерацию. Спасибо!';
				$result['status'] = 1;								
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}
			if($result['status'] == 1) {				
				$data = $_REQUEST;				
				sendEmail('sendFreeFormOrderToModerator', $data);
			}	
			
			print json_encode($result);	
			exit();
		break;
		
		//Обработчик ответа поставщика на заявку заказчика
		case 'offer':			
			$offerCount = 0;
			foreach($_REQUEST['offer'] as $offerItem) {
				if(!empty($offerItem['price'])) $offerCount++;
			}
			//Если предложение содержит цены
			if($offerCount > 0) {
				$_REQUEST['offer'] = json_encode($_REQUEST['offer']);
				$userId = $djemAuth->isAuthed();
								
				if($_REQUEST['password']) {
					$userId = $djemAuth->Login(trim($_REQUEST['email']), md5($_REQUEST['password']));
					if($userId <= 0) {
						$result['message'] = 'Введен неверный пароль.';
						$result['status'] = 0;
					}				
				} elseif($_REQUEST['username'] && $_REQUEST['town'] && $_REQUEST['email'] && $_REQUEST['phone'] && $_REQUEST['org']) {
					if(!checkUser($_REQUEST)) {
						$userId = createUser($_REQUEST);
					} else {
						$result['message'] = 'Вы уже зарегистрированы в системе, пожалуйста авторизуйтесь на сайте.';
						$result['status'] = 0;
					}
				} elseif($userId <= 0) {				
					$result['message'] = 'Заполните все необходимые поля формы.';
					$result['status'] = 0;
				}
				
				if($userId > 0) {
					$userOffers = R('DJEM')->GetForeach()->Path('main.offers.$')->Where('_link1 =  ' . $_REQUEST['orderId'] . ' && _link2 = ' . $userId)->Size();
					
					if($userOffers > 0) {
						$result['message'] = 'Ответ на заявку уже отправлен.';
						$result['status'] = 1;
					} else {
						$_REQUEST['userId'] = $userId;
						$result = createOffer($_REQUEST);
					}					
				}
			} else {
				$result['message'] = 'Введите стоимость хотя бы одной позиции.';
				$result['status'] = 0;
			}
			//Если ответ на заявку создан, отправляем письмо
			if($result['status'] == 1) {				
				sendEmail($action, $_REQUEST);				
			}	
			print json_encode($result);
			exit();
		break;
		
		case 'add_price':
			if($_REQUEST['categoryId'] && $_REQUEST['length'] && $_REQUEST['steel'] && ($_REQUEST['wholesale'] || $_REQUEST['retail']) && (($_REQUEST['type'] && $_REQUEST['size']) || $_REQUEST['size'])) {			
				$result = createPriceItem($_REQUEST);
			} else {
				$result['message'] = 'Заполните все необходимые поля формы.';
				$result['status'] = 0;
			}
			print json_encode($result);
			exit();
		break;
		
		default : 
			print "Invalid action";
		break;	

	}

?>