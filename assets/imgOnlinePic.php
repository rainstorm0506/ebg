<?php
	date_default_timezone_set('PRC');
	
	/**
	 * 获得远程的图片地址
	 * @author		涂先锋
	 * 
	 * @param		$_POST['src']			远程路径
	 * @param		$_POST['path']			转移到新的目录名称
	 * @param		$_POST['fix']			时间之后的后缀
	 * @param		$_POST['postfix']		默认的文件后缀
	 * 
	 * @return		{"code":(错误码 , 非0即错误) , "message":"错误信息"}
	 */
	$src		= empty($_POST['src']) ? '' : trim((string)$_POST['src']);
	$path		= empty($_POST['path']) ? '' : trim((string)$_POST['path']);
	$fix		= empty($_POST['fix']) ? '' : trim((string)$_POST['fix']);
	$postfix	= empty($_POST['postfix']) ? 'png' : trim((string)$_POST['postfix']);
	
	$returned = array('code' => 0 , 'message' => '' , 'src'=>'');
	
	if (!$src || !preg_match('/^https?:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i',$src))
	{
		$returned['code'] = 1;
		$returned['message'] = '文件地址错误!';
		exit(json_encode($returned));
	}
	
	$px = '';
	$fileParts = pathinfo($src);
	$fileParts = empty($fileParts['extension']) ? '' : strtolower($fileParts['extension']);
	if (in_array($fileParts , array('jpg','jpeg','gif','png','bmp')))
	{
		$px = $fileParts;
	}else{
		$px = strtolower($postfix);
	}
	
	$path = "{$path}/".date('Y/m-d').'/'.($fix ? $fix.'/' : '');
	@mkdir($path , 0777 , true);
	$path .= mt_rand().microtime(true) . '.' . $px;
	
	$ch = curl_init();
	curl_setopt($ch , CURLOPT_RETURNTRANSFER , true);
	curl_setopt($ch , CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch , CURLOPT_TIMEOUT, 20);
	curl_setopt($ch , CURLOPT_URL , $src);
	$data = curl_exec($ch);
	$errno = (int)curl_errno($ch);
	$httpCode = (int)curl_getinfo($ch , CURLINFO_HTTP_CODE);
	curl_close($ch);
	if ($errno == 0 && $httpCode == 200 && file_put_contents($path , $data))
	{
		$returned['src'] = '/'.$path;
		exit(json_encode($returned));
	}else{
		$returned['code'] = 2;
		$returned['message'] = '读取/写入文件失败!';
		exit(json_encode($returned));
	}
