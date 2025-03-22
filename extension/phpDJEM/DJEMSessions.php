<?php

	class DJEMSessions {	
		private $hostName = false;
	
		function __construct($hostName = false) {			
			if ($hostName === false || $hostName instanceof DJEM) {
				$this->hostName = R('Config')->httpHost;

			} else {
				$this->hostName = $hostName; 
			}

			// session_set_cookie_params(0, '/', $this->hostName);
			session_start();
			
			if (!isset($_COOKIE['PHPSESSID']) || $_COOKIE['PHPSESSID'] != session_id()) {
				$this->SetMyCookie("PHPSESSID", session_id(), 0, '/', $this->hostName);
		    } 
		    
		}
	
		function __get($key) {
		    if (isset($_SESSION[$key])) {
				return $_SESSION[$key];
		    } else {
		        return false;
		    }
		}
	
		function __set($key, $value) {
		    $_SESSION[$key] = $value;
		}
		
		function Set($key, $value) {
			$this->__set($key, $value);
		}
		
		function Get($key) {
			return $this->__get($key);
		}
	
		function Reset($key) {
			$_SESSION[$key] = 'z';
		    unset($_SESSION[$key]);		    
		}
	
		
		
		function SetMyCookie($name, $value, $expires, $path, $domain) {			
			if(headers_sent()) {
				print '<META HTTP-EQUIV="SET-COOKIE" CONTENT="'.$name.'='.$value.'; PATH='.$path.'; EXPIRE='.($expires ? gmstrftime ("'%a, %d-%b-%Y %T %Z'", $expires) : $expires) .'; DOMAIN=' . $domain . '">';
			} else {
				setcookie($name, $value, $expires, $path, $domain);
			}
		}
	}
