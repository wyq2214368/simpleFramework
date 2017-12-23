<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/23
 * Time: 15:16
 */
namespace application\models\test;
use core\base\BaseModel;

class testModel extends BaseModel
{
    protected $table = 'sf_test';
    // Medoo 使用示例
    public function testForMedoo(){
        return $this->db->select($this->table,['id','content'],['id[<]'=>100, 'LIMIT'=>10]);
    }
    // Pdo 使用示例
    public function testForPdo(){
        $sql = 'SELECT id,content FROM '.$this->table." WHERE `id` < 100  LIMIT 10";
        return $this->db->query($sql)->fetchAll();
    }
}