<?php


    class DJEMXml {
        public $__xml;
        public $__attributes; 
    
        function __construct($xml = false, $encoding = 'utf-8') {
            if ($xml instanceof SimpleXMLElement) {
                $this->__xml = $xml;
            } elseif ($xml) {
                $this->Parse($xml, $encoding);
            } else {
				$this->Parse('<root></root>', $encoding);
	    	}
	    	
	    	$this->__attributes = false;
        }


        function Parse($xml, $encoding = 'utf-8') {
            $xml = str_replace('<$', '<__', $xml); 
            $xml = str_replace('</$', '</__', $xml); 
            $xml = preg_replace('#(<'.'[a-zA-Z0-9_/]+):#', '$1___', $xml);
            
            if (substr($xml, 0, 5) != '<?xml') $xml = '<?xml version="1.0" encoding="' . $encoding . "\"?>\n" . $xml;
            
            $this->__xml = simplexml_load_string($xml);
            if ($this->__xml === false) {
                $this->__xml = new SimpleXMLElement();
            }
        }

        function Keys() {
            return $this->__xml->children();
        }
	
	
		function GetName() {
		    return $this->__xml->getName();
		}
		
		
		function Text() {
			return strval($this->__xml);		
		}
	
	
		function Insert($node) {
		    $node1 = dom_import_simplexml($this->__xml);
		    $dom_sxe = dom_import_simplexml($node->__xml);
		    $node2 = $node1->ownerDocument->importNode($dom_sxe, true);	
		    $node1->appendChild($node2);
		}
	
	
		function Node($key) {
		    $key = $this->_ClearKey($key);
		    
		    return new DJEMXml($this->__xml->$key); 
		}    	
	
		function Nodes($key) {
		    $key = $this->_ClearKey($key);
		    $result = array();
		    
		    foreach ($this->__xml->children() as $c) {
				if ($c->getName() == $key) {
				    $result[] = new DJEMXml($c);
				}
		    }
		    
		    return $result;
		}
	
		function Attributes() {
			if ($this->__attributes === false) {
				if ($this->__xml) {
					$this->__attributes = $this->__xml->attributes();				
				} 							
			}
			
			return $this->__attributes;
		}
		
		function Attribute($key, $value = NULL) {
			$this->Attributes();
			
			if (is_null($value)) {				
				return strval($this->__attributes->$key); 
				
			} else {
				if ($this->__attributes->$key) {
					$this->__attributes->$key = $value;					
				} else {					
					$this->__xml->addAttribute($key, $value);
				}
			}
		}
		
		
	
	
        function __toString() {
            return $this->Write();
        }

    
		function _ClearKey($key) {
		    if ($key{0} == '$') $key = '__' . substr($key, 1);
			$key = str_replace(':', '___', $key);
		    
		    return $key;
		}
	

	
		function __call($key, $args) {
	            $key = $this->_ClearKey($key);
	
		    return new DJEMXml($this->__xml->$key); 
		}
	
    
        function __get($key) {
            $key = $this->_ClearKey($key);
            
            return strval($this->__xml->$key);
        }

    
        function __set($key, $value) {
            if ($key{0} == '$') $key = '__' . substr($key, 1);
            $key = str_replace(':', '___', $key);
            
            if (isset($this->__xml->$key)) {
                unset($this->__xml->$key); 
            }

            $this->__xml->$key = $value;
        }

	
		function Reset($key) {
		    $key = $this->_ClearKey($key);
		    
		    unset($this->__xml->$key);
		}


        function Write($withHeader = false) {
            $xml = $this->__xml->asXML();
            $xml = str_replace('<__', '<$', $xml);
            $xml = str_replace('</__', '</$', $xml);
            $xml = preg_replace('#(<'.'[a-zA-Z0-9_/]+)___#', '$1:', $xml);
	    	$xml = preg_replace('#<([^> ]+)([^>]*)/>#s', '<$1$2></$1>', $xml);
            
            if ($withHeader === false) {
                $xml = preg_replace('#<\?xml.+?\?>[\r\n]*#s', '', $xml);
            }
            
            return $xml;
        }

    }