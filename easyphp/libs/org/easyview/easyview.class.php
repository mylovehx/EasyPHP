<?php

class easyview
{
	public $dir = '';
	public $url = './';
	public $tpl = '';
	public $data = array();
	public function __call($name, $arguments)
	{
		return '';
	}

	public function dispaly()
	{
		self::To($this->tpl);
		return $this->tpl;
	}

	public function loop( & $htmltext, & $code , & $arguments, & $func_in, & $func_ot)
	{

		self::select($htmltext,$func_in,$func_ot,$modeltext);
		//重复函数体模板
		if (!isset($arguments)) {
			$arguments = 0;
		}
		return str_repeat($modeltext,(int)$arguments);
	}

	public function Toarray( & $htmltext, & $code , & $arguments, & $func_in, & $func_ot)
	{
		self::select($htmltext,$func_in,$func_ot,$modeltext);
		$text = '';
		if (!isset($this->data[$arguments])) {
			return $text;
		}
		//重复函数体模板
		if (is_array($this->data[$arguments])) {
			$len = count($this->data[$arguments]);
			for ($i=0; $i < $len; $i++) { 
				$temp = $modeltext;
				if (!is_array($this->data[$arguments][$i])) {
					break;
				}
				foreach ($this->data[$arguments][$i] as $key => $value) {
					if (strpos($temp, '$'.$arguments.'.'.$key)) {
						$temp = str_replace('$'.$arguments.'.'.$key,$value,$temp);
					}
				}
				$text.=$temp = str_replace('$'.$arguments.'.#',$i+1,$temp);;
			}
		}
		return $text;
	}
	public function label( & $htmltext, & $code , & $arguments, & $func_in, & $func_ot)
	{
		if (isset($arguments)) {
			if (isset($this->data[$arguments])) {
				return $this->data[$arguments];
			}
		}
		return '';
	}

	public function load( & $htmltext, & $code , & $arguments, & $func_in, & $func_ot,$head = FALSE)
	{
		if (isset($arguments)) {
			$file    = $this->dir . $arguments;
			//取文件扩展名
			$ex      = pathinfo($file, PATHINFO_EXTENSION);
			switch ($ex) {
				case 'js':
				if ($head) {
					$htmltext = str_replace('</head>',$code."\r\n".'</head>',str_replace($code,'',$htmltext));
				}
				return '<script src="'.$this->url.$arguments.'" ></script>';
				case 'css':
				if ($head) {
					$htmltext = str_replace('</head>',$code."\r\n".'</head>',str_replace($code,'',$htmltext));
				}
				return '<link rel="stylesheet" type="text/css" href="'.$this->url.$arguments.'">';
				case 'jpg':
				case 'jpeg':
				case 'png':
				case 'bmp':
				case 'gif':
				case 'svg':
				return $this->url.$arguments;
				default:
				if (!is_file($file)) {
					return 'Can\'t find ('.$file.')';
				}
				//读取外部模板文件压缩外部文件大小
				return file_get_contents($file);
			}
		}
		return '';
	}
	public function css( & $htmltext, & $code , & $arguments, & $func_in, & $func_ot){
		return $this->load($htmltext,$code,$arguments,$func_in,$func_ot,TRUE);
	}

	function select(&$htmltext,$func_in,$func_ot, &$modeltext)
	{
		$strlen    = strlen($htmltext);
		$loop_in   = 0;
		$modeltext = '';
		$model     = '';
		$in_count  = 0;

		for ($in = $func_ot; $in < $strlen; ++$in) {
			if ($in < 3) {
				break;
			}
			//循环第一层判断函数体开头
			if ($loop_in == 0 && $htmltext[$in] == '{' && $htmltext[$in - 1] != '\\') {
				$loop_in = $in + 1;
			}

			if ($loop_in > 0 && $htmltext[$in] == '{' && $htmltext[$in - 1] != '\\') {
				++$in_count;
			}

			if ($loop_in > 0 && $htmltext[$in] == '}' && $htmltext[$in - 1] != '\\') {
				--$in_count;
			}

			//循环判断函数体结尾
			if ($loop_in > 0 && $htmltext[$in] == '}' && $htmltext[$in - 1] != '\\' && $in_count == 0) {
				$modeltext = substr($htmltext,$loop_in  ,$in - $loop_in );
				$model     = '{'.$modeltext.'}' ;
				break;
			}
		}
		//删除模板部分
		$htmltext = substr_replace($htmltext, '', $loop_in - 1,$in - $loop_in + 2);
	}
	function To(&$html_text){
	$funcname = '';
	$code     = '';
	$func_in  = 0;
	$agm_in   = 0;
	$_in  = 0;
	$count = 0;
	$ate = array();
	while (TRUE) {
		$in = strpos($html_text,'@');//寻找关键字并返回位置
		if ($in === FALSE) {
			break;
		}
		if ($_in == $in) {
			++$count;
		}
		//连续两次进入同一个位置则判定系统进入死循环,将关键词进行置换为空格,将位置记录在数组内,待解析完成替换回来;
		if ($count >= 2) {
			$html_text[$in] = ' ';
			$ate[] = $in;//将位置插入数组
			$count = 0;
			continue;
		}
		if ($in > 1) {
			if ($html_text[$in - 1] == '\\') {
				$html_text[$in - 1] = ' ';
				$html_text[$in] = ' ';
				$ate[] = $in;//将位置插入数组
				$count = 0;
				continue;
			}
		}
		$_in = $in;
		$funcname = $in;
		while (TRUE) {
			++$in;
			if (ctype_space($html_text[$in])) {
				break;
			}
			//判断函数括号位置
			if ($html_text[$in] == '(' ) {
				$agm_in   = $in + 1;
				$func_in  = $funcname;
				$funcname = substr($html_text,$funcname + 1,$in - $funcname - 1);
				continue;
			}
			else if ($agm_in > 0 &&$html_text[$in] == ')') {
				//判断函数尾部括号
				$code    = substr($html_text,$func_in ,$in - $func_in + 1);
				$funcarg = substr($html_text,$agm_in,$in - $agm_in);
				//分割自定义参数
				//$funcarg = explode(',',$funcarg);

				if (isset($html_text[$in+1])) {
					if ($html_text[$in+1] == '(') {
						$in_in = $in+1;
						$out_out = 0;
						$len = strlen($html_text) - $in+1;
						$li = 0;
						while ($li < $len) {
							if ($html_text[$in+1+$li] == ')' && $html_text[$in+$li] != '\\'){
								$dedaulttext = substr($html_text,$in+1,$in+1+$li - $in);
								$out_out = $in+1+$li;
								break;
							}
							++$li;
						}
					}
				}

				//载入模板函数对象
				//调用自定义函数变量,返回并替换函数
				//$funcname 自定义函数名
				$resou = $this->$funcname($html_text,$code,$funcarg,$func_in,$in);
				if (is_string($resou) && is_string($code)) {
					//判断是否有死循环代码(代码返回的数据本身包含自己)
					if (strpos($resou, $code)) {
						$resou = str_replace($code, '', $resou);
					}
					//判断是否有默认数据
					if (isset($dedaulttext)) {
						//判断是否需要使用默认数据
						if ($resou == '') {
							if (strpos($dedaulttext,'\\(')) {
								$dedaulttext = str_replace('\\(', '(',$dedaulttext);
							}
							if (strpos($dedaulttext,'\\)')){
								$dedaulttext = str_replace('\\)', ')', $dedaulttext);
							}
							$resou = substr($dedaulttext,1,strlen($dedaulttext)-2);
						}
						$html_text = substr_replace($html_text, '', $in+1,$in+1+$li - $in);
						unset($dedaulttext);
					}
					$html_text = substr_replace($html_text, $resou, $func_in,$in - $func_in + 1);
					//压缩TPL 开发的时候先不使用,方便维护
					//$html_text = strtr($html_text,array("\r\n"=>'',"\n"  =>'',"\r"  =>'',"\t"  =>''));
				}
				break;
			}
		}
	}
		//关键词复位
		foreach ($ate as $value) {
			$html_text[$value] = '@';
		}
		$html_text = str_replace('\\{', '{', str_replace('\\}', '}', $html_text));
	}
}

?>