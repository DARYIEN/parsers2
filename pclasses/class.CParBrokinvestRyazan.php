<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParBrokinvestRyazan extends CParBrokinvest{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            5684513	=> 'brokinvest.ru/catalog/?print=yes',
        );
    }
}