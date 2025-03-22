<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CPar23metNizhniyNov extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8471104	=> 'nn.23met.ru/plist/mcnn',
            8471105	=> 'nn.23met.ru/plist/nnk',
            8471106	=> 'nn.23met.ru/plist/shery',
            8471107	=> 'nn.23met.ru/plist/spknn',
            8471108	=> 'nn.23met.ru/plist/metallotorgnn',
        );
    }
}