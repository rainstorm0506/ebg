<?php
class LoginIdentity extends CUserIdentity
{
	private $_id;

	/**
	 * 验证用户登录信息
	 * @param	boolean		$verify		是否验证
	 * @return	boolean
	 */
	public function authenticate($verify = true)
	{
		$user = ClassLoad::Only('Merchants');/* @var $user Merchants */
		$info = $user->getUserInfo($this->username);
		
		if(empty($info))
		{
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}elseif($verify && !GlobalUser::validatePassword($this->password , $info['password'])){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}else{
			$this->_id = $info['id'];
			unset($info['password']);
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