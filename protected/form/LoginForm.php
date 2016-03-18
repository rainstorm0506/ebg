<?php
class LoginForm extends WebForm
{
	# type = 0 , 1
	public $userPhone , $password , $type = 0;
	private $_identity = null;
	
	//基本验证
	public function rules()
	{
		return array(
			array('userPhone , password , type', 'required'),
			array('userPhone', 'checkPhone'),
		);
	}
	
	public function checkPhone()
	{
		if (!Verify::isPhone($this->userPhone))
			$this->addError('userPhone' , '手机号码错误');
	}
	
	//验证登录
	public function login()
	{
		if($this->_identity === null)
		{
			$this->_identity = new UserWebIdentity($this->userPhone , $this->password);
			$this->_identity->type = $this->type;
			$this->_identity->authenticate();
		}
		
		if($this->_identity->errorCode === 0)
		{
			Yii::app()->getUser()->login($this->_identity , 0);
			
			# 行为处理
			$uid = $this->getUid();
			if (($mid = $this->getMerchantID()) && $mid != $uid)
				$uid = $mid;
			
			# 行为处理 - 登录
			UserAction::loginAction($uid , 1);
			GlobalUser::setReflushUser(array('phone'=>$this->userPhone , 'user_type'=>$this->type?3:0) , 1);
			
			$model = ClassLoad::Only('Home');/* @var $model Home */
			$model->userLoginTime($this->getUid());
			return true;
		}else{
			$this->addError('userPhone' , $this->_identity->errorMessage);
		}
		return false;
	}
}