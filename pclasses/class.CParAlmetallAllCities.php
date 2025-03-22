<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */

class CParAlmetallMoscow extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/moscow/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10134515;
    }
}
class CParAlmetallSpb extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/saint-petersburg/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10140919;
    }
}
class CParAlmetallSochi extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/sochi/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10140920;
    }
}
class CParAlmetallVoronezh extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/voronezh/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10134521;
    }
}
class CParAlmetallKaluga extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/kaluga/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10140164;
    }
}
class CParAlmetallEburg extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/ekaterinburg/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10107880;
    }
}
class CParAlmetallVladimir extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/vladimir/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10134520;
    }
}
class CParAlmetallBryansk extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/bryansk/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10134519;
    }
}
class CParAlmetallBelgorod extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/belgorod/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10134518;
    }
}
class CParAlmetallBarnaul extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/barnaul/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10134517;
    }
}
class CParAlmetallAstrahan extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/astrakhan/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10148827;
    }
}
class CParAlmetallVladivostok extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/vladivostok/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10148826;
    }
}
class CParAlmetallVologda extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/vologda/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10481077;
    }
}
class CParAlmetallVolgograd extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/volgograd/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10481078;
    }
}
class CParAlmetallKrasnodar extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/krasnodar/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10481079;
    }
}
class CParAlmetallUfa extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/ufa/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10481080;
    }
}
class CParAlmetallHabarovsk extends CParAlmetall{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://all-metall.ru/catalog/khabarovsk/';
        $this->document_urls = $this->getUrl();
        $this->price_id = 10481081;
    }
}
/*
class CParMcBryansk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/br/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118112;
    }
}
class CParMcSmolensk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/smolensk/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118139;
    }
}
class CParMcRostov extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/ug/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471112;
    }
}
class CParMcNovosib extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/sib/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471110;
    }
}
class CParMcSpb extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/sp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8438129;
    }
}
class CParMcSamara extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118104;
    }
}
class CParMcBalakovo extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/volga/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118131;
    }
}
class CParMcKursk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/black/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118113;
    }
}
class CParMcBelgorod extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/blg/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118114;
    }
}
class CParMcTaganrok extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/rst/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118200;
    }
}
class CParMcKrasnodar extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/krasnodar/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118116;
    }
}
class CParMcBarnaul extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/barnaul/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118145;
    }
}
class CParMcHabarovsk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru/page.asp/region/habarovsk/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118148;
    }
}


*/