<?php
class SModels extends ExtModels
{
	//在调用 $this->execute() , 请设定这个变量 
	public $backLog = array(
		'table'			=> '',
		'response_id'	=> 0,
	);
	
	private $_inserID = 0;
	
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
	/**
	 * 安全过滤函数
	 *
	 * @param $string
	 * @return string
	 */
	public function safe_replace($string) {
		$string = str_replace('%20','',$string);
		$string = str_replace('%27','',$string);
		$string = str_replace('%2527','',$string);
		$string = str_replace('*','',$string);
		$string = str_replace('"','&quot;',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace('"','',$string);
		$string = str_replace(';','',$string);
		$string = str_replace('<','&lt;',$string);
		$string = str_replace('>','&gt;',$string);
		$string = str_replace("{",'',$string);
		$string = str_replace('}','',$string);
		$string = str_replace('\\','',$string);
		return $string;
	}
	/**
	 * xss过滤函数
	 *
	 * @param $string
	 * @return string
	 */
	public function remove_xss($string) {
		$string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);
		$parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
		$parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$parm = array_merge($parm1, $parm2);
	
		for ($i = 0; $i < sizeof($parm); $i++) {
			$pattern = '/';
			for ($j = 0; $j < strlen($parm[$i]); $j++) {
				if ($j > 0) {
					$pattern .= '(';
					$pattern .= '(&#[x|X]0([9][a][b]);?)?';
					$pattern .= '|(&#0([9][10][13]);?)?';
					$pattern .= ')?';
				}
				$pattern .= $parm[$i][$j];
			}
			$pattern .= '/i';
			$string = preg_replace($pattern, ' ', $string);
		}
		return $string;
	}
	
	//获得登录用户ID
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	//获得登录用户信息
	public function getUser()
	{
		return (array)Yii::app()->getUser()->getName();
	}
	
	private function getReToken()
	{
		static $_token = 0;
		if ($_token > 0)
			return $_token;
		
		return $_token = mt_rand(1000000 , 9999999);
	}
	
	private function changeLog($action , $table , $responseID = 0 , array $columns = array() , $conditions = '' , $SQL = '')
	{
		$contr			= Yii::app()->getController();
		$route			= $contr->getId() . '/' . $contr->getAction()->getId();
		
		$responseID		= $responseID ? $responseID : (empty($this->backLog['response_id']) ? 0 : $this->backLog['response_id']);
		if (!$responseID && $conditions)
		{
			//模糊去匹配响应的ID
			preg_match('/id=(\d+)/', $conditions , $out);
			$responseID = empty($out[1]) ? 0 : $out[1];
		}
		
		$u = $this->getUser();
		parent::insert('back_log' , array(
			'gid'			=> $this->getUid(),
			'gov_name'		=> $u['branch_name'] . ' - ' . $u['true_name'],
			'route'			=> $route,
			'response_id'	=> $responseID,
			'time'			=> time(),
			'change_data'	=> json_encode(array(
				'table'			=> $table,
				'id'			=> $responseID,
				'action'		=> $action,
				'columns'		=> $columns,
				'conditions'	=> $conditions,
				'SQL'			=> $SQL
			)),
			'token'			=> $this->getReToken(),
		));
	}
	
	public function getInsertId()
	{
		$_id = $this->_inserID;
		$this->_inserID = -1;
		return $_id;
	}
	
	/**
	 * 执行无查询SQL
	 * @param	string		$SQL		SQL
	 * @param	array		$params		为SQL执行的输入参数 (name=>value)
	 * @return	integer					返回此操作影响的行数
	 */
	public function execute($SQL , array $params = array())
	{
		$this->changeLog('execute' , (empty($this->backLog['table']) ? '' : $this->backLog['table']) , 0 , array() , '' , $SQL);
		return parent::execute($SQL , $params);
	}
	
	/**
	 * 插入
	 * @param	string		$table		将被插入的表
	 * @param	array		$columns	要插入表的列数据(name=>value)
	 * @return	integer					返回此操作影响的行数
	 */
	public function insert($table , array $columns)
	{
		$result = parent::insert($table , $columns);
		$this->_inserID = parent::getInsertId();
		$this->changeLog('insert' , $table , $this->_inserID , $columns);
		return $result;
	}
	
	/**
	 * 更新
	 * @param	string		$tabName		将被插入的表
	 * @param	array		$columns		要更新的列数据(name=>value)
	 * @param	mixed		$conditions		放入 WHERE 部分的条件
	 * @param	array		$params			要绑定到此查询的参数
	 * @return	integer						返回此操作影响的行数
	 */
	public function update($table , array $columns , $conditions = '', array $params = array())
	{
		$this->changeLog('update' , $table , 0 , $columns , $conditions);
		return parent::update($table , $columns , $conditions , $params);
	}
	
	/**
	 * 删除
	 * @param	string		$tabName		将被插入的表
	 * @param	mixed		$conditions		放入 WHERE 部分的条件
	 * @param	array		$params			要绑定到此查询的参数
	 * @return	integer						返回此操作影响的行数
	 */
	public function delete($table , $conditions = '', array $params = array())
	{
		$this->changeLog('delete' , $table , 0 , array() , $conditions);
		return parent::delete($table , $conditions , $params);
	}
}