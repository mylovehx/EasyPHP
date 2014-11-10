<?php
	class VIEW {
		public static $tplPath = '';//引擎调用模板的路径
		private static $_type = 'easyview';
		private $_assign = array();//引擎调用的数据
		private static $vi;//引擎对象(静态)
		function __construct() {
			//初始化
			if (defined('ROOT')) {
				self::$tplPath = ROOT . '/libs/templet./';
			}else{
				self::$tplPath = EASYROOT . '/libs/templet./';
			}
		}
		//返回数据库对象可以定义数据库类名
		static public function creat($name = __CLASS__,$type = null){
			//$type默认启用视图引擎类型,系统默认自带引擎也可以用第三方引擎
			if (isset($type)) {
				self::$_type = $type;
			}
			if (!isset(self::$vi)) {
				self::$vi = new $name();
			}
			return self::$vi;
		}

		public function assign($name = array(),$add = false){
			if ($add) {
				$this->_assign[] = $name;
			}else{
				$this->_assign = $name;
			}

		}

		public function dispaly($name = '/index/index.html'){
			if (self::$_type == 'easyview') {
				$config = array();
				$config['dir'] = self::$tplPath;
				$config['url'] = './';
				$config['tpl'] = self::getfile($name);
				$config['data'] = $this->_assign;
				$engine = O(self::$_type,self::$_type,$config);
				System::show($engine->dispaly());
			}
		}

		static function getfile(&$name){
			return file_get_contents(self::$tplPath.$name);
		}
	}

?>