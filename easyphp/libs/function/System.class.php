<?php

/**
* 系统类函数
*/
class System
{
	static function show($name = ''){
		echo $name;//输出内容
	if (extension_loaded('zlib')) {
		ob_end_flush();
	}
	}
	static function dump($name = ''){
		var_dump($name);
	}
	/**
	* 压缩html : 清除换行符,清除制表符,去掉注释标记
	* @param undefined $string
	*
	* @return
	*/
	public static function compress_html(&$string)
	{
		$string = strtr($string,array("\r\n"=>'',"\n"  =>'',"\r"  =>'',"\t"  =>''));
		$string = strtr($string,array('  '=>''));
		$string = strtr($string,array('  '=>''));
		$string = strtr($string,array('  '=>''));
	}

	public static function addleach(&$name){
		if (!get_magic_quotes_gpc()) {
			foreach ($name as $key => $value) {
				$name[$key] = addcslashes ( $value ,  "\0..\37!@\177..\377\'\"*{}" );
			}
		}
	}
}

?>