<?php
class Verify
{
	/**
	 * 16-19 位银行卡号校验方法
	 * 
	 * 校验采用 Luhm方法计算：
	 * 1, 将除开校验位 的 其他卡号位从右依次编号 1 到 15
	 * 2, 位于奇数位号上的数字乘以 2 , 将奇位乘积的个十位全部相加
	 * 3, 再加上所有偶数位上的数字
	 * 4, 将和在加上校验位 , 检查是否能被 10 整除。
	 * 
	 * @param		string		$code		银行卡号 [16-19位卡号]
	 * @return		NULL|boolean
	 */
	public static function luhm($code)
	{
		if (!preg_match('/^\d{16,19}$/' , $code))
			return null;
		
		$n = 0;
		foreach (str_split(strrev($code)) as $k => $v)
			$n += ($k > 0) ? (($k % 2 == 0) ? $v : (($v / 5 >= 1) ? (1 + $v * 2 % 10) : ($v * 2))) : $v;
		
		return ($n % 10) == 0;
	}
	
	/**
	 * 检查是不是一个32的MD5值
	 * @param		string		$str		待验证的字符串
	 * @return		boolean
	 */
	public static function isMd5($str)
	{
		return (bool)preg_match('/^[0-9a-fA-F]{32}$/' , $str);
	}
	
	/**
	 * 检查一个号码是否是手机号码
	 * @param		string		$phone		手机号码
	 * @return		boolean
	 */
	public static function isPhone($phone)
	{
		return (bool)preg_match('/^(13\d|18\d|14[57]|15[012356789]|17[0678])\d{8}$/' , (string)$phone);
	}
	
	/**
	 * 验证 字符串是否是密码格式
	 * @param		string			$password		密码字符串
	 * @return		boolean
	 */
	public static function isPassword($password)
	{
		return (bool)preg_match('/^[a-zA-Z]\w{5,17}$/' , $password);
	}
	
	/**
	 * 检查是否是一个邮箱地址格式
	 * @param	string		$email		邮箱地址
	 */
	public static function isMail($email)
	{
		return (bool)preg_match('/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/' , $email);
	}
	
	/**
	 * 验证 字符串是否是一个身份证号码
	 * @param		string			$identity		身份证号码
	 * @return		string|true
	 */
	public static function check($identity)
	{
		return Identity::check($identity);
	}
	
	/**
	 * 验证身份证号码
	 * @param		string			$identity		身份证号码
	 * @return		boolean
	 */
	public static function checkIdentity($identity)
	{
		return Identity::checkIdentity($identity);
	}
}