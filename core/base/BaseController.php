<?php
/**
 * Created by PhpStorm.
 * User: wangyaqi
 * Date: 2017/12/13
 * Time: 23:11
 */
namespace core\base;

class BaseController
{
    public $assign = array();
    public function __construct()
    {
        return true;
    }
    protected function assign($name,$value){
        $this->assign[$name] = $value;
    }
    protected function display($fileName){
        $loader = new \Twig_Loader_Filesystem(VIEW_PATH);
        $twig = new \Twig_Environment($loader);
        echo $twig->render($fileName,$this->assign);
    }
}