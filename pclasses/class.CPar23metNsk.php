<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CPar23metNsk extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8471109	=> 'nsk.23met.ru/plist/sibmetsnabnsk',
            8471110	=> 'nsk.23met.ru/plist/mcnsk'
        );
    }
}