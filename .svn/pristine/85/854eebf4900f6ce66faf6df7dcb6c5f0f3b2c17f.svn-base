<?php

/**
 * Created by JetBrains PhpStorm.
 * User: dengly
 */
class WordsProcess
{

	/**
	 * 根据一句话返回第一个可用的首字母。
	 *
	 * @param $sentence          //句子内容
	 * @param $canNumberOrLetter //数字是否当字母
	 *
	 * @return bool
	 */
	public static function  getFirstPinYinOfSentence($sentence, $charSet = "utf-8", $canNumberOrLetter = true)
	{
		$words = self::mbStringToArray($sentence, $charSet); //将句子分割成单字数组
		foreach ($words as $key => $value) {
			$pinYin = self::getFirstPinYinOfWord($value, $charSet);
			if (self::isLetters($pinYin)) { //如果是字母
				return $pinYin;
			} elseif (self::isNumber($pinYin) && $canNumberOrLetter) { //如果是数值，而且允许返回数字
				return ucfirst($pinYin);
			}
		}

		return '';
	}

	/**
	 * 根据一个字返回其首字母
	 *
	 * @param $str
	 *
	 * @return string
	 */
	public static function  getFirstPinYinOfWord($s0, $charSet = "UTF-8")
	{
		$fchar = ord($s0{0});
		@$s = iconv($charSet, "GBK", $s0);
		if ($fchar < 160) { //非中文
			if (self::isLetters($s0) || self::isNumber($s0)) { //如果是数字或字母，直接返回
				return $s0{0};
			}
		} else {
			@$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
			if ($asc >= -20319 and $asc <= -20284) {
				return "A";
			}
			if ($asc >= -20283 and $asc <= -19776) {
				return "B";
			}
			if ($asc >= -19775 and $asc <= -19219) {
				return "C";
			}
			if ($asc >= -19218 and $asc <= -18711) {
				return "D";
			}
			if ($asc >= -18710 and $asc <= -18527) {
				return "E";
			}
			if ($asc >= -18526 and $asc <= -18240) {
				return "F";
			}
			if ($asc >= -18239 and $asc <= -17923) {
				return "G";
			}
			if ($asc >= -17922 and $asc <= -17418) {
				return "H";
			}     //没有I,V,U拼音开头的汉字
			if ($asc >= -17417 and $asc <= -16475) {
				return "J";
			}
			if ($asc >= -16474 and $asc <= -16213) {
				return "K";
			}
			if ($asc >= -16212 and $asc <= -15641) {
				return "L";
			}
			if ($asc >= -15640 and $asc <= -15166) {
				return "M";
			}
			if ($asc >= -15165 and $asc <= -14923) {
				return "N";
			}
			if ($asc >= -14922 and $asc <= -14915) {
				return "O";
			}
			if ($asc >= -14914 and $asc <= -14631) {
				return "P";
			}
			if ($asc >= -14630 and $asc <= -14150) {
				return "Q";
			}
			if ($asc >= -14149 and $asc <= -14091) {
				return "R";
			}
			if ($asc >= -14090 and $asc <= -13319) {
				return "S";
			}
			if ($asc >= -13318 and $asc <= -12839) {
				return "T";
			}
			if ($asc >= -12838 and $asc <= -12557) {
				return "W";
			}
			if ($asc >= -12556 and $asc <= -11848) {
				return "X";
			}
			if ($asc >= -11847 and $asc <= -11056) {
				return "Y";
			}
			if ($asc >= -11055 and $asc <= -10247) {
				return "Z";
			}

			return null;
		}
	}

	/**
	 * 将一句话分割为单字数组
	 *
	 * @param        $sentence   需分割的句子
	 * @param string $charset    编码方式
	 *
	 * @return array
	 */
	public static function mbStringToArray($sentence, $charset = 'UTF-8')
	{
		$strLength = mb_strlen($sentence);
		while ($strLength) {
			$array[] = mb_substr($sentence, 0, 1, $charset);
			$sentence = mb_substr($sentence, 1, $strLength, $charset);
			$strLength = mb_strlen($sentence);
		}

		return $array;
	}

	/**
	 * 判断字符是否为数字
	 *
	 * @param $char
	 *
	 * @return bool
	 */
	public static function  isNumber($char)
	{
		$firstStr = substr($char, 0, 1);
		$ascii = ord($firstStr);
		if ($ascii >= 48 && $ascii <= 57) { //是数字
			return true;
		}

		return false;
	}

	/**
	 * 判断字符是否为字母
	 *
	 * @param $char
	 *
	 * @return bool
	 */
	public static function  isLetters($char)
	{
		$firstStr = substr($char, 0, 1);
		$ascii = ord($firstStr);
		if (($ascii >= 65 && $ascii <= 90) || ($ascii >= 97 && $ascii <= 122)) {
			return true;
		} else {
			return false;
		}
	}
}
