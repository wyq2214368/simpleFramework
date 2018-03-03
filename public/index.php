<?php
/**
 * Created by wangyaqi.
 * Date: 2017/12/12
 * Time: 23:39
 */
define('ROOT_PATH',dirname(__DIR__));
define('APP_PATH',ROOT_PATH.'/application');
define('CORE_PATH',ROOT_PATH.'/core');
define('VIEW_PATH',APP_PATH.'/views');
define('DEBUG',false);

include "../vendor/autoload.php";
include CORE_PATH.'/loader.php';
include CORE_PATH.'/functions.php';
if(DEBUG){
    ini_set('display_errors','On');
    $whoops = new \Whoops\Run;
    $handler = new \Whoops\Handler\PrettyPageHandler;
    $handler->setPageTitle('页面出错了');
    $whoops->pushHandler($handler);
    $whoops->register();
}else{
    ini_set('display_errors','Off');
    // 设定错误和异常处理
    //脚本结束时自动会调用这个函数,常用来捕获set_error_handler不能捕获到的致命错误
    register_shutdown_function('fatalError');
    //异常捕获
    set_exception_handler('appException');
    //用户自定义错误捕获，函数设置用户自定义的错误处理程序，然后触发错误（通过 trigger_error()）:
    set_error_handler('appError');
}

spl_autoload_register('\core\loader::load');
core\loader::run();