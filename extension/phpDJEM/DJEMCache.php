<?php
	class DJEMCache {			
		private $memCache;
	
		function __construct($cacheServer = false, $cachePort = false) {
			$this->memCache = new MemCache();
			
			if (!$cacheServer) $cacheServer = 'localhost'; 
			if ($cacheServer instanceof DJEM) $cacheServer = 'localhost';
			if (!$cachePort) $cachePort = 12366;
			
			$cacheServer = 'unix:///tmp/memcached_velorama';
			$cachePort = 0;
			
			if ($cacheServer && $cachePort) {			
				$this->memCache->pconnect($cacheServer, $cachePort);
			}
		}
	
		function Get($key, $resourceType = false) {
			return $this->memCache->get($this->FormKey($key, $resourceType)); 
		}
		
		function Set($key, $value, $resourceType = false, $expire = 0) {
			return $this->memCache->set($this->FormKey($key, $resourceType), $value, false, $expire); 
		}
		
		function Delete($key, $resourceType = false) {
			return $this->memCache->delete($this->FormKey($key, $resourceType)); 
		}
		
		function FormKey($key, $resourceType) {
			if ($resourceType) {
				return $resourceType . '::' . $key;				
			} else {
				return $key;
			} 
		}
		
	}