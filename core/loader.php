<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/13
 * Time: 21:27
 */
namespace core;
class loader
{
    public static $classMap = array();
    public static function run(){
        $router = new \core\router();
        $controller =$router->controller;
        $action = $router->action;
        $controller = 'crawler';
        $action = 'autopost';
        $param = isset($router->param)?$router->param:null;
        $controllerFile = APP_PATH.'/controllers/'.$controller.'Controller.php';
        $controllerClass = '\controllers\\'.$controller.'Controller';
        if (is_file($controllerFile)){
            include $controllerFile;
            $controllerObj = new $controllerClass();
            if(method_exists($controllerObj,$action)){
                $controllerObj->$action($param);
            }else{
                throw new \Exception("action {$action} not found");
            }
        }else{
            throw new \Exception("controller {$controller} not found");
        }
    }
    public static function load($class){
        if(isset($classMap[$class])){
            return true;
        }else{
            $file = ROOT_PATH.'\\'.$class.'.php';
            if (is_file($file)){
                include $file;
                self::$classMap[$class] = $class;
            }else{
                return false;
            }
        }
    }
}