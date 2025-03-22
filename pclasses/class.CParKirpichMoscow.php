<?php
class CParKirpichMoscow extends CParKirpich {
    var $parser;
    function __construct() {
        parent::__construct();
        $this->site_link = "https://www.kirpich.ru";
        foreach ($this->list_parsers as $city_id => $parser) {
            if (in_array(get_class($this), $parser)) {
                $this->city_id = $city_id;
                break;
            }
        }
        $this->formDirArray();
    }
    function start() {
       $this->processParsing();
       $this->parseSave();
    }
}
