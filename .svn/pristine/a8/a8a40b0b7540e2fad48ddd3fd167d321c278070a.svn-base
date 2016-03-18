<?php
class WAuser extends WebApiModels
{
	public function checkCompanyName($companyName , $id = 0)
	{
		if (!$companyName) return false;
		$SQL = $id ? "AND uid!={$id}" : '';
		return (boolean)$this->queryRow("SELECT uid FROM user_company WHERE `com_name`={$this->quoteValue($companyName)} {$SQL}");
	}
	
	/**
	 * 得到的信息
	 * @param		string		$phone		用户手机号码
	 * @return		array
	 */
	public function getUserInfo($phone)
	{
		if (!$phone) return array();
		return $this->queryRow("SELECT * FROM `user` WHERE `phone`={$this->quoteValue($phone)} AND user_type<3");
	}
	
	/**
	 * 更新用户的登录时间
	 * @param	int		$uid		用户ID
	 */
	public function userLoginTime($uid)
	{
		return $this->update('user' , array('last_time'=>time()) , "id={$uid}");
	}
}