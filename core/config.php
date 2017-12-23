<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/15
 * Time: 22:14
 */

namespace core;

class config
{
    public static $conf = array();
    private static $type = '.php';

    public static function get($key){
        $arr = explode('.',$key);
        $file = $arr[0];
        if(empty(self::$conf[$file])){
            $path = ROOT_PATH.'/config/'.$file.self::$type;
            if (is_file($path)) {
                $conf = include $path;
                self::$conf[$file] = $conf;
            }
        }
        $tmpConf = self::$conf;
        foreach ($arr as $item){
            if (isset($tmpConf[$item])){
                $tmpConf = $tmpConf[$item];
            }else{
                throw new \Exception('未找到配置项'.$item);
            }
        }
        return $tmpConf;
    }
}