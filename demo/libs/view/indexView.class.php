<?php
//视图类
    class indexView{
    	private $foo;
    	function __construct($foo = 'null') {
    		$this->foo = $foo;
    	}
    	function display($data = null){
    		if (!is_null($data)) {
                $View = VIEW::creat();
                $View->assign($data);
                $View->dispaly('/index/index.html');
    		}else{
    			System::show($this->foo);
    		}

    	}
    }
?>
