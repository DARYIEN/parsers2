<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CPar23metEburg extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8438144	=> 'ekb.23met.ru/plist/spkekb',
            8438146	=> 'ekb.23met.ru/plist/eamcekb',
            10107881 => 'ekb.23met.ru/plist/tel'
        );
    }
}