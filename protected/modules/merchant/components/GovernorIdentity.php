<?php
class GovernorIdentity extends CUserIdentity
{
	private $_id;

	/**
	 * 验证用户登录信息
	 * @param	boolean		$verify		是否验证
	 * @return	boolean
	 */
	public function authenticate($verify = true)
	{
		$user = ClassLoad::Only('User');/* @var $user User */
		$info = $user->getUserInfo($this->username);

		if(empty($info))
		{
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}elseif($verify && !$user->validatePassword($this->password , $info['password'])){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}else{
			$this->_id = $info['id'];
			$this->username = $info;
			$this->errorCode = self::ERROR_NONE;
		}
		return $this->errorCode == self::ERROR_NONE;
	}

	public function getId()
	{
		return $this->_id;
	}

}