<?php
	
	Class Banners {
		
		/**
		* DJEM Instance
		*
		* @access private
		* @var object
		*/
		private $djem;
		
		/**
		* Массив баннеров
		*
		* @access private
		* @var array
		*/
		private $banners = array();
		
		/**
		* Папка с баннерами
		*
		* @access private
		* @var integer
		*/
		private $bannersFolder = 216;
		
		/**
		* Текущее время
		*
		* @access private
		* @var integer
		*/
		private $time;
		
		/**
		* Конструктор
		*
		* @access public
		* @param mixed $djem
		*/
		function __construct($djem = false) {
			if($djem != false && $djem instanceof DJEM) {
				$this->djem = $djem;
			} else {
				$this->djem = R('DJEM');
			}
			$this->time = time();
			$this->GetActiveBanners();
		}
		
		/**
		* Забираем все баннеры
		*
		* @access public
		* @param integer $id
		* @return object (self)
		*/
		function SetFolder($id) {
			$this->bannersFolder = $id;
			return $this;
		}
		
		/**
		* Забираем все баннеры
		*
		* @access public
		* @return object (self)
		*/
		function GetActiveBanners() {
			$this->banners = array();
			if($this->bannersFolder) {
				$foreach = $this->djem->GetForeach()->Path($this->bannersFolder . ".$")->Where('hide != "on" && starttime le ' . $this->time . ' && endtime ge ' . $this->time . '');
				while($document = $foreach->Fetch()) {
					$this->banners[$document->_id] = $document;
				}
			}
			return $this;
		}
		
		/**
		* Забираем баннеры для баннероместа
		*
		* @access public
		* @param integer $palce
		* @return array
		*/
		function GetActiveBannersByPlace($bannerPlace) {
			if(count($this->banners) == 0) {
				$this->GetActiveBanners();
			}
			$banners = array();
			foreach($this->banners as $banner) {
				if($banner->place == $bannerPlace) {
					$banners[$banner->_id] = $banner;
				}
			}
			return $banners;
		}
		
		/**
		* Показываем баннер
		*
		* @access public
		* @param integer $bannerPlace
		* @param integer $count
		*/
		function Show($bannerPlace, $count = 1) {
			$banners = $this->GetActiveBannersByPlace($bannerPlace);
			for ($i = 1; $i <= $count; $i++) {
				if(count($banners) > 0) {
					$bannerKey = array_rand($banners, 1);
					if($bannerKey) {
						$banner = $banners[$bannerKey];
						$banner->totalshows = intval($banner->totalshows) + 1;
						if($banner->maxshows) {
							$banner->maxshows = intval($banner->maxshows) - 1;
							if($banner->maxshows == 0) {
								$banner->hide = "on";
							}
						}
						$banner->Store();
						$this->PrintBanner($banner);
						unset($banners[$banner->_id]);
					}
				}
			}
		}
		
		/**
		* Вовод html кода баннероместа
		*
		* @access public
		* @param object $banner
		*/
		function PrintBanner($banner) {
			if ($banner->html != '') {
				print $banner->html;
				return;
			}
			print '<a href="/system/php/banner_click.php?id=' . $banner->_id . '&time=' . $this->time . '"';
			if ($banner->blank == 'on') {
				print ' target="_blank"';
			}
			print '>';
			if ($banner->color != '') {
				print '<div style="background-color:#' . $banner->color . '; text-align: center;">';
			}
			print '<img src="'.$banner->pic.'" border=0 alt="' . $banner->_name . '">';
			if ($banner->color != '') {
				print '</div>';
			}
			print '</a>';
			if ($banner->nobr != '') {
				print '<br><br>';
			}
		}
		
		/**
		* Обсчет клика по баннеру
		*
		* @access public
		* @param integer $id
		* @return string
		*/
		function Click($id) {
			$banner = false;
			if(count($this->banners) > 0) {
				if(isset($this->banners[$id])) {
					$banner = $this->banners[$id];
					$banner->totalclick = intval($banner->totalclick) + 1;
					$banner->Store();
					return $banner->www;
				}
			}
			return false;
		}
	}
	
?>