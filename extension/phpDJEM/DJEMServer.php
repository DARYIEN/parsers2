<?php

	class DJEMServer {
		private $djem = false;
		private $serverPath = false; 
		private $userKey = false;
		private $rootId = 2;
		private $transport = 'exec'; // could be 'exec'
		
		function __construct($djem = false, $serverPath = false) {
			if ($djem) {
				$this->djem = $djem;
			} else {
				$this->djem = R('DJEM');
			}
			
			if ($serverPath) {
				$this->serverFile = $serverPath;
			} else {
				$cfg = R('Config');
				if ($cfg) {
					$this->serverFile = $cfg->djemserverPath;
				}
			}
		}
		
		
		function __destruct() {
			if ($this->userKey) {
				$this->UnregisterKey();
			}	
		}
		
		
		
		function PublishDocument($id, $dtplId = false, $outputName = false, $mode = false) {			
			if ($dtplId || $outputName || $mode) {			
				if ($mode == 'queue') {
					$this->djem->Query('INSERT INTO pq SET pq_order=UNIX_TIMESTAMP(), pq_document_id="?", pq_dtpl_id="?", pq_file="?", pq_user_id="2", pq_tstamp=UNIX_TIMESTAMP()', 
						$id, $dtplId, $outputName);
			
				} else {
					$p = array();
					$p['id'] = $id;
					
					$command = '<' . 'publish id="' . $id . '"';
					if ($dtplId) $command .= ' dtpl=' . $dtplId; 
					if ($outputName) $command .= ' file="' . str_replace('"', '', $outputName) . '"'; 
					if ($mode) $command .= ' mode=queue'; 
					$command .= '>';
					
					return $this->ExecuteCommand('djemscript', $p, $command);
				}
				
			} else {
		    	return $this->ExecuteCommand("publishDocument", array("id" => $id));
		    }
		}
		
		
		
		
		function ExecuteCommand($cmd, $args, $dataInput = false) {			
		    if (!$cmd) return false;
					
		    
		    if (!$this->serverFile) {
				$this->error = "No path to server specified";
				return false;
		    }
			
			if (!$this->userKey) {
		    	$this->RegisterKey();
		    }
		    
		    if ($this->transport == 'http') {
		    	list ($result, $status, $statusVerbose, $raw) = $this->HttpRequest($cmd, $args, $dataInput); 
		    	$output = $statusVerbose;
		    	
		    } else {		    
			    $serverPath = dirname($this->serverFile);			
			    $queryString = "cd \"$serverPath\" && ";
			    $queryString .= "/usr/bin/env REMOTE_ADDR=\"84.253.84.38\" " . $this->serverFile . ' ' . $cmd;
			    $queryString .= " userkey \"" . $this->userKey . "\"";
		
				if (!isset($args['nopack'])) {
					$args['nopack'] = 'true';
				}
			    foreach ($args as $key => $value) {
					$queryString .= " $key " . escapeshellarg($value); 
		    	}
	
			    if ($dataInput) {
					$output = $this->ExecuteWithSTDIN($queryString, $dataInput);
			    } else {		    
					exec($queryString, $out);
					$output = join("\n", $out);
					
					$dataStartPos = strpos($output, "\n\n" , strpos($output, "\n\n") + 2);
	
					if ($dataStartPos) {
						$output = substr($output, $dataStartPos + 2);
					}
			    }
			}
		    
		    return $output;
		}
	
	
		function ExecuteWithSTDIN($cmd, $stdin) {
			$descriptorspec = array(
	    		0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
				1 => array("pipe", "w")
		    );
	
		    // $stdin = "Content-length: " . strlen($stdin) . "\n\n" . $stdin;
		    $stdout = '';
		    $process = proc_open($cmd, $descriptorspec, $pipes); //, $cwd, $env);
	
	
		    if (is_resource($process)) {
				fwrite($pipes[0], $stdin);
				fclose($pipes[0]);
	
				while ($str = fgets($pipes[1])) {
					$stdout .= $str;
				}
				
				fclose($pipes[1]);	
				$return_value = proc_close($process);
				
				$dataStartPos = strpos($stdout, "\n\n" , strpos($stdout, "\n\n") + 2);

				if ($dataStartPos) {
					$stdout = substr($stdout, $dataStartPos + 2);
				}
		    }
	
		    return $stdout;
		}
	
		
		function HttpRequest($dataCmd, $args, $dataInput) {
				
	        $data = '';
	        $errno = '';
	        $errstr = '';
	        $serverUrl = '/cgi-bin/djem/djemserver';
	        
	        $serverHost = preg_replace('#http://([^/]+)(/.*)?$#', '$1', R('Config')->httpHost);
	        
	        $fp = fsockopen($serverHost, 80, $errno, $errstr, 60);
	        
	        $requestHeader = 'POST '. $serverUrl . " HTTP/1.0\r\nHost: " . $serverHost . "\r\n"; 
	        
	        $requestData = 'userkey=' . $this->userKey . "\n";
	        $requestData .= 'action=' . $dataCmd . "\n";
	        
	        foreach ($args as $key => $value) {
	        	$requestData .= $key . '=' . $value . "\n";
	        }
	        
	        $requestHeader .= 'Content-length: ' . strlen($requestData) . "\r\n"; 
	        $requestHeader .= "\r\n" . $requestData;

	        if ($fp) {
	            fwrite($fp, $requestHeader);
	            
	            while (!feof($fp)) {
	                $data .= fread($fp, 256);
	            }
	            
	            fclose($fp);
	        }
	        
	        $status = 0;
	        $statusVerbose = ''; 
	        $result = '';
	        
	        if (preg_match('#^status=(.+)#m', $data, $m)) {
	        	$status = $m[1];
	        }
	        
	        if (preg_match('#^status-verbose=(.+)#m', $data, $m)) {
	        	$statusVerbose = $m[1];
	        }
	        
	        return array($result, $status, $statusVerbose, $data);
	    }
	
		function RegisterKey() {
		    $this->UnregisterKey();
	
		    $this->userKey = md5(uniqid(time()));
		    $this->djem->Query('INSERT INTO `userkeys` SET
					`userkey_user_id` = "?",
					`userkey_key` = "?",
					`userkey_ip` = "84.253.84.38",
					`userkey_tstamp` = UNIX_TIMESTAMP()',
			    $this->rootId, $this->userKey);
		}
	
	
		function UnregisterKey() {
		    if ($this->userKey) {
				$this->djem->Query(' DELETE FROM `userkeys` WHERE `userkey_key`="?" AND `userkey_ip`="84.253.84.38"',
			    	$this->userKey);
				$this->userKey = false;
		    }
		}
		
		
		
		
	}
	