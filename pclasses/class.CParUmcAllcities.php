<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParUmcRn extends CParUmc{
    function __construct(){
        parent::$name_parser = array(
            'umc' => 'Южный Метал Центр Ростов-На-Дону '
    );
        parent::__construct();
        $this->price_id = 9994484;
    }
}
class CParUmcNovocher extends CParUmc{
    function __construct(){
        parent::$name_parser = array(
            'umc' => 'Южный Метал Центр Новочеркасск '
    );
        parent::__construct();
        $this->price_id = 9994492;
    }
}
class CParUmcStavropol extends CParUmc{
    function __construct(){
        parent::$name_parser = array(
            'umc' => 'Южный Метал Центр Ставрополь '
    );
        parent::__construct();
        $this->price_id = 9994487;
    }
}
class CParUmcKrasnoyarks extends CParUmc{
    function __construct(){
        parent::$name_parser = array(
            'umc' => 'Южный Метал Центр Красноярск'
    );
        parent::__construct();
        $this->price_id = 9994494;
    }
}
class CParUmcSochi extends CParUmc{
    function __construct(){
        parent::$name_parser = array(
            'umc' => 'Южный Метал Центр Сочи'
    );
        parent::__construct();
        $this->price_id = 9999293;
    }
}
class CParUmcMaxachkala extends CParUmc{
    function __construct(){
        parent::$name_parser = array(
            'umc' => 'Южный Метал Центр Махачкала '
    );
        parent::__construct();
        $this->price_id = 9997963;
    }
}
class CParUmcKrasnodar extends CParUmc{
    function __construct(){
        parent::$name_parser = array(
            'umc' => 'Южный Метал Центр Краснодар '
    );
        parent::__construct();
        $this->price_id = 9994494;
    }
}