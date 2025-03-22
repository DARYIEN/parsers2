<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/system/php/DJEMSessions.php');

class HTTP {

	var $_HTTP_HOST;
	var $_WWW_DJEM;
	var $_WWW_DYNADJEM;
	var $session;
	var $_request = array();
	var $_newrequest = array();

	function HTTP($wwwhost = false, $wwwdjem = false) {
		if ($wwwhost !== false) {
			$this->_HTTP_HOST = $wwwhost;
		} else {
			$this->_HTTP_HOST = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
		}
		$this->SetDjemUrl($wwwdjem);		
		$this->session = new DJEMSessions();
	}
	
	function SetDjemUrl($value) {
		$this->_WWW_DJEM = $value !== false ? $value  : '/cgi-bin/djem/';
		$this->_WWW_DYNADJEM = $this->_WWW_DJEM.'dynadjem';
	}
	
	function GetRequestString($values) {
		$requestStr = '';
		foreach ($values as $key=> $value) {
			if (is_array($value)) {
				foreach ($value as $v) {
					if ($requestStr != "") {
						$requestStr .= "&";
					}
					$requestStr .= $key.'%5B%5D='.urlencode($v);
				}
			} else {
				if ($requestStr != "") {
					$requestStr .= "&";
				}
			
				$requestStr .= $key."=".urlencode($value);
			}
			
		}
		return $requestStr;	
	}
	
	function ParseUrl($url) {
		$return = array();
		parse_str($url, $return);
		return $return;
	}
	
	
	function GetRequest($url, $port = 80) {
		$parsedUrl = parse_url($url);
		if (!isset($parsedUrl['path'])) {
			$parsedUrl['path'] = '/';
		}
		$content = '';
		$path = $parsedUrl['path'];
		if (isset($parsedUrl['query']) && ($parsedUrl['query'] != '')) {
			$path .= '?'.$parsedUrl['query'];
		}
		$fp = fsockopen($parsedUrl['host'], $port);
	    if ($fp) {
		    fputs($fp, "GET ".$path." HTTP/1.0\r\n");
		    fputs($fp, "Host: ".$parsedUrl['host']."\r\n");
		    fputs($fp, "Connection: Close\r\n\r\n");
		    while (!feof($fp)) {
		      $content .= fgets($fp, 4096);
		    }
		    fclose($fp);
	    } else {
	    	return false;
	    }			
	    
	    if (preg_match("/^.*?(\r?\n){2}(.*)$/s", $content, $matches)) {
	    	$result = $matches[2];
	    } 
	    return $result;
	    
	}
	
	function PostRequest($host, $port, $path, $values = array()) {
		$content = '';
		$requestStr = '';
		foreach ($values as $key=> $value) {
			if ($requestStr != "") {
				$requestStr .= "&";
			}
			$requestStr .= $key."=".urlencode($value);
		}
		
		$post_query = $requestStr . "\r\n";
		$fp = fsockopen($host, $port);
	    if ($fp) {
		    fputs($fp, "POST ".$path." HTTP/1.0\r\n");
		    fputs($fp, "Host: $host\r\n");
		    fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		    fputs($fp, 'Content-length: '. strlen($post_query) ."\r\n\r\n");
		    fputs($fp, $post_query);
		    while (!feof($fp)) {
		      $content .= fgets($fp, 4096);
		    }
		    fclose($fp);
	    } else {
	    	return false;
	    }
	    $result = '';
	    if (preg_match("/^.*?(\r?\n){2}(.*)$/s", $content, $matches)) {
	    	$result = $matches[2];
	    } 
	    return $result;
	}
		
	function Get($key, $default = '') {
		if (isset($_GET[$key]))	{
			return $_GET[$key];
		}
		
		if (isset($_POST[$key])) {
			return $_POST[$key];
		}
		
		if (isset($_REQUEST[$key])) {
			return $_REQUEST[$key];
		}
		
		return $default;
	}
	
	function GetLocalUrl($url) {
		return $this->GetUrl("http://".$this->_HTTP_HOST.$url);
	}
	
	function GetUrl($url) {

		if (ini_get('allow_url_fopen')) {
			return file_get_contents($url);
		} else {
			return $this->GetRequest($url);
		}
	}
	
	function CallVx($id) {
		$ar = array('action'=> 'show', 'id' => $id);
		return $this->GetLocalUrl(DJEM_WWWPATH.'vx?'.$this->GetRequestString($ar));
	}
	
	function CallDinamicDjem($id, $values = array()) {
	
		$str = $this->_WWW_DYNADJEM.'?_djem_id='.(int)$id;
		foreach ($values as $key => $val) {
			if (is_array($val)) {
				$valstr = join(',',$val);
			} else {
				$valstr = $val;
			}
			$str .= '&'.$key.'='.urlencode($valstr);
		}
		return $this->GetLocalUrl($str);
	}
	
	function Redirect($url, $meta = false, $exit = true) {
		if (headers_sent() || $meta) {
			print '<meta http-equiv="refresh"  content="0; url='.$url.'" />';
		} else {
			header('Location: '.$url);
		}
		if ($exit) {
			exit(0);
		}
	}
	
	function GetRequestVars($keys) {
		$data = array();
		foreach ($keys as $val) {
			$data[$val] = $this->Get($val);
		}	
		
		return $data;
	}
	
	function GetArrayCheckBox($id, $values) {
		$result = array();
		$keys = array_keys($values);
		for ($i = 0; $i < count($keys); ++$i) {
			$s = $id.'_'.$keys[$i];
			if ($this->Get($s)) {
				$result[] = $keys[$i];
			}
		}
		
		return $result;
	}
	
	function SetCookie($key, $value, $expired = "", $path="/") {
		if (!headers_sent()) {
			setcookie($key, $value, $expired, $path);
		} else {
			$str = '<meta http-equiv="Set-Cookie" content="'.$key.'='.$value.'; ';
			if ($expired != '') {
				$str .= 'expires='.gmdate('l, d-M-y H:i:s e', $expired).'; '; 
			}
			$str .= 'path='.$path.'">';
			print $str;
		}
	}
	
	function GetCookie($key, $default = false) {
		$result = $default;
		if (isset($_COOKIE[$key])) {
			$result = $_COOKIE[$key];
		}
		
		return $result;
	}

	function InitRequest($vals = array()) {
		if (empty($vals)) {
			foreach ($_POST as $key => $value) {
				$this->_request[$key] = $value;
			}
			
			foreach ($_GET as $key => $value) {
				$this->_request[$key] = $value;
			}
		} else {
			$this->_request = $vals;
		}
		$this->_newrequest = $this->_request;
	}	
	
	function FlushRequest() {
		$this->_newrequest = $this->_request;
	}
	
	function Set($key, $value) {

		if ($value !== '') {
			$this->_newrequest[$key] = $value;
		} else {
			if (isset($this->_newrequest[$key])) {
				unset($this->_newrequest[$key]);
			}
		}
	}
	
	function GetCurrentRequestString() {
		return $this->GetRequestString($this->_newrequest);
	}
	
	function GetCurrentUrl() {
		$ret = $_SERVER['REQUEST_URI'];
		if (!empty($this->_newrequest)) {
			$ret .= '?'.$this->GetCurrentRequestString();
		}
		return $ret;
	}
	
	function GetRequestChangedByArray($newvars) {

		foreach($newvars as $key => $value) {
			$this->Set($key, $value);
		}
		$result = $this->GetCurrentRequestString();
		$this->FlushRequest();
		return $result;		
	}
	
	function UploadFile($formName, $dir, $file = false) {
		if (!isset($_FILES[$formName])) {
			return false;
		}
		if ($_FILES[$formName]['error'] != UPLOAD_ERR_OK) {
			return false;
		}
		if (((int)$_FILES[$formName]['size'] == 0) || ($_FILES[$formName]['name'] == '') || ($_FILES[$formName]['tmp_name'] == '') || (!is_readable($_FILES[$formName]['tmp_name']))) {
			return false;
		}
		
		
		$ext = pathinfo($_FILES[$formName]['name'], PATHINFO_EXTENSION);
		if ($file !== false) {
			$fileName = $dir.'/'.$file.'.'.$ext;
		} else {
			$fileName = $dir.'/'.$_FILES[$formName]['name'];
		}
		$return = false;
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
			chmod($dir, 0755);
		}
		if  (move_uploaded_file($_FILES[$formName]['tmp_name'], $fileName)) {
			chmod($fileName, 0644);
			$return = $fileName;
		}
		
		return $return;
	}
};

?>