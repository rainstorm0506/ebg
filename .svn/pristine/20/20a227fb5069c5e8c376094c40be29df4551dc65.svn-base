<?php
class WebAppIdentity extends CUserIdentity
{
	private $_id;
	public $type;	# 无效

	/**
	 * 验证用户登录信息
	 * @param	boolean		$verify		是否验证
	 * @return	boolean
	 */
	public function authenticate($verify = true)
	{
		$model = ClassLoad::Only('WAuser');/* @var $model WAuser */
		$info = $model->getUserInfo($this->username);
		
		if(empty($info))
		{
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}elseif($verify && !GlobalUser::validatePassword($this->password , $info['password'])){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}else{
			$this->_id = $info['id'];
			unset($info['password'] , $info['source'] , $info['re_code'] , $info['re_uid'] , $info['merchant_id'] , $info['remark']);
			
			$info['face'] = Views::imgShow($info['face']);
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