<?php
//控制器
    class indexController{
    	function show(){
    		$data = M('index');
    		$view = V('index');
    		$view->display($data->get());
    	}
    }

?>
