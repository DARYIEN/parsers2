<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParPromISTaganrok extends CParPromIS{
    function __construct(){
        parent::$name_parser = array(
            'promis' => 'Пром Инвест Строй Таганрок'
    );
        parent::__construct();
        $this->price_id = 10481086;
    }
}
class CParPromISKrasnodar extends CParPromIS{
    function __construct(){
        parent::$name_parser = array(
            'promis' => 'Пром Инвест Строй Краснодарск'
    );
        parent::__construct();
        $this->price_id = 10481087;
    }
}
class CParPromISStavropol extends CParPromIS{
    function __construct(){
        parent::$name_parser = array(
            'promis' => 'Пром Инвест Строй Ставрополь'
    );
        parent::__construct();
        $this->price_id = 10481088;
    }
}