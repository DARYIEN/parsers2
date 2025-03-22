<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParBrokinvestTver extends CParBrokinvest{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8480822	=> 'brokinvest.ru/catalog/?print=yes',
        );
    }
}