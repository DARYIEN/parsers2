<?php
	
    class Auth {
        private $djem;
        private $folderId = 148;
        public $error = "";

        function __construct($djem) {
            $this->djem = $djem;    
        }

        function Service($action) {
        	$authObj = R('DJEMAuth');
            switch ($action) {
				case 'login':
					$result = $authObj->Login(trim($_REQUEST['name']), md5(trim($_REQUEST['password'])), true);
					if ($result > 0) {
						print json_encode(array('result' => 'success'));
					} else {
						print json_encode(array('result' => 'error', 'reason' => $authObj->error));
					}					
				break;
				
				case 'logoff':
					$authObj->Logoff();
					print 'ok';
				break;
				
				case 'remindPassword':
					if($this->RemindPassword()) {
						print json_encode(array('result' => 'success'));
					} else {
						print json_encode(array('result' => 'error', 'reason' => $this->error));
					}
				break;
				
				case 'updateProfile': 					
					$result = $this->UpdateProfile(); 
        			if ($result) {
    					$result = $result + array('result' => 'success'); 
    					print json_encode($result);
        			} else {
        				$result = array('result' => 'failure', 'reason' => $this->error); 
        				if (count($this->errorArray)) $result += $this->errorArray;			
        				print json_encode($result);
        			}
        		break;			
        		
        		case 'register_invite': 
					$result = $this->RegisterByInvite(); 
        			if ($result) {
    					$result = $result + array('result' => 'success'); 
    					print json_encode($result);
        			} else {
        				$result = array('result' => 'failure', 'reason' => $this->error); 
        				if (count($this->errorArray)) $result += $this->errorArray;
        				print json_encode($result);
        			}
        		break;
        		
        		case 'register': 
					$result = $this->Register(); 
        			if ($result) {
    					$result = array_merge($result, array('result' => 'success')); 
    					print json_encode($result);
        			} else {
        				$result = array('result' => 'failure', 'reason' => $this->error); 
        				if (count($this->errorArray)) {
        					$result = array_merge($result, $this->errorArray);
        				} 				
        				print json_encode($result);
        			}
        		break;
			
				case 'updateEmail':
					$result = $this->UpdateEmail();
					if ($result) {
    					print json_encode(array('result' => 'success'));			
        			} else {
        				print json_encode(array('result' => 'failure', 'reason' => $this->error));
        			}
				
				break;

				case 'updatePassword':
					$result = $this->UpdatePassword();
					if ($result) {
    					print json_encode(array('result' => 'success'));				    			
        			} else {
        				print json_encode(array('result' => 'failure', 'reason' => $this->error));
        			}
				
				break;
			
				default: 
					return false;
				break;
			}
			
			return true;
        }    
        
        function RemindPassword() {
        	$fields = array();
        	$fields['_name'] = (isset($_REQUEST['name'])) 	? trim($_REQUEST['name']) 	: "";
        	$fields['email'] = (isset($_REQUEST['email'])) 	? trim($_REQUEST['email']) 	: "";
        	if($fields['_name'] != "" || $fields['email']) {
        		foreach($fields as $field => $value) {
        			if($value == "") {
        				unset($fields[$field]);
        			}
        		}
        		$users = $this->djem->GetForeach()->Path("main.users.$")->Where(implode(" == '?' OR", array_keys($fields)) . "== '?'", array_values($fields));
        		if($users->Size()) {
        			if($user = $users->Fetch()) {
        				$user->newPassword = substr(md5(time()), 0, 6);
        				$user->password = md5($user->newPassword);
        				$user->remind_password = "on";
        				$user->Store();
        				$server = new DJEMServer($this->djem);
        				$server->PublishDocument($user->_id);
        				return true;
        			}
        		} else {
        			$this->error = "Такого пользователя не существует.";
        		}
        	} else {
        		$this->error = "Введите логин или email.";
        	}
        	return false;
        }
        
        function ValidateProfile() {
        	
        					$firstname = strip_tags(trim($_REQUEST['firstname']));
				if (empty($firstname)) {
					$this->errorArray['firstname'] = 'Обязательное поле'; 
				}
							$lastname = strip_tags(trim($_REQUEST['lastname']));
				if (empty($lastname)) {
					$this->errorArray['lastname'] = 'Обязательное поле'; 
				}
			        }
        
        function UpdateProfile() {        	
        	$this->errorArray = array();
        	$authObj = R('DJEMAuth');
			if (!$authObj->_id) {
				$this->error = 'Необходима авторизация';
				return false;
			}
			$this->ValidateProfile();
			if (count($this->errorArray)) {
				$this->error = 'Не заполнены обязательные поля';
				return false;
			}
			$user = $this->djem->Load($authObj->_id);
			
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
							
										$user->text = (isset($_REQUEST['text']) ? strip_tags(trim($_REQUEST['text']),'<p><a>') : ""); 
			$user->Store();
			if(count($this->errorArray) > 0) {
				return false;
			}
			$server = new DJEMServer($this->djem);
			$server->PublishDocument($authObj->_id);
			return array('url' => $user->_url);
        }
     	
     	function ValidateRegistration() {
     		$name = strip_tags(trim($_REQUEST['name']));
			if (empty($name)) {
				$this->errorArray['Name'] = 'Введите же что-нибудь'; 
			} else if (!preg_match('#^[0-9a-zA-Z_]+$#', $name)) {
				$this->errorArray['Name'] = 'Плохие, очень плохие символы в имени'; 
			} else if (strlen($name) > 32) {
				$this->errorArray['Name'] = 'Слишком длинное имя'; 
			} else {
				$query = R('DJEM')->GetForeach()->Path('main.users.$')->Where('_name="?"', $name);
				if ($query->Size()) {
					$this->errorArray['Name'] = 'Такое имя уже существует';					
				} 
			}
			
							$firstname = strip_tags(trim($_REQUEST['firstname']));
				if (empty($firstname)) {
					$this->errorArray['firstname'] = 'Обязательное поле'; 
				} 
							$lastname = strip_tags(trim($_REQUEST['lastname']));
				if (empty($lastname)) {
					$this->errorArray['lastname'] = 'Обязательное поле'; 
				} 
						if (trim($_REQUEST['password1']) == '') {
				$this->errorArray['Password'] = 'Пожалуйста, введите пароль';
			} else if ($_REQUEST['password1'] != $_REQUEST['password2']) {
				$this->errorArray['Password'] = 'Введенные пароли не совпадают';				
			}
			
			if (empty($_REQUEST['email'])) {
				$this->errorArray['Email'] = 'Вы забыли ввести электронный адрес';
			} else if (!preg_match('#^\s*([-a-zA-Z0-9_.]+@[-a-zA-Z0-9_]+(\.[-a-zA-Z_]+)+)+\s*$#', $_REQUEST['email'])) {
				$this->errorArray['Email'] = 'Адрес введен некорректно';
			} else {
				$query = R('DJEM')->GetForeach()->Path('main.users.$')->Where('email="?"', trim($_REQUEST['email']));
				if($query->Size() > 0) {
					$this->errorArray['Email'] = 'Такой емэйл уже существует в базе';
				}
			}
		}
		
    	function Register() {        	
        	$this->errorArray = array();
        	if (count($this->errorArray)) {
				$this->error = 'Не заполнены обязательные поля';
				return false;
			}
			$this->ValidateRegistration();
			if(count($this->errorArray) == 0) {
				$user = new DJEMDocument($this->djem);
				$user->_name = trim(strip_tags(($_REQUEST['name']) ? $_REQUEST['name'] : ""));
				$user->email = trim(strip_tags(($_REQUEST['email']) ? $_REQUEST['email'] : ""));
				$user->password = md5(trim($_REQUEST['password1']));
				
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 					
			    						
				 									$user->ip = $_SERVER['REMOTE_ADDR'];
				$userId = $user->Store($this->folderId);
				$user->_url = '/users/' . $userId . '/';		
				$user->_file = '/users/' . $userId . '/index.phtml';
								$user->Store();
									$login_result = R('DJEMAuth')->Login($user->_name, $user->password);
								$server = new DJEMServer($this->djem);			
				$server->PublishDocument($userId);
				return array('url' => $user->_url);
			}
			return false;
        }
        
        function UpdateEmail() {
        	$authObj = R('DJEMAuth');
        	if ($authObj->_id == false) {
        		$this->error = 'Необходима авторизация';
        		return false;
        	}
        	$email = $_REQUEST['email'];
        	if (!preg_match('#^\s*([-a-zA-Z0-9_.]+@[-a-zA-Z0-9_]+(\.[-a-zA-Z_]+)+)+\s*$#', $email)) {
        		$this->error = 'Неправильно введен адрес эл. почты';
        		return false;
        	} else {
        		$query = R('DJEM')->GetForeach()->Path('main.users.$')->Where('email="?"', trim($email));
				if($query->Size() > 0) {
					$this->error = 'Такой емэйл уже существует в базе';
					return false;
				}
        	}
        	$user = $this->djem->Load($authObj->_id);
        	$user->change_email = "on";
        	$user->newEmail = $email;
        	$user->newEmailConfirmationCode = md5($email . "ololo" . rand(0, 99999));
        	$user->Store();
			$server = new DJEMServer($this->djem);			
			$server->PublishDocument($user->_id);
        	return true;
        }
        
        
        function UpdatePassword() {
        	$authObj = R('DJEMAuth');
        	if ($authObj->_id == false) {
        		$this->error = 'Необходима авторизация';
        		return false;
        	}
        	$newPassword01 = isset($_REQUEST['password01']) ? $_REQUEST['password01'] : ""; 
        	$newPassword02 = isset($_REQUEST['password02']) ? $_REQUEST['password02'] : "";
        	$newPasswordSend = (isset($_REQUEST['send']) && $_REQUEST['send'] == "on") ? "on" : "";
        	if ($newPassword01 == "") {
        		$this->error = 'Не введен новый пароль';
        		return false;
        	}
			if($newPassword01 != $newPassword02) {
				$this->error = 'Введенные пароли не совпадают.';
        		return false;
			}
        	$user = $this->djem->Load($authObj->_id);
        	$user->password = md5($newPassword01);
        	$user->send_password = $newPasswordSend;
        	$user->newPassword = $newPassword01;
        	$user->Store();
        	$server = new DJEMServer($this->djem);
        	$server->PublishDocument($authObj->_id);
        	return true;
        }
        
    }
    