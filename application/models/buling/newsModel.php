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
        $result = $this->db->query("SELECT count(id) as num,concat(YEAR(updated_at),'年',MONTH(updated_at),'月') as time FROM {$tableName} WHERE `level` >= 1  GROUP BY YEAR(`updated_at`) ASC,MONTH (`updated_at`) ASC")->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
    public function qunCountByTime(){
        $result = $this->db->query("SELECT count(id) as num,concat(MONTH(time),'月',DAY(time),'日') as time FROM qq_push_news WHERE `level` >= 1  GROUP BY YEAR(`time`) ASC,MONTH (`time`) ASC,DAY(`time`) ASC")->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}