<?php
//视图类
    class indexView{
    	private $foo;
    	function __construct($foo = null) {
    		$this->foo = $foo;
    	}
    	function display($data = null){
    		if (!is_null($data)) {
    			System::show($data);
    		}else{
    			System::show($this->foo);
    		}

    	}
    }
?>
