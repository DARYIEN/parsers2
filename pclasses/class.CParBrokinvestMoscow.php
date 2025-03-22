<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParBrokinvestMoscow extends CParBrokinvest{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            5683347	=> 'brokinvest.ru/catalog/price/',
        );
    }
}