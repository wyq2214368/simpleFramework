<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/13
 * Time: 22:20
 */
namespace core;
class router
{
    public function __construct()
    {
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/' && strpos($_SERVER['REQUEST_URI'],'/?')!==0 ){
            $path = $_SERVER['PATH_INFO'];
            $pathArr = explode('/',trim($path));
            if (isset($pathArr[1])){
                $this->controller = $pathArr[1];
            }
            if(isset($pathArr[2])){
                $this->action = $pathArr[2];
            }else{
                $this->action = 'index';
            }
            if(isset($pathArr[3])){
                $this->param = $pathArr[3];
            }
        }else{
            $this->controller = 'index';
            $this->action = 'index';
        }
    }
}