<?php
	class DB {
		static $localhost;
		static $user;
		static $passwd;
		static $dbname;
		static $code;
		private static $db;
		private static $instance;
		function __construct($localhost = null,$user = null,$passwd = null,$dbname = null) {
			if (isset($localhost)) {
				self::$localhost = $localhost;
			}
			if (isset($user)) {
				self::$user = $user;
			}
			if (isset($passwd)) {
				self::$passwd = $passwd;
			}
			if (isset($dbname)) {
				self::$dbname = $dbname;
			}
			//调用父类构造函数
			self::$db = mysqli_connect(self::$localhost,self::$user,self::$passwd,self::$dbname);
			mysqli_select_db(self::$db,self::$dbname);
			mysqli_query(self::$db,'SET NAMES '.self::$code);
		}
		//返回数据库对象可以定义数据库类名
		// singleton 方法
		public static function creat()
		{
			if (!isset(self::$instance)) {
				$c = __CLASS__;
				self::$instance = new $c();
			}
			return self::$instance;
		}
		function query($sql){
			return mysqli_query(self::$db,$sql);
		}
		function oneData($table,$field = '*',$where = null,&$result = null){
			$arr = array();
			if (is_array($field)) {
				$field    = implode(',',$field);
			}
			if (is_array($where)) {
				$where   = implode($tab,$where);
			}
			if (!is_null($where)) {
				$where = ' Where '.$where;
			}
			$resource = self::query('Select '.$field.' from '.$table.$where .' limit 1');
			if ($resource !== false) {
				while ($row = mysqli_fetch_array($resource,MYSQL_ASSOC)) {
					$arr[] = $row;
				}
			}
			return $arr;
		}
	}
?>