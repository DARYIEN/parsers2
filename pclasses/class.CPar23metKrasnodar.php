<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CPar23metKrasnodar extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            10101226 => 'krasnodar.23met.ru/plist/agruppkrasnodar',
        );
    }
}