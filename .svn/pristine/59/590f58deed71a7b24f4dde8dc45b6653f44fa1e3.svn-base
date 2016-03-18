<?php
class WebModels extends ExtModels
{
	/**
	 * 将json格式的字符串解析
	 *
	 * @param		string		$json		json
	 */
	public function jsonDnCode($json)
	{
		return ($json && ($_temp = @json_decode($json,true)) && json_last_error()==JSON_ERROR_NONE) ? $_temp : array();
	}
	
	/**
	 * 获得基础的SQL
	 * @param		string		$key		表中字段名称
	 * @param		string		$val		字段的值
	 * @param		string		$type		查询类型	[LIKE , INTGROUP , ...]
	 * @param		string		$prefix		前缀
	 * @return		SQL
	 */
	public function getBaseSQL($key , $val , $type = 'EQUAL' , $prefix = '')
	{
		if (!$key || $val === null || (is_string($val) && trim($val)==='') || !$type)
			return '';
		
		$prefix = ($prefix && substr($prefix , -1) !== '.') ? ($prefix . '.') : $prefix;
		switch ($type)
		{
			case 'EQUAL'	: return $prefix . $key.'='.$this->quoteValue($val);
			case 'UNEQUAL'	: return $prefix . $key.'!='.$this->quoteValue($val);
			case 'LIKE'		: return "{$prefix}{$key} LIKE '%".substr($this->quoteValue($val) , 1 , -1)."%'";
			case 'INTGROUP'	:
				$val = is_array($val)?$val:array();
				$s = empty($val[0]) ? 0 : (int)$val[0];
				$e = empty($val[1]) ? 0 : (int)$val[1];
				if ($s && $e && $s > $e)
					list($s , $e) = array($e , $s);
	
				$SQL = array();
				if ($s) $SQL[] = "{$prefix}{$key}>={$s}";
				if ($e) $SQL[] = "{$prefix}{$key}<={$e}";
				return $SQL ? implode(' AND ', $SQL) : '';
		}
	}

	/**
	 * 获得 上传的图片地址
	 * @param	string			$src		上传到 临时文件夹 的地址
	 * @param	string			$path		转移到新的目录名称
	 * @param	null/string		$postfix	时间之后的后缀
	 * 
	 * @return	string			转移后图片的相对根目录路径
	 */
	public function getPhotos($src , $path , $postfix = null)
	{
		if (!$src)
			return '';
		
		//没有在临时文件夹下的文件不处理
		if (substr($src , 0 ,13) !== '/upload_temp/')
			return $src;
		
		$address = str_replace('/upload_temp/' , '/'.$path.'/' , $src);
		if ($postfix !== null && !preg_match('|/2\d{3}/[01]\d-[0123]\d/'.$postfix.'/|' , $address))
			$address = preg_replace('|(/2\d{3}/[01]\d-[0123]\d/)|' , ('${1}'.$postfix.'/') , $address);
		
		$curl = ClassLoad::Only('Curl');/* @var $curl Curl */
		if ($returned = $curl->postRequest(Yii::app()->params['imgDomain'].'moveFile.php' , array('old' => $src , 'new' => $address)))
		{
			if ($returned['code'] != 0)
				return '';
			
			$data = json_decode($returned['data'] , true);
			if (($errno = json_last_error()) === JSON_ERROR_NONE)
			{
				if ((int)$data['code'] === 0)
					return $address;
			}
		}
		return '';
	}
	
	/**
	 * 删除缓存数据
	 * @param	string/array	$cacheName		缓存名称
	 * @param	string			$suffix			后缀
	 */
	public function clearCache($cacheName , $suffix = '')
	{
		if (is_array($cacheName))
		{
			foreach ($cacheName as $cache)
			{
				if (is_string($cache))
					CacheBase::delete($cache , $suffix);
				elseif (is_array($cache))
				CacheBase::delete(isset($cache[0])?$cache[0]:'' , isset($cache[1])?$cache[1]:'');
			}
		}else{
			CacheBase::delete($cacheName , $suffix);
		}
	}
	
	/**
	 * 格式化输出josn对象
	 * @param		array		$data		数组
	 * @param		bool		$exit		是否输出并退出
	 */
	public function echojsonFormat(array $data , $exit = true)
	{
		if ($exit)
		{
			$data = array(
				'code'		=> 0,
				'message'	=> '',
				'data'		=> $data
			);
		}
		
		$data = json_encode($data ,  JSON_UNESCAPED_UNICODE);
		
		$ret = '';
		$pos = 0;
		$length = strlen($data);
		$indent = "\t";
		$newline = "\n";
		$prevchar = '';
		$outofquotes = true;
		
		for($i=0; $i<=$length; $i++){
		
			$char = substr($data, $i, 1);
		
			if($char=='"' && $prevchar!='\\'){
				$outofquotes = !$outofquotes;
			}elseif(($char=='}' || $char==']') && $outofquotes){
				$ret .= $newline;
				$pos --;
				for($j=0; $j<$pos; $j++){
					$ret .= $indent;
				}
			}
		
			$ret .= $char;
		
			if(($char==',' || $char=='{' || $char=='[') && $outofquotes){
				$ret .= $newline;
				if($char=='{' || $char=='['){
					$pos ++;
				}
		
				for($j=0; $j<$pos; $j++){
					$ret .= $indent;
				}
			}
			$prevchar = $char;
		}
		
		if ($exit)
		{
			header('content-type:application/json;charset=utf8');
			exit('<pre>'.chr(10).$ret.chr(10).'</pre>');
		}
		return $ret;
	}
	
	//获得登录用户ID , 这里是登录人ID , 所以 , 商家ID,子账号ID 都有可能
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	//获得登录人信息
	public function getUser()
	{
		return (($user = Yii::app()->getUser()->getName()) && is_array($user)) ? $user : array();
	}
	
	//获取商家ID
	public function getMerchantID()
	{
		if ($user = $this->getUser())
		{
			if ($user['user_type'] == 3)
			{
				return empty($user['merchant_id']) ? $user['id'] : $user['merchant_id'];
			}else{
				return $user['id'];
			}
		}
		return 0;
	}
}