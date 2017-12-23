<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/18
 * Time: 21:46
 */
use core\log;
/**
 * 自定义异常处理
 * @access public
 * @param mixed $e 异常对象
 */
function appException($e) {
    $error = array();
    $error['code'] = $e->getCode();
    $error['message'] = $e->getMessage();
    $error['resCode'] = 404;
    $trace = $e->getTrace();
    if ('E' == $trace[0]['function']) {
        $error['file'] = $trace[0]['file'];
        $error['line'] = $trace[0]['line'];
    } else {
        $error['file'] = $e->getFile();
        $error['line'] = $e->getLine();
    }
    // 发送404信息
    header('HTTP/1.1 404 Not Found');
    header('Status:404 Not Found');
    halt($error);
}
/**
 * 自定义错误处理
 * @access public
 * @param int $errno 错误类型
 * @param string $errstr 错误信息
 * @param string $errfile 错误文件
 * @param int $errline 错误行数
 * @return void
 */
function appError($errno, $errstr, $errfile, $errline) {
    switch ($errno) {
        case E_ERROR:
        case E_PARSE:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_USER_ERROR:
            ob_end_clean();
            $error['code'] = $errno;
            $error['message'] = $errstr;
            $error['file'] = $errfile;
            $error['line'] = $errline;
            $error['resCode'] = 500;
            halt($error);
            break;
        default:
            ob_end_clean();
            $error['code'] = $errno;
            $error['message'] = $errstr;
            $error['file'] = $errfile;
            $error['line'] = $errline;
            $error['resCode'] = 500;
            halt($error);
            break;
    }
}
// 致命错误捕获
function fatalError() {
    if ($e = error_get_last()) {
        $e['resCode'] = 500;
        switch ($e['type']) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                ob_end_clean();
                $this->halt($e);
                break;
        }
    }
}
/**
 * 错误输出
 * @param mixed $error 错误
 * @return void
 */
function halt($error) {
    log::error($error);
    include VIEW_PATH."/error/".$error['resCode'].'.html';
    exit;
}