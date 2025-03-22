<?php
class WorkDb{
    public $db;
    public $dbParameters;
    public $response = array();
    public $pathToConfigDb = '';
    public function __construct($space){
        if(!is_file($this->pathToConfigDb = ROOT.'/mainclasses/config/'.$space.'.ini')) throw new Exception('Не найден конфигурационный файл');
        else $this->pathToConfigDb = ROOT.'/mainclasses/config/'.$space.'.ini';
        $this->connectDB();
    }
    /**
     *  Работа с Базой Данных
     */
    /*******************************************************************************************************************/
    /**
     * Функция подключения Базы Данных
     * @var $this->dbParameters - Имя Базы Данных
     * @return $this
     */
    private function connectDB(){
        try{
            $this->db = new MyPDO(
                $this->pathToConfigDb
            );
            $this->db->exec("set names utf8mb4");
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //$this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        }catch (PDOException $e){
            $this->response = Array(
                "success" => false,
                "reason" => $e->getMessage()
            );
        }
        return $this;
    }
}

class MyPDO extends PDO
{
    public function __construct($file = '/mainclasses/config/dbParameters.ini')
    {
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable to open ' . $file . '.');

        $dns = $settings['database']['driver'] .':'. 'dbname=' . $settings['database']['schema'].
            ';'.'host=' . $settings['database']['host'].
            ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '');

        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
    }
}
