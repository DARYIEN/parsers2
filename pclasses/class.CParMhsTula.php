<?php
/**
 * Created by ~[M!sterX@#]~.
 * Date: 08.07.13
 * Time: 12:44
 */


class CParMhsTula extends CParMhs{
    function __construct(){
        parent::$name_parser = array(
            'mhs' => 'Металл Холдинг Строй Тула'
    );
        parent::__construct();
        $this->price_id = 9039000;
    }
}