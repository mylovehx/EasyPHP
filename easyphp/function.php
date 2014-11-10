<?php

    /**
    * 自动引入系统自定义函数/使用命名空间
    */
    class Sys
    {
      static public function load($name){
        require_once EASYROOT.'/libs/function/'.$name.'.class.php';
      }
    }
    //注册自动引入处理函数
    spl_autoload_register('Sys::load');
    //控制器
    function C($name,$method){
    if (defined('ROOT')) {
    	require_once ROOT.'/libs/controller/'.$name.'Controller.class.php';
    }else{
    	require_once EASYROOT.'/libs/controller/'.$name.'Controller.class.php';
    }
    $name = $name.'Controller';
    $obj  = new $name();
    return $obj->$method();
    }
    //模型
    function M($name){
    if (defined('ROOT')) {
    	require_once ROOT.'/libs/model/'.$name.'Model.class.php';
    }else{
    	require_once EASYROOT.'/libs/model/'.$name.'Model.class.php';
    }
    $name = $name.'Model';
    $obj  = new $name();
    return $obj;//返回模型对象
    }
    //视图
    function V($name,$argument = ''){
    if (defined('ROOT')) {
    	require_once ROOT.'/libs/view/'.$name.'View.class.php';
    }else{
    	require_once EASYROOT.'/libs/view/'.$name.'View.class.php';
    }
    $name = $name.'View';
    $obj  = new $name($argument);
    return $obj;//返回视图对象
    }
    //调用第三方类库
    function O($path,$name,$argument = null){
      require_once EASYROOT.'/libs/org/'.$path.'/'.$name.'.class.php';
      $obj = new $name();
      if (isset($argument)) {//判断是否需要对第三方类库赋值
        foreach ($argument as $key => $value) {
          $obj->$key = $value;
        }
      }
      return $obj;//返回第三方类库对象
    }
    //全局配置函数
    Global $Config;
    $Config = require_once EASYROOT .'/config.php';
    function Config($name){
    	Global $Config;
    	return $Config[$name];
    }
?>
