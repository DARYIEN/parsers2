<?php
	
	class DJEMScript {
		/**
		* @version 0.1
		*/
	
		
		/**
		* @static
		* @access private
		* @var string
		*/
		private static $_Format_Rus_TagOpen = 'format_rus_tag_open';
		
		/**
		* @static
		* @access private
		* @var string
		*/
		private static $_Format_Rus_TagClose = 'format_rus_tag_close';
		
		/**
		* @static
		* @access private
		* @var array
		*/
		private static $_Format_Rus_Refs = array();
		
		/**
		* @static
		* @access private
		* @var integet
		*/
		private static $_Format_Rus_RefsCntr = 0;
		
		/**
		* @static
		* @access private
		* @var array
		*/
		private static $time_months = array('rus'=>array(	'nominative' => array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'),
															'genitive' => array('Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря')),
											'eng'=>array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"));
		/**
		* @static
		* @access private
		* @var array
		*/
		
		
		
		private static $transliterate_pairs = array(
												'rus' => array(	
													"а" => "a",
													"б" => "b",
													"в" => "v",
													"г" => "g",
													"д" => "d",
													"е" => "e",
													"ё" => "yo",
													"ж" => "zh",
													"з" => "z",
													"и" => "i",
													"й" => "y",
													"к" => "k",
													"л" => "l",
													"м" => "m",
													"н" => "n",
													"о" => "o",
													"п" => "p",
													"р" => "r",
													"с" => "s",
													"т" => "t",
													"у" => "u",
													"ф" => "f",
													"х" => "h",
													"ц" => "c",
													"ч" => "ch",
													"ш" => "sh",
													"щ" => "sch",
													"ь" => "'",
													"ы" => "y",
													"ъ" => "'",
													"э" => "e",
													"ю" => "yu",
													"я" => "ua",
													"А" => "A",
													"Б" => "B",
													"В" => "V",
													"Г" => "G",
													"Д" => "D",
													"Е" => "E",
													"Ё" => "YO",
													"Ж" => "ZH",
													"З" => "Z",
													"И" => "I",
													"Й" => "Y",
													"К" => "K",
													"Л" => "L",
													"М" => "M",
													"Н" => "N",
													"О" => "O",
													"П" => "P",
													"Р" => "R",
													"С" => "S",
													"Т" => "T",
													"У" => "U",
													"Ф" => "F",
													"Х" => "H",
													"Ц" => "C",
													"Ч" => "CH",
													"Ш" => "SH",
													"Щ" => "SCH",
													"Ь" => "'",
													"Ы" => "Y",
													"Ъ" => "'",
													"Э" => "E",
													"Ю" => "YU",
													"Я" => "UA"
												));
		
		/**
		* Вывод времени
		*
		* @static
		* @access public
		* @param MIXED
		* @return string
		*/
		public static function Time() {
			$arguments = func_get_args();
			// метод тайм
			if(count($arguments) > 0 && is_string($arguments[0])) {
				$date_format = $arguments[0];
				$timestamp = (isset($arguments[1])) ? $arguments[1] : mktime(0,0,0,1,1,1970);
				if(preg_match_all("/(%[d1wmrsenbiyhg]+)/iu",$date_format, $matches)) {
					foreach($matches[0] as $pattern) {
						switch($pattern) {
							case "%d1" :
								$date_format = str_replace($pattern, date('j',$timestamp), $date_format);
								break;
							case "%d" :
								$date_format = str_replace($pattern, date('d',$timestamp), $date_format);
								break;
							case "%w" :
								$date_format = str_replace($pattern, date('N',$timestamp), $date_format);
								break;
							case "%mrb" :
								$date_format = str_replace($pattern, mb_strtoupper(self::$time_months['rus']['genitive'][intval(date('m',$timestamp))-1], 'utf-8'), $date_format);
								break;
							case "%mrs" :
								$date_format = str_replace($pattern, mb_strtolower(self::$time_months['rus']['genitive'][intval(date('m',$timestamp))-1], 'utf-8'), $date_format);
								break;
							case "%mri" :
								$date_format = str_replace($pattern, self::$time_months['rus']['nominative'][intval(date('m',$timestamp))-1], $date_format);
								break;
							case "%mrn" :
							case "%mr" :
								$date_format = str_replace($pattern, self::$time_months['rus']['genitive'][intval(date('m',$timestamp))-1], $date_format);
								break;
							case "%mes" :
								$date_format = str_replace($pattern, mb_strtolower(self::$time_months['eng'][intval(date('m',$timestamp))-1], 'utf-8'), $date_format);
								break;
							case "%meb" :
								$date_format = str_replace($pattern, mb_strtoupper(self::$time_months['eng'][intval(date('m',$timestamp))-1], 'utf-8'), $date_format);
								break;
							case "%men" :
							case "%mn" :
								$date_format = str_replace($pattern, self::$time_months['eng'][intval(date('m',$timestamp))-1], $date_format);
								break;
							case "%mm" :
								$date_format = str_replace($pattern, date('m',$timestamp), $date_format);
								break;
							case "%m1" :
								$date_format = str_replace($pattern, date('n',$timestamp), $date_format);
								break;
							case "%yy" :
								$date_format = str_replace($pattern, date('Y',$timestamp), $date_format);
								break;
							case "%y" :
								$date_format = str_replace($pattern, date('y',$timestamp), $date_format);
								break;
							case "%h" :
								$date_format = str_replace($pattern, date('H',$timestamp), $date_format);
								break;
							case "%m" :
								$date_format = str_replace($pattern, date('i',$timestamp), $date_format);
								break;
							case "%s" :
								$date_format = str_replace($pattern, date('s',$timestamp), $date_format);
								break;
							case "%g" :
								$date_format = str_replace($pattern, date('r',$timestamp), $date_format);
								break;
							default :
								break;
						}
					}
				}
				return $date_format;
			}
			// оператор тайм
			if(count($arguments) == 0 || is_int($arguments[0])) {
				$year 	= isset($arguments[0]) ? (int)$arguments[0] : date('Y');
				$month	= isset($arguments[1]) ? (int)$arguments[1] : date('n');
				$day	= isset($arguments[2]) ? (int)$arguments[2] : date('j');
				$hour	= isset($arguments[3]) ? (int)$arguments[3] : date('H');
				$minute	= isset($arguments[4]) ? (int)$arguments[4] : date('i');
				$second	= isset($arguments[5]) ? (int)$arguments[5] : date('s');
				return mktime ($hour, $minute, $second, $month, $day, $year);
			}
		}

		/**
		* Замена
		*
		* @static
		* @access public
		* @param string $where
		* @param string $from
		* @param string $to
		* @return string
		*/
		public static function Replace($where, $from, $to) {
			if ($from{0} == '/') {
				return preg_replace($from, $to, $where);
			}
			return str_replace($from, $to, $where);
		}
		
		/**
		* Вырезеам подстроку
		*
		* @static
		* @access public
		* @param string $text
		* @param string|integer $len
		* @return string
		*/
		public static function Cut($text, $len) {
	        return preg_replace('/^((?:[\x00-\x7F]|(?:[\xC0-\xFF][\x80-\xBF]+)){1,'.$len.'})(?:.*$)/s', '$1', $text);
		}
		
		
		/**
		* Отрезаем до пробела
		*
		* @static
		* @access public
		* @param string $text
		* @param string|integer $len
		* @return string
		*/
		public static function CutSpace($text, $len) {
	        return preg_replace('/^((?:[\x00-\x7F]|(?:[\xC0-\xFF][\x80-\xBF]+)){1,'.$len.'}.*?)(?:\s|$)(?:.*$)/s', '$1', $text);
		}
		
		/**
		* Соответствие
		*
		* @static
		* @access public
		* @param string $pattern
		* @param string $where
		* @return array|boolean
		*/
		public static function Match($pattern = "", $where = "") {
			global $var;
			if ($pattern{0} == '/') {
				if(preg_match_all($pattern, $where, $matches)) {
					for($i=0; $i<count($matches); $i++) {
						$var['match:' . ($i)] = $matches[$i][0];
					}
					return $matches[0];
				}
			} else {
				if(mb_strpos($where, $pattern) !== false) {
					return true;
				}
			}
			return false;
		}
		
		/**
		* Длинна строки
		*
		* @static
		* @access public
		* @param string $text
		* @return integer
		*/
		public static function Strlen($text) {
	        return preg_match_all('/(.)/su', $text, $m);
		}
		
		/**
		* Проверка вхождения $value в $list
		*
		* @static
		* @access public
		* @param mixed $value
		* @param string|array $list (либо массив, либо список (строка) значений через запятую)
		* @return integer
		*/
		public static function In($value, $list) {
			if(!is_array($list)) {
				$list = explode(",", $list);
			}
			if(in_array((string)$value, $list)) {
				return true;
			}
			return false;
		}
		
		/**
		* В нижний регистр
		*
		* @static
		* @access public
		* @param mixed $value
		* @return string
		*/
		public static function Lcase($value = "") {
			return mb_convert_case((string)$value, MB_CASE_LOWER, "utf-8");
		}
		
		/**
		* В верхний регистр
		*
		* @static
		* @access public
		* @param mixed $value
		* @return string
		*/
		public static function Ucase($value = "") {
			return mb_convert_case((string)$value, MB_CASE_UPPER, "utf-8");
		}
		
		private static function _Format_Rus_PutTag($x) {
			self::$_Format_Rus_Refs[] = $x[0];
			return DJEMScript::$_Format_Rus_TagOpen.(self::$_Format_Rus_RefsCntr++).DJEMScript::$_Format_Rus_TagClose;
		}
		
		private static function _Format_Rus_GetTag($x) {
			return self::$_Format_Rus_Refs[$x[1]];
		}
		
		/**
		* Форматирование данных
		*
		* @static
		* @access public
		* @param mixed $data
		* @param string $formatType
		* @return string
		*/
		public static function Format($data, $formatType) {
			$arguments = func_get_args();
			$arguments = array_slice($arguments, 2);
			switch($formatType) {
				case "kb" :
					$data = round(floatVal($data) / 1024, 2);
					break;
				case "mb" :
					$data = round(floatVal($data) / (1024 * 1024), 2);
					break;
				case "gb" :
					$data = round(floatVal($data) / (1024 * 1024 * 1024), 2);
					break;
				case "spaces" :
					$data = strval($data);
					if($data != "") {
						if(self::Strlen($data) > 3) {
							$data = strrev($data);
							preg_match_all("/(.{1,3})/", $data, $data);
							$data = implode(" ", $data[0]);
							$data = strrev($data);
						}
					}
					break;
				case "roman" :
					$data = DJEMDigits::Roman(intval($data));
					break;
				case "digits" :
					if(count($arguments) >= 3) {
						$data = DJEMDigits::VerbalRus(intval($data), $arguments[0], $arguments[1], $arguments[2]);
					}
					break;
				case "rus" :
					self::$_Format_Rus_Refs = array();
					self::$_Format_Rus_RefsCntr = 0;
					 // комментарии
					$data = preg_replace_callback('/<!--.*?-->/us', array('self', '_Format_Rus_PutTag'), $data);
					 // обычные теги
					$data = preg_replace_callback('/<(?:[^\'"\>]+|".*?"|\'.*?\')+>/us', array('self', '_Format_Rus_PutTag'), $data);
					// Заменяем табулюцию и перевод строки на пробел
					$data = strtr( $data, "\t\n\r", '   ' );
					// Убираем лишние пробелы
					$data = preg_replace( '/ +/u', ' ', $data);
					// Убираем лишние пробелы перед концом строки
					$data = preg_replace( '/( | )+<br/u', '<br', $data );
					// Заменяем &quot на "
					$data = str_replace( '&quot;','"', $data ); 
					// Расстановка кавычек-"елочек"
					$data = preg_replace( '/([>( ]|^)(")([^"]*)([^ "(])(")/u', '\\1«\\3\\4»', $data ); 
					// Если есть вложенные кавычки
					if( stristr( $data, '"' ) )  {
						$data = preg_replace( '/([>( ]|^)(")([^"]*)([^ "(])(")/u', '\\1«\\3\\4»', $data );
						while( preg_match( '/(«)([^»]*)(«)/u', $data ) ) {
							$data = preg_replace( '/(«)([^»]*)(«)([^»]*)(»)/u', '\\1\\2„\\4“', $data );				
						}
					}
					// Делаем замены
					$data = strtr( $data, array('<br /> '=>'<br />',
												'• '=>'• ',
												' - '=>' — ',' - '=>' — ',
												'>- '=>'>— ','> - '=>'>— ',
												'...'=>'…',
												'!?'=>'?!',
												'+/-'=>'±',
												' 1/2'=>' &frac12;',' 1/4'=>' &frac14;',' 3/4'=>' &frac34;',
												'(r)'=>'<sup>®</sup>','(R)'=>'<sup>®</sup>',
												'(c)'=>'©','(C)'=>'©','(с)'=>'©','(С)'=>'©',
												'(tm)'=>'™'
											));
					// удаляем пробелы после открывающей скобки и перед закрыващей скобкой
					$data = preg_replace( '/\( *(.+?) *\)/su', '(\\1)', $data ); 
					// добавляем пробел между словом и открывающей скобкой, если его нет
					$data = preg_replace( '/([а-яА-ЯёЁa-zA-Z])\(/u', '\\1 (', $data ); 
					// Удаляем пробел перед знаками препинания
					$data = preg_replace( '/ ([.,!?:;])/u', '\\1', $data ); 
					// Возвращаем теги на место
					while(preg_match('/'.DJEMScript::$_Format_Rus_TagOpen.'\d+?'.DJEMScript::$_Format_Rus_TagClose.'/u', $data)) {
						$data = preg_replace_callback('/'.DJEMScript::$_Format_Rus_TagOpen.'(\d+)'.DJEMScript::$_Format_Rus_TagClose.'/u', array('self', '_Format_Rus_GetTag'), $data);
					}
					break;
				case "translit" :
					$data = strtr(strval($data), self::$transliterate_pairs["rus"]);
					break;
				case "url" :
					$data = preg_replace("/[^a-zA-Z0-9_]/", "-", strtr(strval($data), self::$transliterate_pairs["rus"]));
					break;
			}
			return $data;
		}
		
		public static function Notags($text) {
   			return strip_tags($text);
		}
	}
?>