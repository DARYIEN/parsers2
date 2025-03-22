<?php

    class Basket {
        private $djem;
        private $folderId = 148;
		
        function __construct($djem) {
			$this->djem = $djem;    
        }
		
        function Service($action) {
        	$authObj = R('DJEMAuth');
            switch ($action) {
				case 'updateBasketItem': 					
					$result = $this->UpdateBasketItem(); 
        			if ($result) {
    					$result = array('result' => 'success'); 
    					print json_encode($result);
        			} else {
        				$result = array('result' => 'failure', 'reason' => $this->error);
        				print json_encode($result);
        			}
        		break;			
        		case 'addItem': 					
					$result = $this->AddBasketItem(); 
        			if ($result) {
    					$result = array('result' => 'Добавлен в корзину', 'count' => $result['items'], 'summ' => $result['sum']); 
    					print json_encode($result);
        			} else {
        				$result = array('result' => 'failure', 'reason' => $this->error);
        				print json_encode($result);
        			}
        		break;
        		case 'updateByRequest' :
        			$result = $this->UpdateBasketByRequest();
        			if ($result) {
    					$result = array('result' => 'success'); 
    					print json_encode($result);
        			} else {
        				$result = array('result' => 'failure', 'reason' => $this->error);      				
        				print json_encode($result);
        			}
        			break;
				default: 
					return false;
				break;
			}
			return true;
        }
     	
     	function AddBasketItem() {
     		$itemId = intval($_REQUEST['id']);
     		try {     		
     			$item = $this->djem->Load($itemId);
     		} catch (Exception $e) {
     			$this->error = 'Товарная позиция отсутствует';
     			return false;
     		}
     		if($item->price == "" || $item->nosklad == "on") {
     			$this->error = 'Товарная позиция отсутствует';
     			return false;
     		}
     		if (strpos($item->_path, 'main.rus.shop.') !== 0) {
     			$this->error = 'Товарная позиция отсутствует';
     			return false;
     		}
     		$price = floatval(str_replace(',', '.', $item->price)); 
     		$newData = array(
				'quantity' 	=> 1, 
				'price' 	=> $price,
				'size'		=> $item->size,
				'color'		=> $item->color,
				'sum'		=> $price,
				'_url'		=> $item->_url
			);
			$basket = $this->GetBasket();
			if (count($newData) == 0) {
				unset($basket[$itemId]); 
			} else {
				if(isset($basket[$itemId])) {
					$newData['quantity'] += $basket[$itemId]['quantity'];
				}
				$basket[$itemId] = $newData;
			}
			$this->SetBasket($basket);
			return $this->CalculateBasket();
     	}
     	
     	function UpdateBasketByRequest() {
     		$basket = $this->GetBasket();
     		foreach(array_keys($basket) as $basket_key) {
     			if(isset($_REQUEST['basket_quantity_' . $basket_key])) {
     				// обновление количества элементов
     				if(intval($_REQUEST['basket_quantity_' . $basket_key]) > 0) {
     					$basket[$basket_key]['quantity'] = $_REQUEST['basket_quantity_' . $basket_key];
     				} else {
     					unset($basket[$basket_key]);
     				}
     			}
     		}
     		$this->SetBasket($basket);
     		return true;
     	}
     	
     	function UpdateBasketItem() {
     		$itemId = intval($_REQUEST['id']);
     		try {     		
     			$item = $this->djem->Load($itemId);
     		} catch (Exception $e) {
     			$this->error = 'Товарная позиция отсутствует';
     			return false;
     		}
     		$price = floatval(str_replace(',', '.', $item->price)); 
     		try {
     			$data = json_decode($_REQUEST['data']); 	
     		} catch (Exception $e) {
     			$this->error = 'Ошибка в переданных данных';
     			return false;
     		}
     		$newData = array();
			foreach ($data as $d) {
				if ($d->quantity == 0) continue;	
				$newData[] = array(
					'quantity' => $d->quantity,
					'price'	=> $price,
					'size'	=> $d->size,
					'color'	=> $d->color,
					'_url'	=> $d->_url,
					'sum'	=> $price * $d->quantity
				);
			}
			$basket = $this->GetBasket();
			if (count($newData) == 0) {
				unset($basket[$itemId]); 
			} else {
				$basket[$itemId] = $newData;
			}
			$this->SetBasket($basket);
			return true;
     	}
     	
     	function GetBasket() {
     		$basket = R('DJEMSessions')->basket; 
     		if (empty($basket)) {
     			$basket = array();
     		} else {
     			$basket = $basket;
     		}
     		return $basket;
     	}
     	
     	function SetBasket($basket) {
     		R('DJEMSessions')->basket = $basket;
     	}
        
        function CalculateBasket() {
        	$basket = $this->GetBasket();
        	$totalItems = 0;
        	$totalSum = 0; 
        	foreach ($basket as $itemId => $b) {
        		$totalItems += $b['quantity'];
        		$totalSum += $b['price'] * $b['quantity'];
        	}
        	$discount = 0;
        	$discountSum = floor($discount * $totalSum);
        	return array(
        		'items' => $totalItems,
        		'sum' => $totalSum - $discountSum, 
        		'discount' => $discountSum
        	);
        }
        
        function GetFullBasket() {
        	$result = array();
        	$basket = $this->GetBasket();
        	if (count($basket)) {
        		$query = $this->djem->Query('	SELECT 
        											v.document_name AS var_name, 
        											v.document_id AS var_id,
        											g.document_name AS good_name, 
        											g.document_id AS good_id,
        											v.document_url AS good_url
        										FROM 
        											documents AS v
        										LEFT JOIN documents AS g ON g.document_id=v.document_link1
        										WHERE 
        											v.document_id IN (?)
        										ORDER BY 
        											g.document_id',  join(',', array_keys($basket)));
				foreach ($query as $q) {
					$result[$q['var_id']] = array(
						'good' 		=> $q['good_name'], 
						'good_id' 	=> $q['good_id'],
						'item' 		=> $q['var_name'],
						'item_id' 	=> $q['var_id'], 
						'_url' 		=> $q['good_url'], 
						'price' 	=> $basket[$q['var_id']]['price'],
						'quantity' 	=> $basket[$q['var_id']]['quantity'],
						'sum'		=> $basket[$q['var_id']]['price'] * $basket[$q['var_id']]['quantity']
					);
				}
			}
			return $result;
        }
    }