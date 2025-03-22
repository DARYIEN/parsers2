<?php

	require_once('DJEMSessions.php'); 

	class DJEMAuth extends DJEMDocument {
		public $session = false;
	
		// Fields used
		private $fieldLogin = '_name';
		private $fieldPassword = 'password';
		private $emailField = 'email';
		private $authField = 'djem_user_id';
		private $authRememberTime =  5356800; // 2 * 31 * 86400; 
		
		// Cookie setup
		private $cookieName = 'djem_autologin';
		private $hostName = '.djem.ru'; // HOST для куки - тут
		
		protected $usersFolderId = 148;
		private $userId;


		function __construct($djem = false) {
			parent::__construct($djem); 
						
			$this->session = R('DJEMSessions');
			$this->hostName = R('Config')->httpHost;
			if ($this->session) {
				$this->session->hostName = $this->hostName;
			}			
		}


		// Геттеры на объект авторизации возвращают данные из пользователя. Если пользователь не авторизован - то возвращается всюду false
		// Проверять авторизованность обьекта рекомендуется по _id. Если запрашивается поле, отличное от _id, это ведет к автоматической загрузке 
		// документа-пользователя, если пользователь авторизован. 
		
		function __get($key) {
			if ($key == '_id') {
				return $this->IsAuthed();
			} 
			
			if ($this->documentId) { // Если documentId != false, то документ успешно загружен
				return parent::__get($key);
			}
			
			if ($this->IsAuthed()) {
				try { 
					$this->Load($this->IsAuthed()); 
					return parent::__get($key);
									
				} catch (Exception $e) {
					// Это значит, что пользовательский документ по каким-то причинам не загрузился. 
					// Ничего не делаем, автоматически будет возвращено false 	
				}
			}
			
			return false;
		}

		
		function SetUsersFolderId($usersFolderId) {
		    $this->usersFolderId = intval($usersFolderId);
		}


		function IsAuthed() {			
			
			if ($this->session->{$this->authField}) {				
				return $this->session->{$this->authField};

		    } else {
		        if (isset($_COOKIE[$this->cookieName])) {		        	
		            $this->ReLogin();
		            if ($this->session->{$this->authField}) {
		                return $this->session->{$this->authField};

		            } else {
		                // Уничтожаем куку
		                // @setcookie($this->cookieName, '', time() - 666, '/', $this->hostName);
		                return false;
		            }
		        } else {
		            return false;
		        }
		    }
		}
		
		
		/**/// авторизуем по логину, паролю
		function Login($login, $password, $rememberMe = false) {			
			if ($login instanceof DJEMDocument) {			
				$user = $login;
				$password = $user->password;	
			} else {							
				if(!$login) {
			    	$this->error = 'Заполните, пожалуйста, поле "Логин"';
					return -2;
				}	
				
				$foreach = $this->djem->GetForeach(); 							
				$foreach->Path($this->usersFolderId . '.$');
				$foreach->Where($this->fieldLogin . '="?"', $login); 
				$user = $foreach->Fetch();
								
				if (!$user) {
					$this->error = 'Неправильный логин и/или пароль'; // 'Нет такого пользователя';
					return -3;
				}
				
				if($user->{$this->fieldPassword} != $password || empty($password)) {
					$this->error = 'Неправильный логин и/или пароль';
					return -4;
				}
				
								
			}

			// устанавливаем сессионные переменные
			$this->SetSessionFields($user);	
			if($rememberMe) {
		        $this->SetPersistentLogin($password);
			}
		
		    return $user->_id;
		}
		
		
		/**/// устанавливаем куку с ключом
		function SetPersistentLogin($userPassword) {			
			if (!$this->_id) return false;

		    $reloginKey = $this->_id . ':' . md5($this->_id . $userPassword);
		    $this->SetCookie($this->cookieName, $reloginKey, time() + $this->authRememberTime, '/', $this->hostName);
		
		}


		/**/// авторизуем по куке
		function ReLogin() {		
			$key = $_COOKIE[$this->cookieName];
		    list($keyId, $keyName) = explode(':', $key);
									
		    if ($keyId && $keyName && $this->usersFolderId) {		    	
	        	try { 
	        		$this->Load($keyId); 
	                if ($this->_parent_id != $this->usersFolderId) {
	                	$this->documentId = false;
	                	throw new Exception('illegal user');
	                }

	                if ($this->documentId) {
	                	if (md5($keyId . $this->{$this->fieldPassword}) == $keyName) {
	                        // устанавливаем сессионные переменные
	                        $this->SetSessionFields($this);
	                    } else {
	                    	$this->documentId = false;
	                    }
	                }

				} catch(Exception $e) {
					$this->SetCookie($this->cookieName, '', time() - 666, '/', $this->hostName);
				}
		    }
		}
		
		
		/**/// устанавливаем нужные сессионные переменые для авторизованного юзера из $xml
		function SetSessionFields($xml) {			

			$this->session->{$this->authField} = $xml instanceof DJEMAuth ? $xml->documentId : $xml->_id;
		    $this->session->djem_user_name = trim($xml->firstname . ' ' . $xml->middlename);
		    $this->session->djem_user_sex = $xml->sex == 'male' ? 'male' : ($xml->sex == 'female' ? 'female' : '');
		    $this->session->djem_user_login = $xml->_name;
			
		    return true;
		}
		
		
		function Logoff() {			
		    $this->session->Reset($this->authField);
		    $this->session->Reset('badauth');		    
		    $this->SetCookie($this->cookieName, '', time() - 666, '/', $this->hostName);
		    
		    return true;
		}
		
		
		function SetCookie($name, $value, $expires = 0, $path = false, $domain = false) {
		    if (headers_sent()) {		
		        $cookieParams = $name.'='.$value.'; ';
		
		        if ($expires) {
		            $cookieParams .= ' EXPIRE=' . gmstrftime('%a, %d-%b-%Y %T GMT', $expires) . '; ';
		        }
		
		        if ($path) {
		            $cookieParams .= ' PATH=' . $path . '; ';
				}
					
		        if ($domain) {
		            $cookieParams .= ' DOMAIN=' . $domain . '; ';
		        }
		
		        print '<META HTTP-EQUIV="SET-COOKIE" CONTENT="' . $cookieParams . '">';

		    } else {
		        setcookie($name, $value, $expires, $path, $domain);
		    }
		}
	}
