<?php

class Identity
{
	/**
	 * 验证 字符串是否是一个身份证号码
	 * @param		string			$identity		身份证号码
	 * @return		string|true
	 */
	public static function check($identity)
	{
		if (!in_array(strlen($identity) ,array(15 , 18)))
			return '身份证号码位数不对(15/18位).';
		if (CDateTimeParser::parse(substr($identity , 6 , 8) ,'yyyyMMdd') === false)
			return '身份证号码中的出生日期错误';
		if (strlen($identity) === 18 && !self::checkIdentity($identity))
			return '身份证验证错误';
		return true;
	}
	
	/**
	 * 验证身份证号码
	 * @param		string			$identity		身份证号码
	 * @return		boolean
	 */
	public static function checkIdentity($identity)
	{
		$identity	= strtoupper($identity);
		$iW			= array(7 , 9 , 10 , 5 , 8 , 4 , 2 , 1 , 6 , 3 , 7 , 9 , 10 , 5 , 8 , 4 , 2);
		$szVerCode	= array(1 , 0 , 'X' , 9 , 8 , 7 , 6 , 5 , 4 , 3 , 2);
		$sum = 0;
		for ($i = 0 ; $i < 17 ; $i++)
			$sum += $identity[$i] * $iW[$i];
		return $szVerCode[$sum % 11] == $identity[17];
	}
}