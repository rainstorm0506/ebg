<?php
/**
 * 单例 class
 * @author simon
 */
class ClassLoad
{
	/**
	 * 返回一个 单例 class
	 * @param string $cn	类名称
	 */
	public static function Only($cn)
	{
		static $c = array();
		
		$class = null;
		if (isset($c[$cn]))
		{
			$class = $c[$cn];
		}else{
			$class = new $cn();
			$c[$cn] = $class;
		}
		return $class;
	}
}