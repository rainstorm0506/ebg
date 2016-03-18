<?php

/**
 *Tools.php
 * Date: 2015/12/8
 *
 * @author: Casper
 */
class Tools
{
	/**
	 * @static 格式化打印数据
	 *
	 * @param $_value
	 */
	public static function   pre($_value)
	{
		if ($_value === null || $_value === false || $_value === true) {
			var_dump($_value);
		} else {
			echo "<pre>";
			print_r($_value);
			echo "</pre>";
		}
	}

	/**
	 * @static 格式化打印数据，并终止
	 *
	 * @param $_value
	 */
	public static function  preDie($_value)
	{
		self::pre($_value);
	}

	/**
	 *
	 * @static
	 *
	 * @param bool $isSuccess     是否成功
	 * @param string $successInfo 成功信息
	 * @param string $errorInfo   失败信息
	 * @param $otherInfo          其它自定义信息
	 *
	 * @return array
	 */
	public static function  returnInfo($isSuccess = true, $successInfo = '', $errorInfo = '', $otherInfo = array())
	{
		return array(
			'isSuccess'   => $isSuccess,
			'successInfo' => $successInfo,
			'errorInfo'   => $errorInfo,
			'otherInfo'   => $otherInfo,
		);
	}

}

