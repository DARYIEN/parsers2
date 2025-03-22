<?php

    class DJEMForeachCtpl implements Iterator, Countable {
        private $djem = false;
        private $data = null;        
        private $sourceData = array();
        private $ctplId; 
        private $sort = false;
        private $sortOrderAsc = true;
        private $sortTypeString = true;
                
        
        function __construct($djem = null, $params = false) {
        	if ($djem) {
            	$this->djem = $djem;
            } else {
            	$this->djem = R('DJEM');
            }
        }


        function __destruct() {
            
        }
        
        function Sort($sort) {        	            
        	if ($sort) {
        		
        		while ($sort{0} == '-' || $sort{0} == '#') {
	            	if ($sort{0} == '-') {
	            		$this->sortOrderAsc = false;
	            	} else {
	            		$this->sortOrderString = false;
	            	}
	            	
	            	$sort = substr($sort, 1);
	            }

            	$this->sort = $this->TranslateFieldName($sort);
            	
            	
            } else {
            	$this->sort = false;
            	$this->sortOrderAsc = true;
            }
            
            return $this; 
        }

		
		function Path($ctplId) {
			$this->ctplId = intval($ctplId);
			
			return $this;
		}


        function Limit($limit = false, $limitHowMuch = false) {

            return $this;
        }


        function Size() {
            return 0; 
        }


        function Fetch() {
           	if ($this->data === null) {
				$this->Execute();
				$data = current($this->data); 
				
           	} else {
           		$data = next($this->data);
           		if (!$data) {
           			$this->data = null;
           		}
           	}
           	
           	return $data;
        }


        function Execute() {
            if (!$this->ctplId) return false; 
            if ($this->data) {
            	reset($this->data);
            	return true;
            }
            
            $doc = $this->djem->Load($this->ctplId, array('_ctpl'));  // Исключение не ловим, пусть наверху ловят
            if (empty($doc->_ctpl)) return;
            
			$xml = new DJEMXml($doc->_ctpl); 
			
			$this->data = array();
			// чтобы туда сюда не приводить типы - оставим все в объектах SimpleXml
			foreach($xml as $rootNode) {
				// и длопишем "недостоющие" ноды, чтобы не писать класс враппер для xml со своим геттером (для использования в итераторе)
				foreach($rootNode as $key => $value) {
					$fields = $this->TranslateNodeName($key);
					if(!is_array($fields)) {
						$fields = array($fields);
					}
					foreach($fields as $field) {
						$rootNode->{$field} = $value;
					}
				}
				$this->data[] = $rootNode;
			}
			
			//$this->data = $xml->ToArray();
			
			if ($this->sort) {
				usort($this->data, array($this, 'SortCallback'));
			}			
        }
        
        
        function SortCallback($a, $b) {
        	//if ($a[$this->sort] == $b[$this->sort]) {
        	if ($a->{$this->sort} == $b->{$this->sort}) {
        		return 0; 
        	}
        	
        	if ($this->sortOrderAsc) {
        		//return ($a[$this->sort] < $b[$this->sort]) ? -1 : 1;
        		return ($a->{$this->sort} < $b->{$this->sort}) ? -1 : 1;
        	} else {
        		//return ($a[$this->sort] < $b[$this->sort]) ? 1 : -1;
        		return ($a->{$this->sort} < $b->{$this->sort}) ? 1 : -1;
        	}
        }


        function __get($key) {        
        	if (!$this->data) {
        		$this->Execute();
        	}
        
            if ($this->data) {
            	$item = current($this->data);
                
                if ($item) {
                	// пофакту преобразование теперь не нужно, так как названия всех полей уже содержатся в объекте
                	//return $item[$this->TranslateFieldName($key)];
                	return $item->{$key};
                }
            }
            
            return null;
        }
        
        
        function TranslateFieldName($name) {
        	switch ($name) {
        		case '_name': 
        			return 'description';
        		case '_type': 
        			return 'type';
        		case '_code': 
        			return 'code';
        		case '_tab': 
        			return 'tab';
        		case '_sort': 
        			return 'y';
        		case '_description': 
        			return 'description';
        		default:
        			return $name;
        	}        	
        }
        
        // по имени ноды получить имя которое используется в скрипте (обратное TranslateFieldName)
        function TranslateNodeName($name) {
        	switch ($name) {
        		case 'description': 
        			return array('_name', '_description');
        		case 'type': 
        			return '_type';
        		case 'code': 
        			return '_code';
        		case 'tab': 
        			return '_tab';
        		case 'y': 
        			return '_sort';
        		default:
        			return $name;
        	}        	
        }
        
    
    	
    	function ToArray() {    	
    		$args = func_get_args();    		
			$result = array();
			
    		return $result;
    	}
    
    
        
        
        
        // Реализация интерфейса Iterator
        function rewind() {
        	if (empty($this->data)) {
        		$this->Execute();
        	}
        	
        	reset($this->data);
        }
        
        function current() {
        	return current($this->data);
        }
        
        function key() {
        	return false; // $this->_doc->_id;
        }
        
        function next() {
        	return next($this->data);
        }
        
        function valid() {
        	$key = key($this->data);
        	$var = ($key !== NULL && $key !== FALSE);
        	
        	return $var; // $this->_doc !== false; 
        }
        
        
        
        // Реализация интерфейса Countable
        function count() {
        	return $this->Size();
        }
    
    }
    
    