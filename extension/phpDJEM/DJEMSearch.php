<?php

	class DJEMSearch {
		private $djem = false;
		private $words = false;
		private $fields = false;
		
		function __construct($djem = false) {
			if ($djem) {
				$this->djem = $djem;
			} else {
				$this->djem = R('DJEM');
			}
		}
		
		function ParseDocument($doc) {
			if (!($doc instanceof DJEMDocument)) {
				try {
					$doc = $this->djem->Load($doc);
					
				} catch (Exception $e) {
					return false;
				}
			}

			$this->LoadWords();
			$this->LoadFields();
			$this->djem->Query('DELETE FROM djem_srch WHERE djem_srch_doc_id="?"', $doc->_id);

			foreach ($doc as $key => $value) {
				if ($key{0} == '_') {
					if ($key != '_name') continue;
				}

				if (empty($value)) continue;
				
				if (isset($this->fields[$key])) {
					$fieldId = $this->fields[$key];
				} else {
					$this->djem->Query('INSERT INTO djem_fields SET djem_field_name="?"', $key); 
					$fieldId = $this->djem->db->LastInsertId();						
					$this->fields[$key] = $fieldId;
				}			
				
				foreach (preg_split('#[,.!?() \r\n\t%\#*\[\]]+#si', strip_tags($value)) as $w) {
					if (strlen($w) < 3) continue;
										
					$stem = mb_convert_case(stem_russian_unicode($w), MB_CASE_LOWER, "UTF-8");
					if (isset($this->words[$stem])) {
						$wordId = $this->words[$stem];
					} else {
						$this->djem->Query('INSERT INTO djem_words SET djem_word_text="?"', $stem); 
						$wordId = $this->djem->db->LastInsertId();						
						$this->words[$key] = $wordId;
					}
					
					$this->djem->Query('INSERT DELAYED INTO djem_srch SET djem_srch_word_id="?", djem_srch_field_id="?", djem_srch_doc_id="?"', 
						$wordId, $fieldId, $doc->_id);
				}
			}			
						
		}
			
			
		function LoadWords() {
			if ($this->words !== false) return;
			
			$words = array();
			$query = $this->djem->Query('SELECT * FROM djem_words');
			foreach ($query as $q) {
				$words[$q['djem_word_text']] = $q['djem_word_id']; 
			}			
			
			$this->words = $words;
		}
		
		function LoadFields() {
			if ($this->fields !== false) return;
			
			$fields = array();
			$query = $this->djem->Query('SELECT * FROM djem_fields');
			foreach ($query as $q) {
				$fields[$q['djem_field_name']] = $q['djem_field_id']; 
			}			
			
			$this->fields = $fields;
		}
	}
	