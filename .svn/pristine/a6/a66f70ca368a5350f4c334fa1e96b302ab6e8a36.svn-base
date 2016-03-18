<?php
class MerLoginForm extends ApiForm
{
	public $phone , $password , $apt;
	
	private $_identity = null;
	
	//基本验证
	public function rules()
	{
		return array(
			array('phone , password , apt', 'required'),
			array('phone', 'checkPhone'),
		);
	}
	
	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
			$this->addError('phone' , '手机号码错误');
	}
	
	//验证登录
	public function login()
	{
		if($this->_identity === null)
		{
			$this->_identity = new LoginIdentity($this->phone , $this->password);
			$this->_identity->authenticate();
		}
		
		if($this->_identity->errorCode === LoginIdentity::ERROR_NONE)
		{
			Yii::app()->getUser()->login($this->_identity , 0);
			
			$model = ClassLoad::Only('Merchants');/* @var $model Merchants */
			
			# 行为处理 - 商家登录
			if (UserAction::loginAction($this->getMerchantID() , 1))
			{
				if (($mer = $model->getMerInfo()) && !empty($mer['phone']))
					GlobalUser::setReflushUser(array('phone'=>$mer['phone'] , 'user_type'=>3) , 3);
			}
			
			$model->userLoginTime($this->getUid());
			return true;
		}else{
			switch ($this->_identity->errorCode)
			{
				case LoginIdentity::ERROR_USERNAME_INVALID :
					$this->addError('phone' , '找不到用户数据');
				break;
				
				case LoginIdentity::ERROR_PASSWORD_INVALID :
					$this->addError('phone' , '账号或密码错误');
				break;
				
				case LoginIdentity::ERROR_NONE :
				default :
					$this->addError('phone' , '未知错误');
				break;
			}
		}
		return false;
	}
}