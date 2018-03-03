<?php

/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2018/3/2
 * Time: 22:57
 */
namespace controllers;
use core\base\BaseController;
use core\common\http\Request;
class sourceController extends BaseController
{
    private $tpl;
    public function html($tpl)
    {
        $param = Request::get('data', '');
        switch ($tpl)
        {
            case 'bannerbg':
                if (empty($param))
                    $this->tpl = 'bannerbg/type'.rand(1,12);
                else
                    $this->tpl = 'bannerbg/'.$param;
                break;

        }
        $this->assign('data', $param);
        $this->display("source/html/$this->tpl.html");
    }
}