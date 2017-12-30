<?php

/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/13
 * Time: 23:06
 */
namespace controllers;
use application\models\buling\newsModel;
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
        $list = array(
            "xiaoxin"=>"小信",
            "mangguo"=>"芒果",
            "xingkong"=>"星空",
            "biaobaiqiang"=> "表白墙",
        );

        $json = <<<json
{
	"tooltip": {
		"trigger": "axis"
	},
	"legend": {
		"data": ["小信","芒果","星空","表白墙"]
	},
	"toolbox": {
		"show": true,
		"feature": {
			"dataView": {
				"show": true,
				"readOnly": false
			},
			"magicType": {
				"show": true,
				"type": [
					"line",
					"bar",
					"stack",
					"tiled"
				]
			},
			"restore": {
				"show": true
			},
			"saveAsImage": {
				"show": true
			}
		}
	},
	"calculable": true,
	"xAxis": [
		{
			"type": "category",
			"data": []
		}
	],
	"yAxis": [
		{
			"type": "value",
			"name":"消息条数"
		}
	],
	"series": []
}
json;
        $option = json_decode($json,true);
        $option2 = $option;
        $option3 = $option;
        $option4 = $option;
        $pieData = array();
        $model = new newsModel();
        foreach ($list as $key => $value){
            $ret = $model->newsCountByTime($key);
            $xData = array();
            $yData = array();
            $tmpData = 0;
            $series['name'] = $value;
            $series['type'] = "line";
            $series['itemStyle']['normal']['label']['show'] = true;
            foreach ($ret as $item){
                $tmpData += $item['num'];
                $xData[] = $item['time'];
                $yData['line'][] = $tmpData;
                $yData['bar'][] = $item['num'];
            }
            $pieData[] = ['value'=>$tmpData,'name'=>$value];
            $series['data'] = $yData['line'];
            $option['series'][] = $series;
            // No.2 增量图
            $series['type'] = "bar";
            $series['data'] = $yData['bar'];
            $option2['series'][] = $series;
        }
        $option['title']['text'] = "消息平台收录统计";
        $option['xAxis'][0]['data'] = $xData;

        $option2['title']['text'] = "消息月增长量面板";
        $option2['xAxis'][0]['data'] = $xData;

        $ret = $model->qunCountByTime();
        $xData = array();
        $yData = array();
        $tmpData = 0;
        $series['name'] = '群消息数量';
        $series['type'] = "line";
        $series['itemStyle']['normal']['label']['show'] = false;
        foreach ($ret as $item){
            $tmpData += $item['num'];
            $xData[] = $item['time'];
            $yData['line'][] = $tmpData;
            $yData['bar'][] = $item['num'];
        }
        $pieData[] = ['value'=>$tmpData, 'name'=> 'QQ群'];
        $series['data'] = $yData['line'];
        $option3['series'][] = $series;
        // No.2 增量图
        $series['name'] = "群消息日增量";
        $series['type'] = "bar";
        $series['data'] = $yData['bar'];
        $series['yAxisIndex'] = 1;
        $option3['series'][] = $series;
        $option3['title']['text'] = "QQ群消息统计面板";
        $option3['xAxis'][0]['data'] = $xData;
        $option3['yAxis'] = array(
            array(
                'type' => 'value',
                'name' => '消息总数'
            ),
            array(
                'type' => 'value',
                'name' => '消息增量'
            ),
        );
        $option3['legend']['data'] = ['群消息数量','群消息日增量'];
//        var_dump($option3);die;

        unset($option4['xAxis']);
        unset($option4['yAxis']);
        $option4['tooltip'] = array(
            'trigger' => 'item',
            'formatter' => "{a} <br/>{b} : {c} ({d}%)"
        );
        $option4['legend'] = array(
            'orient' => 'vertical',
            'x' => 'left',
            'data' => ['小信','芒果','星空','表白墙','QQ群']
        );
        $option4['series'][] = array(
            'name' => '消息来源',
            'type' => 'pie',
            'radius' => '55%',
            'center' => ['50%', '60%'],
            'data' => $pieData
        );
        $option4['title']['text'] = "消息比例";
        $option4['title']['x'] = "center";

        $this->assign('option1',json_encode($option));
        $this->assign('option2',json_encode($option2));
        $this->assign('option3',json_encode($option3));
        $this->assign('option4',json_encode($option4));
        $this->display('buling/count.html');
    }
}