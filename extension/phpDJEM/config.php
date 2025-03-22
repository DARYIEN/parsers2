<?php
if (!function_exists('json_encode')) {
    require_once('JSON.php');

    function json_encode($arg) {
        global $services_json;
        if (!isset($services_json)) {
            $services_json = new Services_JSON();
        }
        return $services_json->encode($arg);
    }

    function json_decode($arg) {
        global $services_json;
        if (!isset($services_json)) {
            $services_json = new Services_JSON();
        }
        return $services_json->decode($arg);
    }
}


if (!function_exists("format_rus")) {
    function format_rus($a) {
        return $a;
    }
}
class Config {
    public $sqlHost		= 'metal100.ru';
    public $sqlBase		= 'metall';
    public $sqlUser		= 'clinic_rating';
    public $sqlPassword	= 'r0rWVs1w';
    public $documentRoot	= false;
    public $httpHost		= "metal100.ru";
    public $djemserverPath	= "";
    public $adminEmail		= '';
    public $debug			= false;
    public $debugLevel		= 'firephp';
    public $displayErrors	= false;
    public $errorReporting  = '';

    function __construct() {

        date_default_timezone_set('Europe/Moscow');

        @ini_set('display_errors', $this->displayErrors);
        @error_reporting($this->errorReporting);
        ini_set("display_errors", "On");
        ini_set("error_reporting", "E_ALL");

        if ($this->documentRoot === false) $this->documentRoot = dirname(__FILE__);
        if ($this->httpHost === false) $this->httpHost = $_SERVER['SERVER_NAME'];

        $this->classes = array(
            trim('DJEM') => trim('/DJEM.php'),
            trim('DJEMAuth') => trim('/DJEMAuth.php'),
            trim('DJEMCache') => trim('/DJEMCache.php'),
            trim('DJEMDatabase') => trim('/DJEMDatabase.php'),
            trim('DJEMDocument') => trim('/DJEMDocument.php'),
            trim('DJEMForeach') => trim('/DJEMForeach.php'),
            trim('DJEMServer') => trim('/DJEMServer.php'),
            trim('DJEMSessions') => trim('/DJEMSessions.php'),
            trim('DJEMXml') => trim('/DJEMXml.php'),
            trim('DJEMMail') => trim('/DJEMMail.php'),
            trim('DJEMScript') => trim('/DJEMScript.php'),
            trim('DJEMForeachCtpl') => trim('/DJEMForeachCtpl.php'),
            trim('DJEMSearch') => trim('/DJEMSearch.php'),
            trim('DJEMHttp') => trim('/DJEMHttp'),
            trim('DJEMSearchDocuments') => trim('/DJEMSearchDocuments.php')								   								);

        $this->modules = array(									trim('3781080') => array('file' => trim('modules/document3781080.php'), 'class' => "3781080")
        , 									trim('3781085') => array('file' => trim('modules/document3781085.php'), 'class' => "3781085")
        , 									trim('3781086') => array('file' => trim('modules/document3781086.php'), 'class' => "3781086")
        , 									trim('3781088') => array('file' => trim('modules/document3781088.php'), 'class' => "3781088")
        , 									trim('3823954') => array('file' => trim('modules/document3823954.php'), 'class' => "3823954")
        , 									trim('3823955') => array('file' => trim('modules/document3823955.php'), 'class' => "3823955")
        , 									trim('387287') => array('file' => trim('/tmp/advertisement.php'), 'class' => "387287")
        , 									trim('387311') => array('file' => trim('/tmp/create-listings.php'), 'class' => "387311")
        , 									trim('391294') => array('file' => trim('/tmp/addition.php'), 'class' => "391294")
        , 									trim('6085655') => array('file' => trim('modules/document6085655.php'), 'class' => "6085655")
        , 									trim('6128407') => array('file' => trim('modules/document6128407.php'), 'class' => "6128407")
        , 									trim('6136731') => array('file' => trim('modules/document6136731.php'), 'class' => "6136731")
        , 									trim('7362534') => array('file' => trim('modules/document7362534.php'), 'class' => "7362534")
        , 									trim('7362536') => array('file' => trim('modules/document7362536.php'), 'class' => "7362536")
        , 									trim('7546807') => array('file' => trim('modules/document7546807.php'), 'class' => "7546807")
        , 									trim('7560193') => array('file' => trim('modules/document7560193.php'), 'class' => "7560193")
        , 									trim('7567279') => array('file' => trim('modules/document7567279.php'), 'class' => "7567279")
        , 									trim('7607539') => array('file' => trim('modules/document7607539.php'), 'class' => "7607539")
        , 									trim('tags') => array('file' => trim('modules/Tags.php'), 'class' => "Tags")
        , 									trim('auth') => array('file' => trim('modules/auth.php'), 'class' => "auth")
        , 									trim('basket') => array('file' => trim('modules/Basket.php'), 'class' => "basket")
        , 									trim('create-listings') => array('file' => trim('modules/document457766.php'), 'class' => "create-listings")
        , 									trim('download-file') => array('file' => trim('modules/download-file.php'), 'class' => "download-file")
        , 									trim('editing-price') => array('file' => trim('modules/editing-price.php'), 'class' => "editing-price")
        , 									trim('error-message') => array('file' => trim('modules/error-message.php'), 'class' => "error-message")
        , 									trim('sms-dispatch') => array('file' => trim('modules/sms-dispatch.php'), 'class' => "sms-dispatch")
        , 									trim('tripang') => array('file' => trim('modules/document6136750.php'), 'class' => "tripang")
        );
    }

    function __get($name) {
        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        } else {
            throw new Exception('Данная переменная [' . $name . '] не существует в конфигурационном файле!');

            return false;
        }
    }
}
class Registry {
    private static $_vars = array();
    public function __construct() {}

    public static function Set($key, $var) {
        if (isset(self::$_vars[$key]) == true && 0) {
            throw new Exception('Данная переменная [' . $key . '] уже существует!');
        }

        self::$_vars[$key] = $var;
        return true;
    }

    public static function Get($key) {
        global $MODULES;
        // print 'registry: ' . $key . "\n";

        if (isset(self::$_vars[$key]) == false) {
            // var_dump(self::$_vars['Config']->classes);
            $c = self::$_vars['Config'];

            if (isset($c) && isset($c->classes[$key])) {
                require_once($c->documentRoot . self::$_vars['Config']->classes[$key]);
                $args = func_get_args();
                if (count($args) === 1) {
                    if ($key == 'DJEM') { // Специальные условия создания для джема
                        if ($c) {
                            self::$_vars[$key] = new $key($c->sqlHost, $c->sqlBase, $c->sqlUser, $c->sqlPassword);
                        } else {
                            self::$_vars[$key] = new $key();
                        }
                    } else if ($key == 'DJEMXml') {
                        self::$_vars[$key] = new $key();

                    } else {
                        if ($djem = self::Get('DJEM')) {
                            self::$_vars[$key] = new $key($djem);
                        } else {
                            self::$_vars[$key] = new $key();
                        }
                    }
                } else {
                    $pStr = '';
                    $pArray = array();
                    for ($i=1; $i<count($args);$i++) {
                        $pArray[] = '$args['.$i.']';
                    }
                    $pStr = implode(', ',$pArray);
                    eval('self::$_vars["' . $key . '"] = new ' . $key . '(' . $pStr . ');');
                }

            } else if (isset($MODULES[strtolower($key)])) {
                $module = $MODULES[strtolower($key)];
                $args = func_get_args();

                require_once($module['file']);
                if (count($args) === 1) {
                    if ($djem = self::Get('DJEM')) {
                        self::$_vars[$key] = new $key($djem);
                    } else {
                        self::$_vars[$key] = new $key();
                    }
                } else {
                    $pStr = '';
                    $pArray = array();
                    for ($i=1; $i<count($args);$i++) {
                        $pArray[] = '$args['.$i.']';
                    }
                    $pStr = implode(', ',$pArray);
                    eval('self::$_vars["' . $key . '"] = new ' . $key . '(' . $pStr . ');');
                }

            } else {
                return false;
            }
        }

        return self::$_vars[$key];
    }

    public static function Remove($key) {
        unset(self::$_vars[$key]);
    }

    public static function DumpVars() {
        var_dump(self::$_vars);
    }

    public static function Trace($var, $varName = false) {
        if (!isset(self::$_vars['Config'])) {
            self::Get('Config');
        }

        if (self::$_vars['Config']->debug != 1) {
            return false;
        }

        switch (self::$_vars['Config']->debugLevel) {
            case 'firephp':
                if ($varName) {
                    fb($var, $varName, FirePHP::DUMP);
                } else {
                    fb($var, rand(), FirePHP::DUMP);
                }
                break;
            case 'stdout':
                var_dump($var);
                break;
            case 'file':
                break;
            case 'uxo':
                break;

        }
    }

    public static function LoadFromCache($id, $folder = 'users', $params = array()) {
        $fileName = $_SERVER['DOCUMENT_ROOT'] . '/cache/' . $folder . '/' . $id . '.php';
        if (file_exists($fileName)) {
            foreach ($params as $key=>$value) {
                $$key = $value;
            }
            include($fileName);
        } else {
            return false;
        }
    }

    public function sendErrorPage($errorCode, $errorName = false, $errorText = false) {
        $ASKAPACHE_STATUS_CODES = array(
            '400' => 'Bad Request',
            '401' => 'Authorization Required',
            '402' => 'Payment Required',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '406' => 'Not Acceptable',
            '407' => 'Proxy Authentication Required',
            '408' => 'Request Time-out',
            '409' => 'Conflict',
            '410' => 'Gone',
            '411' => 'Length Required',
            '412' => 'Precondition Failed',
            '413' => 'Request Entity Too Large',
            '414' => 'Request-URI Too Large',
            '415' => 'Unsupported Media Type',
            '416' => 'Requested Range Not Satisfiable',
            '417' => 'Expectation Failed',
            '422' => 'Unprocessable Entity',
            '423' => 'Locked',
            '424' => 'Failed Dependency',
            '425' => 'No code',
            '426' => 'Upgrade Required',
            '500' => 'Internal Server Error',
            '501' => 'Method Not Implemented',
            '502' => 'Bad Gateway',
            '503' => 'Service Temporarily Unavailable',
            '504' => 'Gateway Time-out',
            '505' => 'HTTP Version Not Supported',
            '506' => ' Variant Also Negotiates',
            '507' => 'Insufficient Storage',
            '510' => 'Not Extended');
        if ($errorCode != 'Custom') {
            header('HTTP/1.0 ' . $errorCode . ' ' . $ASKAPACHE_STATUS_CODES[$errorCode]);
            header('Status: ' . $errorCode . ' ' . $ASKAPACHE_STATUS_CODES[$errorCode]);
        }
        include($_SERVER['DOCUMENT_ROOT'] . '/error_docs/' . $errorCode . '.phtml');
        exit();
    }
}

function sgp(
    $url,
    $varname,
    $value = NULL,
    $clean = TRUE
) {



    if (is_array($varname)) {
        foreach ($varname as $i => $n) {
            $v = (is_array($value))
                ? ( isset($value[$i]) ? $value[$i] : NULL )
                : $value;
            $url = sgp($url, $n, $v, $clean);
        }
        return $url;
    }

    $urlinfo = parse_url($url);

    $get = (isset($urlinfo['query']))
        ? $urlinfo['query']
        : '';

    parse_str($get, $vars);

    if (!is_null($value))        // одновременно переписываем переменную
        $vars[$varname] = $value; // либо добавляем новую
    else
        unset($vars[$varname]); // убираем переменную совсем

    $new_get = http_build_query($vars);

    if ($clean)
        $new_get = preg_replace( // str_replace() выигрывает
            '/=(?=&|\z)/',     // в данном случае
            '',                // всего на 20%
            $new_get
        );

    $result_url =   (isset($urlinfo['scheme']) ? "$urlinfo[scheme]://" : '')
        . (isset($urlinfo['host']) ? "$urlinfo[host]" : '')
        . (isset($urlinfo['path']) ? "$urlinfo[path]" : '')
        . ( ($new_get) ? "?$new_get" : '')
        . (isset($urlinfo['fragment']) ? "#$urlinfo[fragment]" : '')
    ;
    return $result_url;
}

function R($instanceName, $newValue = NULL) {
    if (is_null($newValue)) {
        return Registry::Get($instanceName);
    } else {
        return Registry::Set($instanceName, $newValue);
    }
}

$MODULES = array();
$MODULES['auth'] = array('class' => 'Auth', 'file' => 'modules/auth.php');
$MODULES['basket'] = array('class' => 'Basket', 'file' => 'modules/Basket.php');
$MODULES['tags'] = array('class' => 'Tags', 'file' => 'modules/Tags.php');

R('Config', new Config());
spl_autoload_register('R');

// функция для русских ссылок
function for_size($msort) {
    if (R('DJEM')->Load(R('DJEM')->Load($msort)->{'_parent_id'})->_type!=221) {
        $for_csv=str_replace(array(")","(",".","/","*","\\"),"",str_replace(array(" "," "),"_",R('DJEM')->Load($msort)->msort));
    } else {
        $for_csv=str_replace(array(")","(",".","/","*","\\"),"",str_replace(array(" "," "),"_",R('DJEM')->Load(R('DJEM')->Load($msort)->{'_parent_id'})->msort))."/".str_replace(array(")","(",".","/","*","\\"),"",str_replace(array(" "," "),"_",R('DJEM')->Load($msort)->msort));
    }
    return $for_csv;
}

function for_vid($msort) {

    $for_csv=str_replace(array(")","(",".","/","*","\\"),"",str_replace(array(" "," "),"_",R('DJEM')->Load(R('DJEM')->Load($msort)->_parent_id)->msort))."/index.phtml";

    return $for_csv;
}

function for_url($url) {
    return str_replace("index.phtml","",$url);
}

?>