<?php
	define('EASYROOT',str_replace('\\', '/', realpath(dirname(__FILE__))));

    //gzip压缩程序
	function ob_gzip($content) // $content 就是要压缩的页面内容，或者说饼干原料
	{
		if ( !headers_sent() && // 如果页面头部信息还没有输出
			extension_loaded("zlib") && // 而且zlib扩展已经加载到PHP中
			strpos($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip") !== FALSE)//而且浏览器说它可以接受GZIP的页面
		{
			$content = gzencode($content,9);//此页已压缩”的注释标签，然后用zlib提供的gzencode()函数执行级别为9的压缩，这个参数值范围是0 - 9，0表示 无压缩，9表示最大压缩，当然压缩程度越高越费CPU。
			//然后用header()函数给浏览器发送一些头部信息，告诉浏览器这个页面已经用GZIP压缩过了！
			header("Content-Encoding: gzip");
			header("Vary: Accept-Encoding");
			header("Content-Length: ".strlen($content));
			header("content-Type: text/html; charset=utf-8");
			
			/* 页面级缓存
			date_default_timezone_set('PRC');
			$cache = date('Ymdhi');
			if ($_SERVER["HTTP_IF_NONE_MATCH"] == $cache)
			{
				header('Etag:'.$cache,true,304);
				exit();
			}
			else {
				header('Etag:'.$cache);
			}
			*/

		}
		return $content; //返回压缩的内容，或者说把压缩好的饼干送回工作台。
	}
	if (extension_loaded('zlib')) {
		ob_start('ob_gzip');
	}
	
	include(EASYROOT."./function.php");
	//过滤函数
	System::addleach($_GET);
	System::addleach($_POST);

	//初始化默认配置

	DB::$localhost = Config('Mysql-Localhost');
	DB::$user  = Config('Mysql-User');
	DB::$passwd= Config('Mysql-Passwd');
	DB::$dbname= Config('Mysql-DBName');
	DB::$code= Config('Mysql-Code');

	//判断控制函数名,方法名是否有传递没有则默认 index or show
	$controller= isset($_GET['controller'])?$_GET['controller']:'index';
	$method= isset($_GET['method'])?$_GET['method']:'show';
	//允许调用的方法集合
	$func = array('index','show');
	//判断调用的方法是否在允许调用的方法范围内
	if (in_array($controller, $func) && in_array($method, $func)) {
		//调用控制器
		C($controller,$method);
	}else{
		System::show("easy engine controller or method in error");
	}
?>
