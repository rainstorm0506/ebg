<?php
/**
 * 新浪微博
 * 
 * @author root
 *
 */

require 'config.php';
require 'saetv2.ex.class.php';

class Weibo
{
	/**
	 * 登录授权
	 * 
	 * @return string
	 */
	public function loginUrl()
	{	
		$o = new SaeTOAuthV2(WB_AKEY, WB_SKEY);
		return $o->getAuthorizeURL(WB_CALLBACK_URL);
	}
	
	/**
	 * 获取access_token
	 * 
	 * @param  string 	$key
	 * @return string
	 */
	public function getAccessTokenToCode($code)
	{
		$oAuth	= new SaeTOAuthV2(WB_AKEY, WB_SKEY);
		$param	= array(
				'code' 			=> $code,
				'redirect_uri'	=> WB_CALLBACK_URL
		);
		
		try 
		{
			$token	= $oAuth->getAccessToken('code', $param);
		}
		catch (OAuthException $e)
		{
			return '';
		}
		
		return $token;
	}
	
	/**
	 * 返回调用微博接口的对象
	 * 
	 * @param 	string 	$token
	 * @return	object
	 */
	public function apiObj($token)
	{
		return new SaeTClientV2(WB_AKEY, WB_SKEY, $token);
	}
	
}
?>