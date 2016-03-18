<?php
class UserWebIdentity extends CUserIdentity
{
	public $type;
	private $_id;
	
	/**
	 * 验证用户登录信息
	 * @param	boolean		$verify		是否验证
	 * @return	boolean
	 */
	public function authenticate($verify = true)
	{
		$user = ClassLoad::Only('Home');/* @var $user Home */
		$info = $user->getUserInfo($this->username , (int)$this->type);
		
		if(empty($info))
		{
			$this->errorCode = 1;
			$this->errorMessage = '查询不到数据!';
		}elseif($verify && !GlobalUser::validatePassword($this->password , $info['password'])){
			$this->errorCode = 2;
			$this->errorMessage = '账号或者密码错误!';
		}else{
			$status = (int)$info['status_id'];
			$userType = (int)$info['user_type'];
			switch ($userType)
			{
				case 1 : $status = $status===510 ? 0 : $status; break;
				case 2 : $status = $status===610 ? 0 : $status; break;
				case 3 : $status = $status===710 ? 0 : $status; break;
				default: $status = -1;
			}
			
			if ($status === 0)
			{
				$this->_id = $info['id'];
				$this->username = $info;
				$this->errorCode = 0;
			}else{
				if ($status === -1)
					$this->errorMessage = '用户类型错误!';
				else
					$this->errorMessage = GlobalStatus::getStatusName($status , $userType);
			}
		}
		return $this->errorCode == 0;
	}

	public function getId()
	{
		return $this->_id;
	}
}