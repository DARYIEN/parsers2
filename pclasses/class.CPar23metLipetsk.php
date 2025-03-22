<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CPar23metLipetsk extends CPar23met{
    function __construct(){
        parent::__construct();
        $this->document_urls = array(
            8471089	=> 'lipetsk.23met.ru/plist/metallotorglipetsk',
            8471090	=> 'lipetsk.23met.ru/plist/brokinvestlipetsk',
            8471091	=> 'lipetsk.23met.ru/plist/nlmkbaza',
        );
    }
}