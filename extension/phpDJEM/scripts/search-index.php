
<?php
	
	require_once('/www/template1.djem.ru/www/system/php/config.php');
	
	$search = R('DJEMSearch');
	
	if(function_exists('stem_russian_unicode')) {
		print "exist";
	} else {
		include_once($_SERVER['DOCUMENT_ROOT'] . "/system/php/libs/Stemmer.phtml");
		$stemmer = new Lingua_Stem_Ru();
		
		function stem_russian_unicode($w) {
			global $stemmer;
			return $stemmer->stem_word($w);
		}
	}
	
	$foreach_x = R('DJEM')->GetForeach()->Path('main.rus.*')->Sort('_gsort');
	while($foreach_x->Fetch()) {
		$search->ParseDocument($foreach_x->_id);
	}
	
	print "done";
	
?>