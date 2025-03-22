<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParBrokinvestVladimir extends CParBrokinvest{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8479203	=> 'brokinvest.ru/catalog/?print=yes',
        );
    }
}