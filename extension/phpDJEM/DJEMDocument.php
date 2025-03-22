<?php

    require_once('DJEMXml.php');

    class DJEMDocument implements Iterator {
        protected $djem;
        public $__xml;
        protected $systemFields; 
        protected $keys = false;       	
        protected $documentId; 
        protected $serverMode = 'doctypes'; // doctypes для серверов DJEM версии 2.0, с типами документов    
        private $changedFields = array();
        private $isFolder = null;
        public $error; 

        const TYPE_FOLDER = 0;
        const TYPE_DOCUMENT = 1;

        function __construct($djem = NULL, $data = false, $fields = false) {        	
        	$this->djem = $djem === NULL ? R('DJEM') : $djem;
            $this->documentId = false;
            $this->__xml = new DJEMXml('<root></root>');
            $this->systemFields = array();

			if ($data) {
	            if ($data instanceof DJEMStatement) {
	                $this->LoadFromSQL($data);
	            } else {
	                $this->Load($data, $fields);
	            } 
			}
        }


        protected function LoadFromSQL($data) {
            if ($data->document_content) {
                $this->__xml = new DJEMXml($data->document_content); 
            } else {
                $this->__xml = null;
            }
                            
            foreach ($data->Keys() as $key) {
                if ($key == 'document_content') continue;
                if ($key == 'document_id') {
                    $this->documentId = $data->$key;
                }
            
                $xmlKey = substr($key, 8);
                if ($xmlKey) {
                	$this->systemFields[$xmlKey] = $data->$key;
                }
            }
        }


        function Load($documentId, $fields = false) {
        	
        	if ($fields) {
        		$selectFields = array('`document_id`' => true);
        		foreach ($fields as $f) {
        			if ($f{0} == '_') {
        				$selectFields['`document' . $this->djem->db->Escape($f) . '`'] = true;
        			} else {
        				$selectFields['`document_content`'] = true;
        			}
        		}
        		
        		$whatToSelect = join(', ', array_keys($selectFields));
        	} else {
        		$whatToSelect = '*';
        	}
 
 			if (is_object($documentId)) $documentId = (string) $documentId;       	
            if (is_numeric($documentId)) {
                $dsth = $this->djem->Query('SELECT ' . $whatToSelect . ' FROM documents WHERE document_id="?"', $documentId); 
            } else { 
                $dsth = $this->djem->Query('SELECT ' . $whatToSelect . ' FROM documents WHERE document_path="?"', $documentId); // Значит, это был путь
            }
            
            if ($dsth->Fetch()) {
                $this->LoadFromSQL($dsth);
            } else {
                throw new Exception('Unable to load document ' . $documentId);
            }
        }
    
    
    	private function StoreTags($field, $value) {
    		$this->djem->Query('DELETE FROM `taglink_?` WHERE taglink_document_id="?"', $field, $this->documentId);

			foreach (explode(',', $value) as $tag) {
				$tag = trim($tag);
				
				$query = $this->djem->Query('SELECT tagdict_id FROM tagdict WHERE tagdict_word="?"', $tag);
				if ($query->Fetch()) {
					$tagId = $query->tagdict_id;
				} else {
					$query = $this->djem->Query('INSERT INTO tagdict SET tagdict_word="?"', $tag);
					$tagId = $this->djem->db->LastInsertId();
				}
				
				$this->djem->Query('REPLACE `taglink_?` SET taglink_document_id="?", taglink_word_id="?", taglink_word="?"', $field, $this->documentId, $tagId, $tag);
			}
    	}
    
    
        function Store($folderId = false) {
        	$this->djem->db->Begin();
                
            $updateContent = false;

            if ($this->documentId == false) {
            	$updateContent = true;
            	if ($this->serverMode == 'doctypes') {
            		$this->InsertWithTypes($folderId); 
            	} else {
            		$this->InsertNoTypes($folderId); 
            	}
            }
           
            $queryFields = array();
            $queryValues = array();
            
            $cacheTables = $this->djem->GetCacheTables();
            $tagTables = $this->djem->tagTables;
            
            foreach (array_keys($this->changedFields) as $key) {
                if ($key{0} == '_') {
                    $queryFields[] = '`document' . $key . '`="?"';
                    $queryValues[] = $this->systemFields[$key];
                } else {
                    $updateContent = true;
                    if (isset($cacheTables[$key])) {
                    	$value = $this->__get($key);
                    	$this->djem->Query('REPLACE `scache_?` SET document_id="?", `?`="?"', $key, $this->documentId, $key, $value);                    	
                    	$this->djem->Query('REPLACE `icache_?` SET document_id="?", `?`="?"', $key, $this->documentId, $key, $value);
                    }
                    if (isset($tagTables[$key])) {
                    	$this->StoreTags($key, $this->__get($key));
                    }
                }
            }
            
            if ($updateContent) {
                $queryFields[] = '`document_content`="?"';
                $queryValues[] = $this->__xml->Write();
            }
            
            if (count($queryFields)) {
            	$this->djem->Query('UPDATE documents SET ' . join(', ', $queryFields) . ' WHERE document_id="?"', $queryValues, $this->documentId);
                $this->changedFields = array();
            }

            $this->djem->db->Commit();
            return $this->documentId; 
        }
    
    

        private function InsertNoTypes($folderId) {
            if ($folderId == false) $folderId = $this->_parent_id;
            if ($folderId == false) {
                    throw new Exception('No folder id');
            }
            
            $parent = new DJEMDocument($this->djem, $folderId);
            $this->SetIfNull('_create_time', time());
            $this->SetIfNull('_name', 'New document');
            $this->SetIfNull('_ctpl', '<root></root>');
            $this->SetIfNull('_dtpl', '');
            $this->SetIfNull('_creator_id', $parent->_creator_id);
            $this->SetIfNull('_level', $parent->_level + 1);
            
            if ($this->_type !== self::TYPE_FOLDER) {
                $this->_type = self::TYPE_DOCUMENT;
            }
            

            if ($this->djem->Query('INSERT INTO documents SET document_parent_id="?", document_type="?"', $folderId, $this->_type) !== true) {
                throw new Exception('Unable to insert document');
            }
            
            $this->documentId = $this->djem->db->LastInsertId();
            $this->systemFields['_id'] = $this->documentId;
            $this->SetIfNull('_code', $this->documentId);
            
            if ($this->_type === self::TYPE_FOLDER) {
                // Скопируем настройки из отеческого документа
                foreach ($parent->Keys() as $key => $value) {
                    if ($key{0} == '_' && $key{1} == '_') {
                        $this->{'$' . substr($key, 2)} = (string) $value;
                    }
                }

                $this->SetIfNull('_url', preg_replace('/(.+)\/.*/', '$1', $parent->_url) . '/' . $this->documentId . '/index.phtml');
                $this->SetIfNull('_ctpl_id', $parent->{'$folder_ctpl_id'});
                $this->SetIfNull('_dtpl_id', $parent->{'$folder_dtpl_id'});
                $this->SetIfNull('_codepage', $parent->{'$folder_codepage'});
                $this->SetIfNull('_publish_points', $parent->{'$folder_publish_points'});
                $this->SetIfNull('_grid_id', $parent->_grid_id);
                $this->_grid_path = $this->documentId . '.$';

                $asth = $this->djem->Query('SELECT * FROM `acls` WHERE `acl_document_id`="?"', $folderId);

                while ($asth->Fetch()) {
                    $this->djem->Query('INSERT INTO `acls` SET `acl_user_id`="?", `acl_document_id`="?", `acl_access`="?"',
                                    $asth->acl_user_id, $this->documentId, $asth->acl_access);
                }

                $this->djem->Query('UPDATE documents SET document_sub_folders=document_sub_folders + 1 WHERE document_id="?"', $parent->_id);

            } else {
                $this->SetIfNull('_url', preg_replace('/(.+)\/.*/', '$1', $parent->_url) . '/document' . $this->documentId . '.phtml');
                $this->SetIfNull('_ctpl_id', $parent->{'$document_ctpl_id'});
                $this->SetIfNull('_dtpl_id', $parent->{'$document_dtpl_id'});
                $this->SetIfNull('_codepage', $parent->{'$document_codepage'});
                $this->SetIfNull('_publish_points', $parent->{'$document_publish_points'});

                $this->djem->Query('UPDATE documents SET document_sub_documents=document_sub_documents + 1 WHERE document_id="?"', $parent->_id);
            }

            $this->SetIfNull('_file', $this->_url);
            $this->_path = $parent->_path . '.' . $this->_code;
            $this->_sort = $this->documentId;
            $this->_gsort = $parent->_gsort . DJEM::Base36($this->_sort);
        }



		private function InsertWithTypes($folderId) {
			if ($folderId == false) $folderId = $this->_parent_id;
            if ($folderId == false) {
                    throw new Exception('No folder id');
            }
            
            
            $parent = $this->djem->Load($folderId);
            $this->SetIfNull('_create_time', time());
            $this->SetIfNull('_name', 'New document');
            $this->SetIfNull('_ctpl', '<root></root>');
            $this->SetIfNull('_dtpl', '');
            $this->SetIfNull('_creator_id', $parent->_creator_id);
            $this->SetIfNull('_level', $parent->_level + 1);
                        
            $this->SetIfNull('_ctpl_id', 1); // "1" значит "взять значение у типа документа"
            $this->SetIfNull('_dtpl_id', 1);
            $this->SetIfNull('_image', 1);
            $this->SetIfNull('_codepage', 1);
            
            
            $this->FixDocumentType($parent);
            $this->CheckForSameCode(); 


            if ($this->djem->Query('INSERT INTO documents SET document_parent_id="?", document_type="?"', $folderId, $this->_type) !== true) {
                throw new Exception('Unable to insert document');
            }
            
            $this->documentId = $this->djem->db->LastInsertId();
            $this->systemFields['_id'] = $this->documentId;
            $this->SetIfNull('_code', $this->documentId);
                        
                       
            $isFolder = $this->IsFolder(); 
            if ($isFolder) {
            	$this->djem->Query('UPDATE documents SET document_sub_folders=document_sub_folders + 1 WHERE document_id="?"', $parent->_id);
            	$asth = $this->djem->Query('SELECT * FROM `acls` WHERE `acl_document_id`="?"', $folderId); // Shame insert into.. select doesn't for the same table

                while ($asth->Fetch()) {
                    $this->djem->Query('INSERT INTO `acls` SET `acl_user_id`="?", `acl_document_id`="?", `acl_access`="?"',
                                    $asth->acl_user_id, $this->documentId, $asth->acl_access & 0xFFFFFFF);
                }

            	$this->SetIfNull('_url', preg_replace('/(.+)\/.*/', '$1', $parent->_url) . '/' . $this->documentId . '/index.phtml');

            } else {
            	$this->SetIfNull('_url', preg_replace('/(.+)\/.*/', '$1', $parent->_url) . '/document' . $this->documentId . '.phtml');
            	$this->djem->Query('UPDATE documents SET document_sub_documents=document_sub_documents + 1 WHERE document_id="?"', $parent->_id);
            }
            
            $this->SetIfNull('_file', $this->_url);
            
            if (empty($this->_default_subtype)) {
            	if (!empty($this->_subtypes)) {
            		$this->_default_subtype = intval($this->_subtypes);            		
            	}
            }            	            

            $this->_path = $parent->_path . '.' . $this->_code;
            $this->_sort = $this->documentId;
            $this->_gsort = $parent->_gsort . DJEM::Base36($this->_sort);
        }

		
		
		private function FixDocumentType($parent) {		
			if (isset($this->_type)) {
	           	$typeDoc = $this->djem->Load($this->_type);
	           	if (strpos($typeDoc->_path, 'settings.types.') !== 0) {
	           		throw new Exception('Illegal type');
	           	}
            
            } else {
            	if ($parent->_default_subtype) {
            		$this->_type = $parent->_default_subtype; 
            		
            	} else if ($parent->_subtypes) {
            		$this->_type = intval($parent->_subtypes);
            		
            	} else {
            		$parentTypeDoc = new DJEMDocument($this->djem, $parent->_type);
            		if ($parentTypeDoc->_default_subtype) {
            			$this->_type = $parentTypeDoc->_default_subtype;
            			
            		} else if ($parentTypeDoc->_subtypes) {
            			$this->_type = intval($parentTypeDoc->_subtypes);
            		}
            	}
            	
            	if (!isset($this->_type)) {  // Ежели все еще пустой - то пусть будет весь в отца
            		$this->_type = $parent->_type;
            	}            	
            }
		}

		
		
		private function CheckForSameCode() {
			if (isset($this->_code)) {
            	// Проверим, нет ли на этом уровне документа с таким же кодом
            	$query = $this->djem->Query('SELECT document_id FROM documents WHERE document_parent_id="?" AND document_code="?"', $this->_parent_id, $this->_code); 
            	if ($query->Size()) {
            		throw new Exception('Document code already exists');
            	}
            }
		}


		function IsFolder() {
			if ($this->isFolder !== null) {
				return $this->isFolder;
			}
			
			$query = $this->djem->Query('SELECT d.document_sub_documents + d.document_sub_folders AS document_kids, t.document_subtypes, t.document_subtypes AS type_subtypes
										 FROM documents AS d, documents AS t 
										 WHERE d.document_id="?" AND t.document_id = d.document_type', 
									
										$this->documentId);
	
			if ($q = $query->Fetch()) {
				$this->isFolder = false;

				if ($q['document_kids']) $this->isFolder = true;
				if ($q['document_subtypes'] || $this->_subtypes) $this->isFolder = true;
				if ($q['type_subtypes']) $this->isFolder = true;
			}
			
			return $this->isFolder;
		}


    
        function Set($key, $value) {
            $this->__set($key, $value);
            
            return $this;
        }

        function Keys() {
            return $this->__xml->Keys();
        }    
    


        function __get($key) {
            if ($key{0} == '_') {
                return isset($this->systemFields[$key]) ? $this->systemFields[$key] : '';
                                
            } elseif ($this->__xml) {
                return $this->__xml->$key; 
            } 
            
            return false;
        }           


        private function SetIfNull($key, $value) {
            if (isset($this->$key) === false) {
                $this->$key = $value;
            }
        }


        function __isset($key) {
            if ($key{0} == '_') {
                return isset($this->systemFields[$key]);
            } else {
                return isset($this->__xml->$key);
            }
        }


        function __set($key, $value) {
            if ($key{0} == '_') {
                $this->systemFields[$key] = $value;
            } else {
                $this->__xml->$key = $value;
            }

            $this->changedFields[$key] = true;
        }
        
        
        function __toString() {
        	return $this->systemFields['_id'];
        }
		
		
		// Реализация интерфейса Iterator
        function rewind() {
        	if ($this->keys == false) {
        		$this->keys = array_keys($this->systemFields); 
        		foreach ($this->__xml->Keys() as $key => $value) {
        			$this->keys[] = $key;
        		}
        	}
        	        	
        	reset($this->keys);
        	return current($this->keys);
        }
        
        function current() {
        	return $this->{current($this->keys)}; // $this->_doc;
        }
        
        function key() {
        	return current($this->keys);
        }
        
        function next() {
        	return next($this->keys); // $this->Fetch();
        }
        
        function valid() {
        	return current($this->keys) !== false;
        }
    }
    
    
    