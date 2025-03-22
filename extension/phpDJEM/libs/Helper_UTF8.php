<?php
	Class Helper_UTF8 {
		/**
		* Хелпер для работы с UTF8 (будет постепенно обновляться)
		*
		* @copyright Pavel Ladygin
		* @version 0.1
		*/
		
		/**
		* Кодировка, которая будет использоваться __некоторыми__ методами для декодирования,
		* в случае отсутствия mb_functions
		* 
		* @static
		* @access public
		* @var string
		*/
		public static $encoding_without_mb_functions = "Windows-1251";
		
		/**
		* Ord
		* 
		* @static
		* @access public
		* @param string $c
		* @return integer
		*/
		public static function Ord($c) {
			$h = ord($c{0});
			if ($h <= 0x7F) {
				return $h;
			} else if ($h < 0xC2) {
				return false;
			} else if ($h <= 0xDF) {
				return ($h & 0x1F) << 6 | (ord($c{1}) & 0x3F);
			} else if ($h <= 0xEF) {
				return ($h & 0x0F) << 12 | (ord($c{1}) & 0x3F) << 6
										 | (ord($c{2}) & 0x3F);
			} else if ($h <= 0xF4) {
				return ($h & 0x0F) << 18 | (ord($c{1}) & 0x3F) << 12
										 | (ord($c{2}) & 0x3F) << 6
										 | (ord($c{3}) & 0x3F);
			} else {
				return false;
			}
		}
		
		/**
		* Chr
		* 
		* @static
		* @access public
		* @param integer $c
		* @return string
		*/
		static function Chr($c) {
			if ($c <= 0x7F) {
				return chr($c);
			} else if ($c <= 0x7FF) {
				return chr(0xC0 | $c >> 6) . chr(0x80 | $c & 0x3F);
			} else if ($c <= 0xFFFF) {
				return chr(0xE0 | $c >> 12) . chr(0x80 | $c >> 6 & 0x3F)
											. chr(0x80 | $c & 0x3F);
			} else if ($c <= 0x10FFFF) {
				return chr(0xF0 | $c >> 18) . chr(0x80 | $c >> 12 & 0x3F)
											. chr(0x80 | $c >> 6 & 0x3F)
											. chr(0x80 | $c & 0x3F);
			} else {
				return false;
			}
		}
		
		/**
		* Strtolower
		* 
		* @static
		* @access public
		* @param string $s
		* @return string
		*/
		static function Strtolower($s) {
			if(function_exists('mb_convert_case')) {
				return mb_convert_case($s, MB_CASE_LOWER, "UTF-8");
			} else {
				return iconv(self::$encoding_without_mb_functions, "UTF-8", strtolower(iconv("UTF-8",self::$encoding_without_mb_functions, $s)));
			}
		}
		
		/**
		* Strtoupper
		* 
		* @static
		* @access public
		* @param string $s
		* @return string
		*/
		static function Strtoupper($s) {
			if(function_exists('mb_convert_case')) {
				return mb_convert_case($s, MB_CASE_UPPER, "UTF-8");
			} else {
				return iconv(self::$encoding_without_mb_functions, "UTF-8", strtoupper(iconv("UTF-8",self::$encoding_without_mb_functions, $s)));
			}
		}
		
		/**
		* Substr
		* 
		* @static
		* @access public
		* @param string $s
		* @param integer | array $a
		* @param integer $b
		* @return string
		*/
		static function Substr($s, $a, $b = 0) {
			if(is_array($a)) {
				if(count($a) > 1) {
					$b = $a[1];
					$a = $a[0];
				} else {
					if($a[0] < 0) {
						$a = self::Strlen($s) + $a[0];
						$b = self::Strlen($s) - $a;
					} else {
						$b = $a[0];
						$a = 0;
					}
				}
			} else {
				if($b == 0) {
					$b = $a;
					$a = 0;
				}
			}
			if(function_exists('mb_substr')) {
				return mb_substr($s, $a, $b, "UTF-8");
			} else {
				return iconv(self::$encoding_without_mb_functions, "UTF-8", substr(iconv("UTF-8",self::$encoding_without_mb_functions, $s), $a, $b));
			}
		}
		
		/**
		* Strlen
		* 
		* @static
		* @access public
		* @param string $string
		* @return integer
		*/
		public static function Strlen($string) {
			if(function_exists('mb_strlen')) {
				return mb_strlen($string, "UTF-8");
			} else {
				return count(preg_split("//u", $string)) - 2;
			}
		}
		
		/**
		* Convert to utf8
		*
		* @static
		* @access public
		* @param string $string
		* @param string $source_encoding
		* @return string
		*/
		static function GetUTF8String($string, $source_encoding) {
			if($source_encoding == "UTF-8") {
				return $string;
			}
			return iconv($source_encoding, "UTF-8", $string);
		}
		
		/**
		* Strpos
		*
		* @static
		* @access public
		* @param string $string
		* @param string $needle
		* @param integer $offset
		* @return integer
		*/
		static function Strpos($string, $needle, $offset = 0) {
			if($offset > Helper_UTF8::Strlen($string)) {
				return Helper_UTF8::Strlen($string);
			}
			if($offset < 0) {
				$offset = 0;
			}
			if(function_exists('mb_strpos')) {
				return mb_strpos($string, $needle, $offset, "UTF-8");
			} else {
				return iconv_strpos($string, $needle, $offset, "UTF-8");
			}
		}
		
		/**
		* Stripos
		*
		* @static
		* @access public
		* @param string $string
		* @param string $needle
		* @param integer $offset
		* @return integer
		*/
		static function Strrpos($string, $needle, $offset = 0) {
			if($offset > Helper_UTF8::Strlen($string)) {
				return Helper_UTF8::Strlen($string);
			}
			if($offset < 0) {
				$offset = 0;
			}
			if(function_exists('mb_strrpos')) {
				return @mb_strrpos($string, $needle, $offset, "UTF-8");
			} else {
				return @iconv_strrpos($string, $needle, $offset, "UTF-8");
			}
		}
	}
?>