<?php
class String
{
	/**
	 * 字符串截断
	 * @param		string		$string		字符串
	 * @param		int			$length		截断长度
	 * @param		string		$etc		后缀
	 */
	public static function utf8Truncate($string , $length , $etc = '...')
	{
		if (!is_string($string) && strlen($string) <= $length)
			return $string;
		
		$string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
		$strlen = strlen($string);
		if (function_exists('mb_substr'))
			return mb_substr($string , 0 , $length , 'utf-8') . ($strlen < $length ? '' : $etc);
		
		$result = '';
		for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
		{
			if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
			{
				if ($length < 1.0)
					break;
				$result .= substr($string, $i, $number);
				$length -= 1.0;
				$i += $number - 1;
			}else{
				$result .= substr($string, $i, 1);
				$length -= 0.5;
			}
		}
		$result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
		if ($i < $strlen)
			$result .= $etc;
		
		return $result;
	}
	
	/**
	 * 字符串隐私处理
	 * note : 此方法仅适合英文,数字等组成的 每个字符一位字节长度的字符串
	 * 
	 * @param	string		$string			字符串
	 */
	public static function privacyOut($string)
	{
		if (Verify::isPhone($string))
		{
			return substr($string , 0 , 3) . '****' . substr($string , -4);
		}elseif (strlen($string) >= 12){
			return substr($string , 0 , 4) . '****' . substr($string , -4);
		}elseif (strlen($string) >= 8){
			return substr($string , 0 , 2) . '****' . substr($string , -2);
		}elseif (strlen($string) > 2){
			return substr($string , 0 , 1) . '****' . substr($string , -1);
		}
		
		return $string;
	}
}