<?php
    // MySQL edition

    class DJEMDatabase {
        private $isConnected; 
        private $baseType; 
        private $dbh; 

        private $host;
        private $base;
        private $user;
        private $password;

        private $transactionCounter; 

            
        function __construct($host = false, $base = false, $user = false, $password = false) {
            $this->isConnected = false;
            
            $this->host = $host;
            $this->base = $base;
            $this->user = $user;
            $this->password = $password;
            $this->transactionCounter = 0; 
        } 


        function __destruct() {
            if ($this->isConnected) {
                mysql_close($this->dbh);
                $this->isConnected = false;
            }
        }


        function Connect($host = false, $base = false, $user = false, $password = false) {
        	if ($this->isConnected) return;
            
            if ($host) $this->host = $host;
            if ($base) $this->base = $base;
            if ($user) $this->user = $user;
            if ($password) $this->password = $password;
			
			$this->dbh = @mysql_connect($this->host, $this->user, $this->password);             
            if ($this->dbh === false) {
                throw new Exception('Unable to connect: ' . mysql_error());
            }
            
            if (mysql_select_db($this->base, $this->dbh) == false) {
                throw new Exception('Unable to select base: ' . mysql_error($this->dbh));
            }
            
            $this->isConnected = true;
            $this->Query('/*!40101 SET names utf8 */');
        }
    

        function LastInsertId() {
        	return mysql_insert_id($this->dbh);
        }



        function Query($query) {
        	$args = self::array_flatten(func_get_args());
            array_shift($args);
            
            $queryParts = explode('?', $query);
            $queryStr = array_shift($queryParts);
                    
            while (count($queryParts)) {
                if (count($args) == 0) {
                    throw new Exception('Query lacks parameters');
                }

                $value = array_shift($args);
                $queryStr .= mysql_real_escape_string($value) . array_shift($queryParts);
            }

			$this->Connect();
            $result = mysql_query($queryStr, $this->dbh);

            if ($result == false) {
                throw new Exception('Bad query: ' . mysql_error($this->dbh));

            } elseif ($result === true) {
                return true;        
            } 
            
            return new DJEMStatement($result);
        }   


        function Escape($str) {
            return mysql_real_escape_string($str);
        }


        function FieldName($fieldName, $tableName = 'documents') {
            if ($fieldName[0] == '_') {
                return '`document' . $fieldName . '`';
            }
            
            if (strpos($fieldName, '.') !== false) {
            	return $fieldName; 
            }
            
            $fieldName = $this->Escape($fieldName);
            return "IF(LOCATE('<" . $fieldName . ">', `$tableName`.document_content), SUBSTRING_INDEX(SUBSTRING_INDEX(`$tableName`.document_content, '<".$fieldName.">', -1), '</".$fieldName.">', 1), '')";
        }



        function Begin() {
        	if ($this->transactionCounter == 0) {
                $this->Query('BEGIN');
            }
            ++$this->transactionCounter;
        }


        function Commit() {
            if ($this->transactionCounter) {
                --$this->transactionCounter;
                if ($this->transactionCounter == 0) {
                    $this->Query('COMMIT');
                }
            } 
        }


        function Rollback() {
            if ($this->transactionCounter) {
                $this->Query('ROLLBACK');
                $this->transactionCounter = 0;
            } 
        }




        static function array_flatten($array) {
            $flatArray = array();
            foreach ($array as $subElement) {
                if (is_array($subElement)) {
                    $flatArray = array_merge($flatArray, self::array_flatten($subElement));
                } else {
                    $flatArray[] = $subElement;
                }
            }
                    
            return $flatArray;
        }
    }
    
    
    class DJEMStatement implements Iterator {
        private $_statement; 
        private $_row; 
        private $_count;

        function __construct($statement) {
            $this->_statement = $statement;
            $this->_row = null;
        }

        function __destruct() {
            mysql_free_result($this->_statement);
        }

        function Size() {
            return mysql_num_rows($this->_statement);
        }

        function Fetch() {
            $this->_row = mysql_fetch_array($this->_statement);
            
            return $this->_row;
        }

        function Keys() {
            return array_keys($this->_row);
        }

        function __get($field) {
        	if ($this->_row === null) {
        		$this->Fetch();
        	}

            return empty($this->_row[$field]) ? '' : $this->_row[$field];
        }
        
        
        
        
        // Реализация интерфейса Iterator
        function rewind() {
        	$this->_count = 0;
        	return $this->Fetch();        	
        }
        
        function current() {
        	return $this->_row;
        }
        
        function key() {
        	return $this->_count;
        }
        
        function next() {
        	++$this->_count;
        	return $this->Fetch();
        }
        
        function valid() {
        	return $this->_row !== false;
        }
        
    }