<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParMcMoscow extends CParMc{
    function __construct(){
        parent::__construct();
        //$this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
	$this->home_url = 'http://mc.ru:8080/metalloprokat/armatura_riflenaya_a3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1'=>
                array(
                    'price_id' => 9187052,
                    'company_name' => 'Металл Сервис'.current(array_values($this->cities_list[$this->city_id]))
                ),
/*            '0.998'=>
                array(
                    'price_id' => 8872107,
                    'company_name' => 'Стальдекс'.current(array_values($this->cities_list[$this->city_id]))
                ),
            '0.993'=>
                array(
                    'price_id' => 5640894,
                    'company_name' => 'МК сталь резерв'.current(array_values($this->cities_list[$this->city_id]))
                ),
            '0.97'=>
                array(
                    'price_id' => 12308553,
                    'company_name' => 'Альянс-групп '.current(array_values($this->cities_list[$this->city_id]))
                ),
            '1.01'=>
                array(
                    'price_id' => 13437395,
                    'company_name' => 'ГлавСнаб - МС ТРУБЫ Москва '.current(array_values($this->cities_list[$this->city_id]))
                ),*/
        );
    }
}

class CParAlianceRostov extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '0.97'=>
                array(
                    'price_id' => 12298103,
                    'company_name' => 'Альянс-групп '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}
class CParStalProMc extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.01'=>
                array(
                    'price_id' => 12298103,
                    'company_name' => 'Сталь-про мс '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParAllMetGroup extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '0.98'=>
                array(
                    'price_id' => 12298103,
                    'company_name' => 'Альянс-металл групп '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParGlavsnabMC extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.01'=>
                array(
                    'price_id' => 13437395,
                    'company_name' => 'ГлавСнаб - МС ТРУБЫ Москва '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParVistMetall extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.02'=>
                array(
                    'price_id' => 13437395,
                    'company_name' => 'ВИСТ-МЕТАЛЛ '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParTSKKomplektologiyaMC extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '0.995'=>
                array(
                    'price_id' => 13437395,
                    'company_name' => 'TSKKomplektologiyaMC '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParMetTransTerminal extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '0.995'=>
                array(
                    'price_id' => 13437395,
                    'company_name' => 'МетТрансТерминал '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParStalReserv extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '0.993'=>
                array(
                    'price_id' => 5640894,
                    'company_name' => 'МК сталь резерв'.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParTriadaMarketSaratov extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.001'=>
                array(
                    'price_id' => 13184519,
                    'company_name' => 'Триада-Маркет '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParTriadaMarketSamara extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.001'=>
                array(
                    'price_id' => 13190466,
                    'company_name' => 'Триада-Маркет '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}
class CParTriadaMarketPenza extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.001'=>
                array(
                    'price_id' => 13205925,
                    'company_name' => 'Триада-Маркет '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParTriadaMarketUliyanovsk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.001'=>
                array(
                    'price_id' => 13193041,
                    'company_name' => 'Триада-Маркет '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParTriadaMarketKazan extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.001'=>
                array(
                    'price_id' => 13197159,
                    'company_name' => 'Триада-Маркет '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}
class CParTriadaMarketVolgograd extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.001'=>
                array(
                    'price_id' => 13200442,
                    'company_name' => 'Триада-Маркет '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParTriadaMarketAstrahan extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.001'=>
                array(
                    'price_id' => 13203337,
                    'company_name' => 'Триада-Маркет '.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParMcPenza extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/penza/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118111;
    }
}
class CParMcNn extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/nnovgorod/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471104;
    }
}
class CParMcBryansk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/br/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118112;
    }
}
class CParMcSmolensk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/smolensk/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118139;
    }
}
class CParMcRostov extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/ug/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471112;
    }
}
class CParMcNovosib extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/sib/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471110;
    }
}
class CParMcSpb extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/region/sp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8438129;
    }
}
class CParStalEnergo extends CParMc{
    function __construct(){
        parent::__construct();
        $this->company_name = "СтальЭнерго-96";
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 14425344;
    }
}

class CParMcSamara extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118102;
    }
}

class CParMcBalakovo extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/volga/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118131;
    }
}
class CParMcKursk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/black/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118113;
    }
}
class CParMcBelgorod extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/blg/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118114;
    }
}
class CParMcTaganrok extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/rst/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118200;
    }
}
class CParMcKrasnodar extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/krasnodar/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118116;
    }
}
class CParMcBarnaul extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/barnaul/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118145;
    }
}
class CParMcChelyabinsk extends CParMetallservice {
    function __construct(){
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/chelyabinsk';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118145;
    }
}

class CParMcHabarovsk extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/habarovsk/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118148;
    }
}
class CParMetTransChelyab extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.0068'=>
                array(
                    'price_id' => 5640894,
                    'company_name' => 'МК сталь резерв'.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParMetTransTumen extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.008'=>
                array(
                    'price_id' => 5640894,
                    'company_name' => 'МК сталь резерв'.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}

class CParMetTransEburg extends CParMc{
    function __construct(){
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->prices = array(
            '1.0038'=>
                array(
                    'price_id' => 5640894,
                    'company_name' => 'МК сталь резерв'.current(array_values($this->cities_list[$this->city_id]))
                ),
        );
    }
}
