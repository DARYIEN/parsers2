<?php

    class DJEMForeach implements Iterator, Countable {
        private $djem = false;
        private $path = false;
        private $where = false;
        private $whereValues = false;
        private $innerWhere = false;
        private $typeWhere = false;
        private $sort = false; 
        private $limit = false;
        private $fields = false;

        private $query = false; 
        private $_doc = false; 
        private $joinCacheTables = array();
        private $joinTagTables = array();
        
        private $joinLeftTables = array();
        private $joinInnerTables = array();
        


        function __construct($djem, $params = false) {
            $this->djem = $djem;
        }


        function __destruct() {            
        }
        


        function Fields() {
            $args = $this->djem->db->array_flatten(func_get_args());
            
            if (count($args)) {
                $this->fields = array('document_id' => true);

                foreach ($args as $arg) {
                    if ($arg{0} == '_') {
                        $this->fields['document' . $arg] = true;
                    } else {
                        $this->fields['document_content'] = true;
                    }
                }
            }       
            
            return $this;
        }


		function Type($type) {
			switch ($type) {
				case 'documents':
				case 'folders':
					$query = $this->djem->Query('SELECT document_id FROM documents WHERE document_path LIKE "settings.types.%" 
													AND document_subtypes' . ($type == 'documents' ? '=""' : '!=""') . ' AND document_deleted=0');
					
					$types = array();
					foreach ($query as $q) {
						$types[] = $q['document_id'];
					}
					
					if (count($types)) {
						$this->typeWhere = ' AND document_type IN (' . join(',', $types) . ') ';
					} else {
						$this->typeWhere = '';
					} 
					
					break;
			
				default:
					$this->typeWhere = '';
					break;			
			}
			
			return $this;
		}


        function Where($where) {        				
			$this->whereValues = $this->djem->db->array_flatten(func_get_args());
			array_shift($this->whereValues);		
							
			$resultStr = $this->WhereScanner($where);
			
			if ($resultStr) { 
                $this->where = ' AND (' . $resultStr . ')';
            } else {
                $this->where = '';
            }

            return $this;
        }


        function WhereScanner($where, $whereTriplet = 'WhereTriplet') {
        	$resultStr = '';            
            $this->djem->GetCacheTables();
            
            $OPEN = 0; 
            $LVALUE = 1; 
            $ACTION_WAITING = 2;
            $ACTION = 3;
            $RVALUE_WAITING = 4;
            $RVALUE = 5;
            
            $state = $OPEN;
            $level = 0;
            $whereLength = strlen($where);
            
            for ($i=0; $i < $whereLength; ++$i) {    
                $s = $where{$i};

                switch ($state) {
                    case $OPEN:
                        if ($s == '(') {
                            $resultStr .= '('; 
                            ++$level;
                        } elseif ($s == ')') {
                            $resultStr .= ')';
                            --$level;
                        } else 
                        if ($s == ' ') {

                        } else {
                            // Сосканируем токены
                            if ($s == '|' && $where{$i + 1} == '|') {
                                $resultStr .= ' OR '; 
                                $i += 1; 
                                $state = $OPEN;

                            } elseif (substr($where, $i, 2) == 'OR' && self::IsStrange($where{$i+2})) {
                                $resultStr .= ' OR ';
                                $i += 1;
                                $state = $OPEN;

                            } elseif (substr($where, $i, 2) == '&&') {
                                $resultStr .= ' AND '; 
                                $i += 1; 
                                $state = $OPEN;

                            } elseif (substr($where, $i, 3) == 'AND' && self::IsStrange($where{$i+3})) {
                                $resultStr .= ' AND ';
                                $i += 2;
                                $state = $OPEN;

                            } else {                                                
                                $fieldName = $s;
                                $state = $LVALUE;
                            }
                        }
                        break;

                    case $LVALUE: 
                        if (self::IsStrange($s)) {
                            if ($s == ' ') {
                                $wasStrange = false;
                            } else {
                                $wasStrange = true;
                            }
                            --$i; // Раз уж натолкнулись на странное значение - отступим назад, захватим его при следующем проходе
                            
                            $state = $ACTION_WAITING;
                        } else {
                            $fieldName .= $s;
                        }
                        break;

                    case $ACTION_WAITING:
                        if ($s != ' ') {
                            $action = $s; 
                            $state = $ACTION; 
                        } 
                        break;

                    case $ACTION:
                        if ($s == ' ') {
                            $state = $RVALUE_WAITING;
                        } elseif ($wasStrange && self::IsStrange($s) == false) {
                            --$i;
                            $state = $RVALUE_WAITING;
                        } else {
                            $action .= $s;
                            if (self::IsStrange($s)) {
                                $wasStrange = true;
                            }
                        }
                    
                        break;


                    case $RVALUE_WAITING:
                        if ($s == '"' || $s == "'") {
                            $sep = $s;
                            $rvalue = ''; 
                            $state = $RVALUE;
                        } elseif ($s != ' ') {
                            $sep = ' ';
                            $rvalue = $s;
                            $state = $RVALUE;
                        }
                    
                        break;

                    case $RVALUE:
                        if ($s == $sep) {
                            $resultStr .= $this->$whereTriplet($fieldName, $action, $rvalue, $sep);
                            $state = $OPEN;
                            
                        } elseif ($sep == ' ' && self::IsStrange($s)) {
                            $resultStr .= $this->$whereTriplet($fieldName, $action, $rvalue, $sep);
                            --$i; // Раз уж натолкнулись на странное значение - отступим назад, захватим его при следующем проходе
                            $state = $OPEN;
                            
                        } else {
                            $rvalue .= $s; 
                        }
                        break;
                }
            }
            
            if ($state == $RVALUE) {
                $resultStr .= $this->$whereTriplet($fieldName, $action, $rvalue, $sep);
            }
                                                
            return $resultStr; 
        }


        private function WhereTriplet($fieldName, $action, $rvalue, $separator) {
            $isDigital = false;
            if ($fieldName{0} == '#') {
                $isDigital = true;
                $fieldName = substr($fieldName, 1);
            }
            
            if (isset($this->djem->cacheTables[$fieldName])) {
                if ($isDigital) {
                    $this->joinCacheTables['icache_' . $fieldName] = 1;
                } else {
                    $this->joinCacheTables['scache_' . $fieldName] = 1;
                }

                $sqlFieldName = "`$fieldName`";
            } else {
                if ($isDigital) {
                    $sqlFieldName = $this->djem->db->FieldName($fieldName) . '-0';
                } else {
                    $sqlFieldName = $this->djem->db->FieldName($fieldName);
                }
            }
            
            // Попробуем эффективно заменить все ? на реальные значения. Если значений не хватит - бросим исключульку
            $rvalueParts = explode('?', $rvalue);
            $rvalue = array_shift($rvalueParts); 
            while (count($rvalueParts)) {
                if (count($this->whereValues) == 0) {
                    throw new Exception('WHERE lacks parameters'); 
                }

                $rvalue .= array_shift($this->whereValues);
                $rvalue .= array_shift($rvalueParts);
            }
            
            if ($separator == ' ' && !is_numeric($rvalue)) {
            	$rvalue = $this->djem->db->FieldName($rvalue);
            } else {
            	$rvalue = '"' . $this->djem->db->Escape($rvalue) . '"';
            }
                    
            switch (strtolower(trim($action))) {
                case '=':
                case '==':
                    return $sqlFieldName . ' = ' . $rvalue;
                case '!=':
                    return $sqlFieldName . ' != ' . $rvalue;
                case '>':
                case 'gt':
                    return $sqlFieldName . ' > ' . $rvalue;
                case '<':
                case 'lt':
                    return $sqlFieldName . ' < ' . $rvalue;
                case '>=':
                case 'ge':
                    return $sqlFieldName . ' >= ' . $rvalue;
                case '<=':
                case 'le':
                    return $sqlFieldName . ' <= ' . $rvalue;
                    
                case 'like':
                    if (strpos($rvalue, '%') === false) {
                        return 'INSTR(' . $sqlFieldName . ', ' . $rvalue . ')';
                    } else {
                        return $sqlFieldName . ' LIKE ' . $rvalue;
                    }
                    
                case 'in':
                	if (preg_match('#^"([0-9,]+)"$#', $rvalue, $m)) {
                        return $sqlFieldName . ' IN (' . $m[1]  . ')';
                    } else {
                    	return 'FIND_IN_SET(' . $sqlFieldName . ', ' . $rvalue . ')';
                    }

                case 'has':
                	if (isset($this->djem->tagTables[$fieldName])) {
                		$this->joinTagTables[$fieldName] = true;
                		return '`tl:' . $fieldName . '`.taglink_word = ' . $rvalue;
                	} else {
                    	return 'FIND_IN_SET(' . $rvalue . ', ' . $sqlFieldName . ')';
                    }
            }
            
            return '';
        }


        static function IsStrange($symbol) {
            switch ($symbol) {
                case '(':
                case ')':
                case '<':
                case '>':
                case '!':
                case '=':
                case ' ':
                    return true;
                default: 
                    return false;
            }       
        }



        function Path($path) {
            $result = array();

            foreach (explode(',', $path) as $pathItem) {
                $pathItem = trim($pathItem);
                preg_match("/^(.+?)(\\.\\$|\\.\\*)*$/", $pathItem, $m);

				if (empty($m[2])) {
                    if($m[1] == '*') {
                        continue;
                    } elseif($m[1] == '$') {
                        $result[] = "INSTR(document_path, '.') = 0";
                    } elseif(is_numeric($m[1])) {
                        $result[] = '`document_id`="' . $this->djem->db->Escape($pathItem) . '"';
                    } else {
                        $result[] = '`document_path`="' . $this->djem->db->Escape($pathItem) . '"';
                    }

                } else {
                    if ($m[2] == '.$') {
                        if (is_numeric($m[1])) {
                            $result[] = ' document_parent_id = "' . $m[1] . '" ';
                        } else {
                            $pathQuery = $this->djem->Query('SELECT `document_id` FROM `documents` WHERE `document_path`="?" AND `document_deleted`=0 LIMIT 1',  $m[1]);
                            if ($pathQuery->Fetch() == false) continue;
                            $result[] = ' document_parent_id = "' . $this->djem->db->Escape($pathQuery->document_id) . '" ';
                        }

                    } elseif ($m[2] == '.*') {
                        if (is_numeric($m[1])) {
                            $pathQuery = $this->djem->Query('SELECT `document_path` FROM `documents` WHERE `document_id`="?" AND `document_deleted`=0 LIMIT 1', $m[1]);
                            if ($pathQuery->Fetch() == false) continue;
                            $result[] = ' document_path LIKE "' . $this->djem->db->Escape($pathQuery->document_path) . '.%" ';
                            
                        } else {
                            $result[] = ' document_path LIKE "' . $this->djem->db->Escape($m[1]) . '.%" ';
                        }
                                            
                    } else {
                        continue;
                    }               
                }
            }

            if (count($result)) {
                $this->path = ' AND ('  . implode(' OR ', $result) . ') ';
            } else {
                $this->path = '';
            }
            
            $this->query = false;  // Сбрасываем результат выборки при изменении пути
                        
            return $this;
        }



        function Sort($sort) {
            $result = array();
            
            if ($sort) {
                foreach (explode(',', $sort) as $sortItem) {
                    $sortItem = trim($sortItem);

                    $sortOrder = ' ASC';
                    $isDigital = false;

                    while ($sortItem) {
                        if ($sortItem[0] == '#') {
                            $isDigital = true;
                            $sortItem = substr($sortItem, 1);
                            continue;
                        }
                        if ($sortItem[0] == '-') {
                            $sortOrder = ' DESC';
                            $sortItem = substr($sortItem, 1);
                            continue;
                        }
                        break;
                    }

                    if (isset($this->djem->cacheTables[$sortItem])) {
                        $result[] = '`' . $sortItem . '`' . $sortOrder;
                        if ($isDigital) {
                            $this->joinCacheTables['icache_' . $sortItem] = 1;
                        } else {
                            $this->joinCacheTables['scache_' . $sortItem] = 1;
                        }
                    } else {
                    	if ($sortItem == '_random') {
                    		$result[] = 'RAND()';
                    	} else {
                        	$result[] = $this->djem->db->FieldName($sortItem) . ($isDigital ? '-0' : '') . $sortOrder;
                        }
                    }
                }
            }

            if (count($result)) {
                $this->sort = ' ORDER BY '  . implode(',', $result) . ' ';
            } else {
                $this->sort = '';
            }
            
            return $this; 
        }


		function Join($table, $joinConditions) {
			$this->joinInnerTables[$table] = true;
			
			$this->whereValues = $this->djem->db->array_flatten(func_get_args());
			array_shift($this->whereValues);
			array_shift($this->whereValues);
			$resultStr = $this->WhereScanner($joinConditions);
			
			if ($resultStr) {				
				$this->innerWhere .= ' AND (' . $resultStr . ') ';
			}
			
			return $this;
		}


        function Limit($limit = false, $limitHowMuch = false) {
            $result = array();
            
            if ($limitHowMuch) {
            	$this->limit = ' LIMIT ' . intval($limit) . ', ' . intval($limitHowMuch);
            
            } else if ($limit) {            
	            foreach (explode(',', $limit) as $limitItem) {
	                $result[] = intval($limitItem);
	            }
	
	            if (count($result)) {
	                $this->limit = ' LIMIT ' . join(',' , $result); 
	            } else {
	                $this->limit = '';
	            }
	            
			} else {
				$this->limit = '';
			}
            
            return $this;
        }


        function Size() {
            $this->Execute();
            
            return $this->query->Size();
        }


        function Fetch() {
            $this->Execute();

            if ($this->query->Fetch() == false) {
            	$this->_doc = false;
            	return false;
            }
            
            $this->_doc = new DJEMDocument($this->djem, $this->query);
            
            return $this->_doc;
        }


        function Execute() {
            if ($this->query) return; 

            if ($this->fields) {
                $selectQuery = join(', ', array_keys($this->fields));
            } else {
                $selectQuery = '`documents`.*'; 
            }
            
            // Подключаем таблицы быстрого джойна, если таковые есть
            $joinStr = '';          
            foreach (array_keys($this->joinCacheTables) as $j) {
                $joinStr .= ' LEFT JOIN `' . $j . '` USING (document_id) ';
            }
            
            foreach (array_keys($this->joinTagTables) as $j) {
	        	$joinStr .= ' LEFT JOIN `taglink_' . $j . '` AS `tl:' . $j . '` ON `tl:' . $j . '`.taglink_document_id = `documents`.document_id ';
            }
            
            if (count($this->joinInnerTables)) {
            	$innerTables = implode(',', array_keys($this->joinInnerTables)) . ',';
            } else {
            	$innerTables = '';
            }

            $this->query = $this->djem->db->Query('SELECT ' . $selectQuery . ' FROM ' . $innerTables . '`documents` ' . $joinStr .
                                ' WHERE `document_deleted`=0 ' . 
                                $this->path . 
                                $this->where .
                                $this->innerWhere .
                                $this->typeWhere .
                                $this->sort . 
                                $this->limit);
        }


        function __get($key) {
            if ($this->_doc) {
                return $this->_doc->$key;
            }
            
            return null;
        }
        
    
    	
    	function ToArray() {    	
    		$args = func_get_args();    		
			$result = array();
			
			if (count($args) == 1) {
				if ($this->fields == false) $this->Fields($args[0]);
				foreach ($this as $f) {
	    			$result[] = $f->{$args[0]};
	    		}
	    		
			} else {
	    		foreach ($this as $f) {
	    			$result[$f->_id] = $f->_name;
	    		}
	    	}
    		
    		return $result;
    	}
    
    
        
        
        
        // Реализация интерфейса Iterator
        function rewind() {
        	$this->query = false;
        	return $this->Fetch();
        }
        
        function current() {
        	return $this->_doc;
        }
        
        function key() {
        	return $this->_doc->_id;
        }
        
        function next() {
        	return $this->Fetch();
        }
        
        function valid() {
        	return $this->_doc !== false; 
        }        
        
        // Реализация интерфейса Countable
        function count() {
        	return $this->Size();
        }
    
    }
    
    