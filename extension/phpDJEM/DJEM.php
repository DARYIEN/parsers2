<?php
    // require_once('DJEMDatabase.php');
    // require_once('DJEMDocument.php');
    // require_once('DJEMForeach.php');

    class DJEM {    
        public $db; 
        private $value;
        public $cacheTables = false;
        public $tagTables = false;
        private $documents = array();
    
        function __construct($host = false, $base = false, $user = false, $password = false) {
        	if ($host) {
        		$this->db = new DJEMDatabase($host, $base, $user, $password);

        	} else {
        		if ($c = R('Config')) {
        			$this->db = new DJEMDatabase($c->sqlHost, $c->sqlBase, $c->sqlUser, $c->sqlPassword);
        		} else {
        			$this->db = false;
        		}
        	}
        }

		function Parent($id, $level = false) {
		
			$parent_id = $id;
			
      		$document = $this->Load($id);
    		$parent_id = $document->_parent_id;
      		if ($level !== false) {
      			$path = $document->_path;
      			$pos = 0;
      			for ($i = 0; $i <= $level; ++$i) {
      				$poss = strpos($path, '.', $pos + 1);
      				if ($poss === false) {
      					$pos = strlen($path);
      					break;
      				}
      				$pos = $poss;
      			}
				$path = substr($path, 0, $pos);
      			$document = $this->Load($path);
      			$parent_id = $document->_id;
      		}
   			return $this->Load($parent_id);
		}

        function Load($documentId, $fields = false) {
            if (!isset($this->documents[$documentId])) {
        		$this->documents[$documentId] = new DJEMDocument($this, $documentId, $fields);
        	}

            return $this->documents[$documentId];
        }


        function GetForeach() {
            return new DJEMForeach($this);
        }

    
        function Query() {        	
            $arguments = func_get_args();
            $query = array_shift($arguments);
            
            return $this->db->Query($query, $arguments);
        }


        static function Base36($int) {
            $res = '0000';

            if ($int < 0 || $int > 1679615) return $res;
            $letters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

            for ($i=3; $int > 0; --$i) {
                $res{$i} = $letters{ $int % 36 };
                $int = floor( $int / 36 );
            }

            return $res;
        }    
                
        
        function GetCacheTables() {
    		if ($this->cacheTables !== false) return $this->cacheTables;
    		
    		$this->cacheTables = array();		
    		$this->tagTables = array();
    		
    		$msth = $this->db->Query('SHOW TABLES'); 
    		
    		foreach ($msth as $r) {
    			if (substr($r[0], 0, 7) == 'scache_') {
    				$this->cacheTables[substr($r[0], 7)] = true;
    			} else if (substr($r[0], 0, 8) == 'taglink_') {
    				$this->tagTables[substr($r[0], 8)] = true;
    			}
    		}
    		
    		return $this->cacheTables;
    	}
    }