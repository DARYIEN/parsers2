<?php
    require_once './inc/simple_html_dom.php';
    require_once '/var/www/sergey/data/www/plastom.ujob.su/lib/parsecsv-0.3.2/parsecsv.lib.php';
    
    $url="http://23met.ru";
	$mainPage = file_get_html($url);
    $csv = new parseCSV();
    $csv->encoding('UTF-8');
    $csv->delimiter = "\t";
    $data = Array();
    
    foreach ($mainPage->find('div[class=panes] a ') as $i=>$link) {     
        if($i < 1) {
            $innerPage = file_get_html($url . $link->href);
            $data[$i]['category'] = trim($innerPage->find('div[id=left-container]  a[class=current]',0)->plaintext);
            $data[$i]['size'] = trim($innerPage->find('div[id=show_first] a[class=current]',0)->plaintext);
            $data[$i]['baseFactor'] = trim($innerPage->find('input[id=calc_koef]',0)->getAttribute('value'));
            $data[$i]['steelFactor'] = trim($innerPage->find('input[id=calc_stal_koef]',0)->getAttribute('value'));
            $data[$i]['factor'] = $data[$i]['baseFactor'] * $data[$i]['steelFactor']; 
        }
    } 
    
    echo sizeof($mainPage->find('div[class=panes] a '));
    
    //$csv->output(true, 'metalFactors.csv', $data);  
    $csv->save('./data/metalFactors.csv', $data, true); 
?>
