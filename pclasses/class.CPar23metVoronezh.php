<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CPar23metVoronezh extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8471086	=> 'voronezh.23met.ru/plist/protec',
            8471087	=> 'voronezh.23met.ru/plist/metallotorgvoronezh',
        );
    }
}