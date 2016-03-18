<?php
class Curl
{
	#超时
	public $timeOut = 5;
	#最长等待时间
	public $connecTimeOut = 2;
	#以文件流的方式返回
	public $returnTransfer = true;
	
	/**
	 * TODO 待实现
	 */
	public function getRequest($url , array $get = array())
	{
		
	}
	
	/**
	 * 以 post 请求 CURL
	 * @param		string		$url		URL
	 * @param		array		$post		post
	 */
	public function postRequest($url , array $post = array())
	{
		$returned = $this->baseCurlRequest($url , 'post' , $post);
		
		$data = array('code'=>0 , 'type'=>'data' , 'message'=>'' , 'data'=>'');
		if($returned['curl_erron'])
		{
			$data['code'] = $returned['curl_erron'];
			$data['type'] = 'curl';
			$data['message'] = '数据请求错误 , 错误码 : '.$returned['curl_erron'];
			$this->setErrorLog($url , $data);
		}else{
			if ($returned['http_erron'] != 200)
			{
				$data['code'] = $returned['http_erron'];
				$data['type'] = 'http';
				$data['message'] = 'http 状态错误 , 状态码 : ' . $returned['http_erron'];
				$this->setErrorLog($url , $data);
			}else{
				$data['data'] = $returned['return'];
			}
		}
		return $data;
	}
	
	/**
	 * 写错误请求日志
	 * @param	string		$url
	 * @param	array		$data
	 * @return	boolean
	 */
	private function setErrorLog($url , array $data)
	{
		if (empty(Yii::app()->params['CurlErrorPath']))
			return false;
	
		$path = Yii::app()->params['CurlErrorPath'].date('Y/m-d/');
		@mkdir($path , 0777 , true);
	
		$data['url'] = $url;
		return (bool)file_put_contents($path.microtime(true).'_'.$data['type'].'_'.mt_rand(100000 , 999999) , serialize($data));
	}
	
	private function baseCurlRequest($url , $type , array $data)
	{
		$ch = curl_init();
		curl_setopt($ch , CURLOPT_RETURNTRANSFER , $this->returnTransfer);			#以文件流的方式返回
		curl_setopt($ch , CURLOPT_CONNECTTIMEOUT, $this->connecTimeOut);			#最长等待时间
		curl_setopt($ch , CURLOPT_TIMEOUT, $this->timeOut);							#超时
		if ($type == 'post')
		{
			curl_setopt($ch , CURLOPT_POST , true);									#post
			curl_setopt($ch , CURLOPT_POSTFIELDS , http_build_query($data));		#post , 兼容写法
		}else{
			$url .= '?' . http_build_query($data);
		}
		curl_setopt($ch , CURLOPT_URL , $url);
		$data = curl_exec($ch);
		$errno = (int)curl_errno($ch);
		$httpCode = (int)curl_getinfo($ch , CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		return array('return'=>$data , 'curl_erron'=>$errno , 'http_erron'=>$httpCode);
	}
}