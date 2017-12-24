<?php

/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/24
 * Time: 21:20
 */
namespace application\models\buling;
use core\base\BaseModel;

class newsModel extends BaseModel
{
    public function newsCountByTime($tableName){
        $result = $this->db->query("SELECT count(id) as num,concat(YEAR(updated_at),'-',MONTH(updated_at)) as time FROM {$tableName} WHERE `level` >= 1  GROUP BY YEAR(`updated_at`) ASC,MONTH (`updated_at`) ASC")->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function qunCountByTime(){

    }
}