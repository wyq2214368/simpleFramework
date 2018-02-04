<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/15
 * Time: 19:47
 */

namespace core\base;

use core\config;
use Medoo\Medoo;

class BaseModel
{
    public $db;
    public function __construct($dataBaseName = null)
    {
        $conf = config::get('database');
        if (isset($dataBaseName)){
            $conf['database_name'] = $dataBaseName;
        }
        try{
            switch (strtolower($conf['driver'])){
                case 'medoo':
                    $this->db = new Medoo($conf);
                    break;
                case 'pdo':
                    $dsn="mysql:host={$conf['server']};dbname={$conf['database_name']}";
                    $pdo = new \PDO($dsn,$conf['username'],$conf['password'],[ 'charset' => 'utf8']);
                    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    $this->db = new \FluentPDO($pdo);
            }
        }catch (\PDOException $e){
            echo $e->getMessage();
        }
    }
}