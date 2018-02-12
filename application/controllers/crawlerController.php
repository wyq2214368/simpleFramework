<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/24
 * Time: 17:50
 */

namespace controllers;
use core\base\BaseController;
use core\log;
use QL\QueryList;
use application\models\wordpress\wpModel;
class crawlerController extends BaseController
{
    public function test(){
        $hj = QueryList::Query('http://mobile.csdn.net/',array("url"=>array('.unit h1 a','href')));
        $data = $hj->getData(function($x){
            return $x['url'];
        });
        echo "爬取CSDN(http://mobile.csdn.net/)首页文章链接：<pre>";
        print_r($data);
    }

    public function autoPost()
    {
        try
        {
            $db = new wpModel('baodingiot');
            $taskList = $db->getTaskList('*');
            foreach ($taskList as $task)
            {
                if ($task['img_match'] >= 2)
                {
                    for ($i=2;$i<=$task['img_match'];$i++)
                    {
                        $arr = explode('.html', $task['url']);
                        $url = $arr[0]."-$i.html";
                        $this->getPost($url, $task['term_id'], $task['list_match'], $task['title_match'], $task['content_match'], $task['img_match']);
                    }
                }
                else
                {
                    $this->getPost($task['url'], $task['term_id'], $task['list_match'], $task['title_match'], $task['content_match'], $task['img_match']);
                }
            }
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            log::error($e->getMessage());
        }
    }
    protected function saveImg($file_url, $save_to)
    {
        $content = file_get_contents($file_url);
        file_put_contents($save_to, $content);
    }
    protected function getPost($url, $term_id, $list_match, $title_match, $content_match, $img_match)
    {
        ini_set('memory_limit','512M');    // 临时设置最大内存占用为3G
        set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期

        $url = $url;
        $rules = array(
            'link' => array($list_match, 'html')
        );
        $res = QueryList::Query($url, $rules, '', 'UTF-8', 'GB2312');
        $data = $res->getData(function ($item) {
            $preg = '/<a .*?href="(.*?)".*>/is';
            preg_match_all($preg, $item['link'], $match);
            return $match[1][0];
        });
        $linkList = $data;
        foreach ($linkList as $item)
        {
            $db = new wpModel('baodingiot');
            $isExist = $db->getRecordByUrl($item, 'id');
            if (!empty($isExist))
                continue;
            $contentRules = array(
                'title' => array($title_match, 'text'),
                'content' => array($content_match, 'html'),
            );
            $article = QueryList::Query($item, $contentRules)->data;
            $title = $article[0]['title'];
            $content = $article[0]['content'];
//            print_r($res);die;
            $time = date('Y-m-d H:i:s');
            $gmtTime = date('Y-m-d H:i:s', strtotime('-8 hours'));
            $insertData = array(
                'post_author' => 1,
                'post_date' => $time,
                'post_date_gmt' => $gmtTime,
                'post_title' => $title,
                'post_content' => $content,
                'post_modified' => $time,
                'post_modified_gmt' => $gmtTime,
            );
            $res = $db->insertArticle($insertData);
            if ($res)
            {
                $id = $res;
                $relationshipData = array(
                    'object_id' => $id,
                    'term_taxonomy_id' => $term_id,
                    'term_order' => 0
                );
                $db->addRelationship($relationshipData);
                $preg = '/<img .*?src="(.*?)".*>/is';
                preg_match_all($preg, $content, $match);
                $recordData = array(
                    'url' => $item,
                    'post_id' => $id,
                    'title' => $title,
                    'have_img' => empty($match[1][0]) ? 0 : 1,
                    'date' => date('Y-m-d H:i:s'),
                    'term' => 1
                );
                $db->addRecord($recordData);
                if (!empty($match[1][0]))
                {
                    $metaData = array(
                        'post_id' => $id,
                        'meta_key' => 'auto_thumbnail',
                        'meta_value' => "thumbnail$id.jpg"
                    );
                    $db->addPostMeta($metaData);
                    $this->saveImg($match[1][0], dirname(ROOT_PATH)."\\baodingiot\\wp-content\\themes\\yusi1.0\\img\\pic\\thumbnail$id.jpg");
                }
            }
        }
    }

}