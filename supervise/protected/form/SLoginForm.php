<?php
class SLoginForm extends SForm
{
	public $account;
	public $password;
	public $codes;
        public $status;

        private $_identity = null;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('account , password', 'required'),
			array('password', 'checkPassword'),
			array('codes', 'length', 'min'=>4, 'max'=>6),
			array('codes', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}
	
	//验证密码
	public function checkPassword()
	{
		$this->_identity = new GovernorIdentity($this->account , $this->password);
		//if(!$this->_identity->authenticate())
		if($this->_identity->authenticate()==GovernorIdentity::ERROR_PASSWORD_INVALID)
			$this->addError('password' , '帐号或密码错误.');
	}

        //验证登录
	public function login($purviews = 'purviews')
	{
		if($this->_identity === null)
		{
			$this->_identity = new GovernorIdentity($this->account , $this->password);
			$this->_identity->authenticate();
		}
		
		if($this->_identity->errorCode === GovernorIdentity::ERROR_NONE)
		{
			Yii::app()->user->login($this->_identity , 0);
			
			$model = ClassLoad::Only('Governor');/* @var $model Governor */
			$model->userLoginTime($this->getUid());
			
			$session = Yii::app()->session;
			$session[$purviews] = array();
			
			$user = $this->getUser();
			if ($user['roles'] && ($purview = json_decode($user['roles'] , true)))
				$session[$purviews] = $model->getUserPurviews($purview);
			return true;
		}
                if($this->_identity->errorCode === GovernorIdentity::ERROR_STATUS_INVALID)
                {
                    $this->addError("status", "当前管理员已被禁用!!");
                    return false;
                }
		return false;
	}
}
