<?php

/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */
class CParAlianceRostov extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        //$this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
	$this->home_url = 'http://mc.ru:8080/metalloprokat/armatura_riflenaya_a3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'company_name' => 'Альянс-групп ' . current(array_values($this->cities_list[$this->city_id])),
            'coefficient' => '0.97'
        );
    }
}
class CParMCPlus10 extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'company_name' => 'Плюс10 ' . current(array_values($this->cities_list[$this->city_id])),
            'coefficient' => '1.1'
        );
    }
}
class CParMCPlus20 extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'company_name' => 'Плюс20 ' . current(array_values($this->cities_list[$this->city_id])),
            'coefficient' => '1.2'
        );
    }
}
class CParMCPlus3 extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'company_name' => 'Плюс3 ' . current(array_values($this->cities_list[$this->city_id])),
            'coefficient' => '1.03'
        );
    }
}
class CParMCPlus4 extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'company_name' => 'Плюс4 ' . current(array_values($this->cities_list[$this->city_id])),
            'coefficient' => '1.04'
        );
    }
}
class CParMCPlus5 extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'company_name' => 'Плюс5 ' . current(array_values($this->cities_list[$this->city_id])),
            'coefficient' => '1.05'
        );
    }
}

class CParMcMoscow extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        //$this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
	    $this->home_url = 'https://mc.ru/products';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'company_name' => 'Металл Сервис' . current(array_values($this->cities_list[$this->city_id])),
            'coefficient' => '1'
        );
    }
}

class CParStalProMc extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.01',
            'company_name' => 'Сталь-про мс ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParAllMetGroup extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '0.98',
            'company_name' => 'Альянс-металл групп ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParGlavsnabMC extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.01',
            'company_name' => 'ГлавСнаб - МС ТРУБЫ Москва ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParVistMetall extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.02',
            'company_name' => 'ВИСТ-МЕТАЛЛ ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTSKKomplektologiyaMC extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '0.995',
            'company_name' => 'TSKKomplektologiyaMC ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMetTransTerminal extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '0.995',
            'company_name' => 'МетТрансТерминал ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParStalReserv extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '0.993',
            'company_name' => 'МК сталь резерв' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTriadaMarketSaratov extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.001',
            'company_name' => 'Триада-Маркет ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTriadaMarketSamara extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.001',
            'company_name' => 'Триада-Маркет ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTriadaMarketPenza extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.001',
            'company_name' => 'Триада-Маркет ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTriadaMarketUliyanovsk extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.001',
            'company_name' => 'Триада-Маркет ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTriadaMarketKazan extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.001',
            'company_name' => 'Триада-Маркет ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTriadaMarketVolgograd extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.001',
            'company_name' => 'Триада-Маркет ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParTriadaMarketAstrahan extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.001',
            'company_name' => 'Триада-Маркет ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcPenza extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/penza';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118111;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcNn extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/nnovgorod';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471104;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcBryansk extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/bryansk';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118112;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcSmolensk extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/region/smolensk/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118139;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcRostov extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/rostovnadonu';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471112;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcNovosib extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/novosibirsk';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8471110;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcSpb extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/speterburg';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8438129;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcEkaterinburg extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/ekaterinburg';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8438129;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcUfa extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/ufa';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8438129;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}
class CParMcPerm extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/perm';
        $this->document_urls = $this->getUrl();
        $this->price_id = 8438129;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParStalEnergo extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->company_name = "СтальЭнерго-96";
        $this->home_url = 'http://mc.ru:8080/page.asp/region/samara/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 14425344;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcSamara extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/samara';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118102;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcBalakovo extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/region/balakovo/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118131;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcKursk extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/kursk';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118113;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcBelgorod extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/belgorod';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118114;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcTaganrok extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/taganrog';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118200;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcKrasnodar extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/krasnodar';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118116;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcBarnaul extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/barnaul';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118145;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcChelyabinsk extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/chelyabinsk';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118145;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcCheboksary extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/cheboksary';
        $this->document_urls = $this->getUrl();
        $this->price_id = 9118145;
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMcHabarovsk extends CParMcHtml
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'https://mc.ru/products/habarovsk';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1',
            'company_name' => 'МС ' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMetTransChelyab extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.0068',
            'company_name' => 'МК сталь резерв' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMetTransTumen extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->priceInfo = array(
            'coefficient' => '1.008',
            'company_name' => 'МК сталь резерв' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}

class CParMetTransEburg extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '1.0038',
            'company_name' => 'МК сталь резерв' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}
class CParAtomSteel extends CParMcNew
{
    function __construct()
    {
        parent::__construct();
        $this->home_url = 'http://mc.ru:8080/page.asp/metalloprokat/armatura_A3';
        $this->document_urls = $this->getUrl();
        $this->priceInfo = array(
            'coefficient' => '0.975',
            'company_name' => 'Атом сталь' . current(array_values($this->cities_list[$this->city_id]))
        );
    }
}
