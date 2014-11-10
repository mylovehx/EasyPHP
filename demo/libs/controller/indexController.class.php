<?php
//控制器
    class indexController{
    	function show(){
    		$data = M('index');
    		$view = V('index');
            $arr = $data->get();
    		$re = array(
                
                'list'=>array($arr),
                'WEB-NAME'=>'EDUCATION 教育学院'

                );
    		$view->display($re);
    	}
    }

?>
