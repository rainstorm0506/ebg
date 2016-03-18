<?php
interface SmsInterface
{
	/**
	 * 获取剩余的短信条数
	 */
	public static function getSurplusRows();
	
	/**
	 * 发送短信
	 * @param	array		$phone		发送的号码
	 * @param	string		$content	短信的内容
	 * @param	int			$sendTime	发送的时间 (时间戳) , 如果>0表示定时发送
	 * @param	int			$uid		用户ID
	 * @param	int			$gid		操作者ID (0=系统 , >0=系统工作人员)
	 */
	public static function send(array $phone , $content , $sendTime = 0 , $uid = 0 , $gid = 0);

	/**
	 * 获得短信接口方的所有错误列表 , error_code => error_message
	 * @return		array
	 */
	public static function getErrors();
}