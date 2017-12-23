<?php

/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/23
 * Time: 18:12
 */
namespace core\common\http;
class Request
{
    /**
     * 从POST/GET获取参数
     */
    public static function request($name, $defaultValue = null)
    {
        return isset($_REQUEST[$name]) ? $_REQUEST[$name] : $defaultValue;
    }
    /**
     * 从POST获取参数
     */
    public static function post($name, $defaultValue = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
    }
    /**
     * 从GET获取参数
     */
    public static function get($name, $defaultValue = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
    }
}