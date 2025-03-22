<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CPar23metRostovND extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8471111	=> 'rostov.23met.ru/plist/metallotorgrostov',
            8471112	=> 'rostov.23met.ru/plist/mcrostov',
        );
    }
}