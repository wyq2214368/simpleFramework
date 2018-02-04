<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/23
 * Time: 15:16
 */
namespace application\models\wordpress;
use core\base\BaseModel;

class wpModel extends BaseModel
{
    protected $table = 'bd_posts';
    // FluentPdo 使用示例
    public function insertArticle($data)
    {
        return $this->db->insertInto($this->table, $data)->execute();
    }
    public function addRelationship($data)
    {
        return $this->db->insertInto('bd_term_relationships', $data)->execute();
    }
    public function getRecordByUrl($url, $col)
    {
        return $this->db->from('bd_auto_record')
            ->select(null)
            ->select($col)
            ->where('url', $url)
            ->where('deleted', 0)
            ->fetch();
    }
    public function addRecord($data)
    {
        return $this->db->insertInto('bd_auto_record', $data)->execute();
    }
    public function addPostMeta($data)
    {
        return $this->db->insertInto('bd_postmeta', $data)->execute();
    }
    public function getTaskList($col)
    {
        return $this->db->from('bd_auto_task')
            ->select(null)
            ->select($col)
            ->where('deleted', 0)
            ->fetchAll();
    }

    // Pdo 使用示例
    public function testForPdo(){
        $sql = 'SELECT id,content FROM '.$this->table." WHERE `id` < 100  LIMIT 10";
        return $this->db->query($sql)->fetchAll();
    }
}