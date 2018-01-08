<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/16
 * Time: 14:02
 */

namespace core;


class log
{
    public static function error($message,$file = 'log'){
        $conf = config::get('log');
        $path = $conf['log_path'];
        if(!is_dir($path)){
            mkdir($path,'0777',true);
        }
        $message = date('Y-m-d H:i:s').' '.json_encode($message, JSON_UNESCAPED_UNICODE).PHP_EOL;
        file_put_contents($path.$file.'.log',$message,FILE_APPEND);
    }
    public static function info($message,$file = 'log_info'){
        $conf = config::get('log');
        $path = $conf['log_path_info'];
        if(!is_dir($path)){
            mkdir($path,'0777',true);
        }
        $message = date('Y-m-d H:i:s').' '.json_encode($message, JSON_UNESCAPED_UNICODE).PHP_EOL;
        file_put_contents($path.$file.'.log',$message,FILE_APPEND);
    }
}