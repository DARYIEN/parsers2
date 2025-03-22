<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CPar23metSpb extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            //8438129 => 'spb.23met.ru/plist/mcspb',
            8438130 => 'spb.23met.ru/plist/diposspb',
            8438131 => 'spb.23met.ru/plist/sanc',
            8438132 => 'spb.23met.ru/plist/rosmetall',
            //8438133 => 'spb.23met.ru/plist/metallotorgspb',
            //8438134 => 'spb.23met.ru/plist/severstalspb',
            //8438124 => 'spb.23met.ru/plist/agruppspb',
            8438135 => 'spb.23met.ru/plist/szmk',
            //8438128 => 'spb.23met.ru/plist/brokinvestspb',
            //8438136 => 'spb.23met.ru/plist/mechelspb',
            8438137 => 'spb.23met.ru/plist/tobolspb',
            8438138 => 'spb.23met.ru/plist/szmetal',
            10101197 => 'spb.23met.ru/plist/lenstroyspb',
            10101198 => 'spb.23met.ru/plist/tkmrspb',
            10101199 => 'spb.23met.ru/plist/baltmetall',
            10101200 => 'spb.23met.ru/plist/citymetspb',
            10101201 => 'spb.23met.ru/plist/pmetallobaza',
            10101209 => 'spb.23met.ru/plist/westmetspb',
        );
    }
}