<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/24
 * Time: 17:50
 */

namespace controllers;
use core\base\BaseController;
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
}