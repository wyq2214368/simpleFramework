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
    public function torrent($film)
    {
        $key = $film;
        if (empty($key))
        {
            echo "参数错误，请暂时按规则请求，url最后输入查询资源名，例：http://test.buling.club/crawler/torrent/前任3";
        }
        $p = 1;
        $domain = "http://www.btyunsou.me";
        $url = $domain . "/search/{$key}_ctime_{$p}.html";

        $rules = array(
            'list'=>array(".media-list>li",'html')
        );
        $datas = QueryList::Query($url,$rules)
            ->getData(function($list){
                foreach ($list as $item)
                {
                    $rules = array(
                        'name' => array(".media-body>h4>.title","text"),
                        'size' => array(".label-warning","text"),
                        'rank' => array(".label-primary","text"),
                        'link' => array("a.title","href"),
                    );
                    $data = QueryList::Query($item, $rules)->data[0];
                    $link = $data['link'];
                    $data['link'] = "magnet:?xt=urn:btih:" . substr($link,1,strpos($link, '.html')-1);
                }
                return $data;
            });
        log::info(urldecode($key), 'torrent');
        $this->assign('list', $datas);
        $this->display('buling/torrent.html');
    }
}