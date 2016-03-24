<?php
/**
 * 微信登录
 * 
 * @author root
 */
class ThirdLoginWechat
{
	public $config = array();
	
	public function __construct()
	{
		if ($this->config = require(Yii::getPathOfAlias('system') . '/../config/thirdLogin.php'))
			$this->config = isset($this->config['ThirdLoginWechat']) ? (array)$this->config['ThirdLoginWechat'] : array();
		
		if (!$this->config)
			throw new CHttpException(404 , '没有配置!' , -789);
	}
	
	/**
	 * 请求授权
	 * 
	 * @param  string $redirect_uri
	 * @return void
	 */
	public function login($redirect_uri)
	{
		$root = 'https://open.weixin.qq.com/connect/qrconnect?';
		$data = array(
				'appid' 			=> $this->config['appid'],
				'redirect_uri'		=> $redirect_uri,
				'response_type'		=> 'code',
				'scope'				=> 'snsapi_login'
		);
		$url = $root.http_build_query($data).'#wechat_redirect';
		header('Location:'.$url);
	}
	
	/**
	 * 获取accessToken
	 * 
	 * @param  string $code
	 * @return array
	 */
	public function getAccessToken($code)
	{
		$root = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
		$data = array(
				'appid' 	=> $this->config['appid'],
				'secret' 	=> $this->config['secret'],
				'code'		=> $code,
				'grant_type'=> 'authorization_code'
		);
		$url  = $root.http_build_query($data);
		
		return $this->call($url);
	}
	
	/**
	 * 获取用户基本信息
	 * 
	 * @param  string 	$access_token
	 * @param  string 	$openid
	 * @return array
	 */
	public function getUser($access_token, $openid)
	{
		$url  = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}";
		$user = $this->call($url);
		$user['access_token'] = $access_token;
		
		return $user;
	}
	
	/**
	 * 调用接口
	 * 
	 * @param  string 	$url		请求地址
	 * @param  number 	$method		请求方法：0GET、1POST
	 * @param  array 	$param
	 * @return array 
	 */
	private function call($url, $method = 0, $param = array())
	{
		$res = WechatTool::call($url);
		return json_decode($res, true);		
	}
}

class WechatTool
{
	public static function call($url, $method = 0, $param = array())
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if ( $method == 1 && !empty($param) ){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
	
		return $output;
	}
}