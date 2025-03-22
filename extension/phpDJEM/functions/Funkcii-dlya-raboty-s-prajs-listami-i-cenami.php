<?php
	function createPriceItem($priceItem) {        	    	    	    
        $newDoc = new DJEMDocument($djem);
        
        $newDoc->_parent_id = 146821;
        $newDoc->_type = 997;
        $newDoc->_name = $priceItem['subCategoryName'];
        $newDoc->size = $priceItem['length'];
        $newDoc->steel = $priceItem['steel'];
        $newDoc->wholesale = $priceItem['wholesale'] ? $priceItem['wholesale'] : 99999999999;
        $newDoc->retail =  $priceItem['retail'] ? $priceItem['retail'] : 99999999999;        
        $newDoc->Notes = $priceItem['note'];
        $newDoc->Ghost = $priceItem['GOST'];
        $newDoc->fit1 = $priceItem['type'] ? $priceItem['type'] : 'non';
        $newDoc->fit2 = $priceItem['size'];
        $newDoc->fit3 = $priceItem['subCategoryName'];
        $newDoc->typ_price = $priceItem['categoryName'];
        $newDoc->city = $priceItem['cityId'];        
        
        $newDoc->userId = $priceItem['userId'];
        $newDoc->categoryId = $priceItem['categoryId'];
        	            
        $newDocId = $newDoc->Store();	        	        	                  	        
        	        
       	if($priceItem['type']) {
       		updateTypeCities($priceItem['type'], $priceItem['cityId']);
       	}
       	updateSizeCities($priceItem['size'], $priceItem['cityId']);
        	        
        $result['message'] = "Позиция добавлена в прайс-лист.";
        $result['status'] = 1;	                   
        $result['priceItemId'] = $newDocId;
        
	    return $result;    
	}
	
	function updateTypeCities($typeId, $cityId) {
		$typeCities = array();
	
		$typeDoc = R('DJEM')->Load($typeId);				
		if($typeDoc->city) {
			$typeCities = explode(',',$typeDoc->city);				
		}	
		array_push($typeCities, $cityId);
		$typeCities = array_unique($typeCities);
		$typeDoc->city = implode(',',$typeCities);						
		
		$typeDoc->_publish_time = time();        
		$typeDoc->Store();
	}
	
	function updateSizeCities($sizeId, $cityId) {
		$sizeCities = array();
	
		$sizeDoc = R('DJEM')->Load($sizeId);				
		if($sizeDoc->city) {
			$sizeCities = explode(',',$sizeDoc->city);				
		}	
		array_push($sizeCities, $cityId);
		$sizeCities = array_unique($sizeCities);
		$sizeDoc->city = implode(',',$sizeCities);						
		
		$sizeDoc->Store();
	}
	
?>