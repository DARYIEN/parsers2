<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CPar23metKazan extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8438147	=> 'kazan.23met.ru/plist/metallotorgkazan',
            8438148	=> 'kazan.23met.ru/plist/uraltrybostalkazan'

        );
    }
}