#!/usr/bin/php

<?php
require_once ('/var/www/sergey/data/www/plastom.ujob.su/system/php/config.php');
class File_FGetCSV
{
    var $VERSION = "1.01";

    function fgetcsv($f, $length, $d = ",", $q = '"')
    {
        $list = array();
        $st = fgets($f, $length);
        if ($st === false || $st === null)
            return $st;
        if (trim($st) === "")
            return array("");
        while ($st !== "" && $st !== false) {
            if ($st[0] !== $q) {
                # Non-quoted.
                list($field) = explode($d, $st, 2);
                $st = substr($st, strlen($field) + strlen($d));
            } else {
                # Quoted field.
                $st = substr($st, 1);
                $field = "";
                while (1) {
                    # Find until finishing quote (EXCLUDING) or eol (including)
                    preg_match("/^((?:[^$q]+|$q$q)*)/sx", $st, $p);
                    $part = $p[1];
                    $partlen = strlen($part);
                    $st = substr($st, strlen($p[0]));
                    $field .= str_replace($q . $q, $q, $part);
                    if (strlen($st) && $st[0] === $q) {
                        # Found finishing quote.
                        list($dummy) = explode($d, $st, 2);
                        $st = substr($st, strlen($dummy) + strlen($d));
                        break;
                    } else {
                        # No finishing quote - newline.
                        $st = fgets($f, $length);
                    }
                }

            }
            $list[] = $field;
        }
        return $list;
    }

    function fputcsv($f, $list, $d = ",", $q = '"')
    {
        $line = "";
        foreach ($list as $field) {
            # remove any windows new lines,
            # as they interfere with the parsing at the other end
            $field = str_replace("\r\n", "\n", $field);
            # if a deliminator char, a double quote char or a newline
            # are in the field, add quotes
            if (ereg("[$d$q\n\r]", $field)) {
                $field = $q . str_replace($q, $q . $q, $field) . $q;
            }
            $line .= $field . $d;
        }
        # strip the last deliminator
        $line = substr($line, 0, -1);
        # add the newline
        $line .= "\n";
        # we dont care if the file pointer is invalid,
        # let fputs take care of it
        return fputs($f, $line);
    }
}
// приводим в корректный формат
function lechim($str)
{

    $str = iconv("CP1251//IGNORE", "UTF-8", $str);

    $err = array(
        "\"",
        "/",
        "?",
        ".",
        "#——џЋ ј!");
    $str = str_replace($err, "", trim($str));
    $str = str_replace("  ", " ", $str);
    if ($str == '' || $str == ' ') {
        $str = 'non';
    }
    return $str;
}






$fp = fopen('/var/www/sergey/data/www/plastom.ujob.su/csv/'.$argv[1].'_result.csv', 'w');
$row = 1;
$handle = fopen("/var/www/sergey/data/www/plastom.ujob.su/csv/".$argv[1]."_update.csv","r"); // сюда вставить $uploadfile вместо 3.csv

while (($data = File_FGetCSV::fgetcsv($handle, 5000, ";")) !== false) {

 $i = 0;
   if ($argv[3]!='false') { $wholesale = str_replace(" ", "",lechim($data[$argv[3]])); } else {$wholesale = ""; }  
   if ($argv[4]!='false')  { $retail = str_replace(" ", "",lechim($data[$argv[4]]));} else {$retail = "";}  
    $price = new DJEMForeach(R('DJEM'));
    $price->Path('main.metal.price.*')->Where('_link1='.$argv[1].' && old_price==\''.trim(iconv("CP1251//IGNORE", "UTF-8",$data[$argv[2]])).'\'')->Fields('old_price');
    foreach ($price as $item) {
       
      //  foreach ($data as $k) {
         // if ($i>0 && $item->{"fit2"}) {echo "delete".'<br>'; R('DJEM')->Query('DELETE FROM `metall`.`documents` WHERE `documents`.`document_id`="?"',$item->{"_id"}); break; }
            
           
            
                if (str_replace(" ", "", $item->{"retail"}) != $retail ||  str_replace(" ", "", $item->{"wholesale"}) != $wholesale) {
                    //echo $i . ' - ' . lechim($k) . ' - ' . lechim($data[$argv[3]]) .' - ' .$item->{"retail"}.'<br>';   
                    $item->{'wholesale'} = $wholesale;   
                 $item->{'retail'} = $retail;
                 if ($retail=='' || $retail=='Ч') $item->retail ="99999999999";
    if ($wholesale=='' || $wholesale=='Ч') $item->wholesale ="99999999999";       
                  $item->Store();
                // break; 
                // $k=$item->{"typ_price"}.';'.$item->{"fit3"}.';'.$item->{"fit1"}.';'.$item->{"fit2"}.';'.$item->{"size"}.';'.$item->{"steel"}.';'.$item->{"Ghost"}.';'.$item->{"number"}.';'.$item->{"wholesale"}.';'.$item->{"retail"}.';'.$item->{"Notes"}.';'.$item->{"old_price"};

                }
 

   
        //}
$i++;
    }
       if ($i==0)  {
        if (empty($data[$argv[3]]) && empty($data[$argv[4]]))   {   } else {    
            
                  if ($argv[3]!='false' && $argv[4]!='false')  $k=$data[$argv[2]].';'.str_replace(array("\r", "\n","\r\n"),'',$data[$argv[3]]).';'.str_replace(array("\r", "\n","\r\n"),'',$data[$argv[4]]);
                  if ($argv[3]!='false' && $argv[4]=='false') $k=$data[$argv[2]].';'.str_replace(array("\r", "\n","\r\n"),'',$data[$argv[3]]).';';
                  if ($argv[3]=='false' && $argv[4]=='false') $k=$data[$argv[2]].';;';
                  
                  if ($argv[3]=='false' && $argv[4]!='false') $k=$data[$argv[2]].';;'.str_replace(array("\r", "\n","\r\n"),'',$data[$argv[4]]);
                  $k=$k."\n";
                  
 fwrite($fp, $k);
      
           } }


   
}
fclose($handle);
fclose($fp); 


// обнуление цен которых нету в прайсе
$null = new DJEMForeach(R('DJEM'));
$time=time()-15;
$null->Path('main.metal.price.*')->Where('_link1='.$argv[1])->Fields('wholesale,retail');
    foreach ($null as $item) {
    //  $item->{'wholesale'} ="99999999999";   
              //   $item->{'retail'} ="99999999999";       
                  $item->Store();

}



?>