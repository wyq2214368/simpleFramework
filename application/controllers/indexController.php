<?php

/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/13
 * Time: 23:06
 */
namespace controllers;
use core\base\BaseController;
use application\models\test\testModel;
use core\common\http\Request;
use core\log;
use core\config;
class indexController extends BaseController
{
    public function index(){
        // 页面渲染 示例
        $param = Request::get('data','Hello World!');
        $this->assign('data',$param);
        $this->display('test.html');


        // 模型类 示例     可通过配置文件config/database.php设置数据库连接参数
        $model = new testModel();
        $result = '';
        // 默认使用的轻量级数据库操作类Medoo，详细使用可访问Medoo官网，或更换pdo，在配置文件中修改driver的值)
        if (config::get('database.driver')=='medoo'){
            $result = $model->testForMedoo();

            // 配置类 示例 通过config::get()方法或取配置文件的配置信息
        }elseif (config::get('database.driver')=='pdo'){
            $result = $model->testForPdo();
        }
//            var_dump($result);

        //错误日志
        try{
//            $i = 1/0;
        }catch (\Exception $e){
            // 日志 示例    默认路径为项目根目录，可通过配置文件config/log.php修改路径
            // 入口文件index.php中debug为true时，开启debug模式，异常由whoops处理；当关闭debug模式时跳转错误页面
            log::error($e->getMessage());
        }
    }
    public function echart(){
        $this->display('test/echart.html');
    }
}