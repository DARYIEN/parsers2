<?php
	Class Tags {
		
		private $djem;
		private $tagid = 0;
		private $limit = "0,10";
		private $tag = "";
		private $tableSuffix = "tags";
		private $fields = array('_name', '_url', '_publish_time');
		
		function __construct($djem) {
			if($djem) {
				$this->djem = $djem;
			} else {
				$this->djem = R('DJEM');
			}
		}
		
		function Limit($a = false, $b = false) {
			if($b) {
				$this->limit = $a . "," . $b;
			} else {
				$this->limit = $a;
			}
			return $this;
		}
		
		function Fields($fields = array()) {
			$this->fields = array_unique($fields);
			return $this;
		}
		
		function Tag($tag) {
			$this->tag = $tag;
			return $this;
		}
		
		function TableSuffix($tableSuffix) {
			$this->tableSuffix = $tableSuffix;
			return $this;
		}
		
		function Get() {
			if($this->tag) {
				$result = $this->djem->Query("SELECT * FROM tagdict WHERE tagdict_word='?'", urldecode($this->tag));
				while($result->Fetch()) {
					$this->tagid = $result->tagdict_id;
				}
				$joins = array();
				$sql = " SELECT 
							d.document_id as _id";
				$sql_parameters = array();
				if(($key = array_search('_id', $this->fields)) !== false) {
					unset($this->fields[$key]);
				}
				for($i=0; $i<count($this->fields); $i++) {
					$field = $this->fields[$i];
					$sql .= " , ";
					if(strpos($field, '->') !== false) {
						$field_parent = explode('->', $field);
						if(!isset($joins[$field_parent[0]])) {
							$joins[$field_parent[0]] = "LEFT JOIN documents as " . $field_parent[0] . " ON d.document_parent_id = " . $field_parent[0] . ".document_id";
						}
						if($field_parent[1]{0} == "_") {
							$sql .= " " . $field_parent[0] . ".document" . $field_parent[1] . " as `" . $field . "`";
						} else {
							$sql .= $this->djem->db->FieldName($field_parent[1], $field_parent[0]) . " as `" . $field . "`";
						}
					} else {
						if($field{0} == "_") {
							$sql .= " d.document" . $field . " as " . $field;
						} else {
							$sql .= $this->djem->db->FieldName($field, "d") . " as " . $field;
						}
					}
				}
				$sql .= "	FROM
								taglink_?
							LEFT JOIN documents as d ON taglink_?.taglink_document_id = d.document_id
							" . implode(" \n", $joins) . "
							WHERE
								taglink_word_id='?'
								AND d.document_deleted = 0
								AND d.document_id IS NOT NULL
							ORDER BY d.document_publish_time DESC
							LIMIT ?";
				$sql_parameters[] = $this->tableSuffix;
				$sql_parameters[] = $this->tableSuffix;
				$sql_parameters[] = $this->tagid;
				$sql_parameters[] = $this->limit;
				
				if($this->tagid) {
					return $result = $this->djem->Query($sql, $sql_parameters);
				}
			}
			return false;
		}
		
		function Size() {
			if($this->tagid) {
				$result = $this->djem->Query("	SELECT 
													count(*) as cnt
												FROM
													taglink_?
												LEFT JOIN documents ON taglink_?.taglink_document_id = documents.document_id
												WHERE
													taglink_word_id='?'
													AND documents.document_deleted = 0
													AND documents.document_id IS NOT NULL",
												
												$this->tableSuffix, 
												$this->tableSuffix, 
												$this->tagid);
				if($result->Fetch()) {
					return $result->cnt;
				}
			}
			return 0;
		}
		
		
		function GetTagsList() {
			$result = $this->djem->Query("	SELECT
												tagdict.tagdict_id,
												tagdict.tagdict_word,
												COUNT(tagdict.tagdict_id) as cnt
											FROM 
												tagdict
											LEFT JOIN taglink_tags ON tagdict.tagdict_id = taglink_?.taglink_word_id
											WHERE
     											taglink_document_id IS NOT NULL
											GROUP BY tagdict.tagdict_id
     										" . ($this->limit ? " LIMIT ?" : ""),
											$this->tableSuffix,
											$this->limit);
			if($result) {
				return $result;
			}
			return false;
		}
	}
?>