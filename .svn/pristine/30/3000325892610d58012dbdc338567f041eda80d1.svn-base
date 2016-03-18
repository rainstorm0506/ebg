<?php
class GovernorForm extends SForm
{
	public $account,$password,$true_name,$branch_id,$number,$sex,$password_current,$password_new1,$password_new2,$remark,$purviews;
	
	//基本验证
	public function rules()
	{
		$rules = array
		(
			array('true_name , number , branch_id', 'required'),
			array('true_name', 'length', 'min'=>2, 'max'=>20),
			array('password', 'checkPassword'),
			array('number', 'length', 'min'=>2, 'max'=>15),
			array('number', 'checkNumber'),
			array('purviews' , 'checkPurviews'),
		);
		
		if (($action = Yii::app()->controller->action->id) == 'create')
		{
			$rules[] = array('account', 'required');
			$rules[] = array('account', 'length', 'min'=>6, 'max'=>15);
			$rules[] = array('account', 'checkAccount');
		}elseif ($action == 'password'){
			$rules = array
			(
				array('password_current , password_new1 , password_new2', 'required'),
				array('password_current , password_new1 , password_new2', 'length', 'min'=>6, 'max'=>15),
				array('password_current', 'checkPasswordCurrent'),
				array('password_new2', 'checkTwoPassword'),
			);
		}
		
		return $rules;
	}
	
	//检查 帐号 是否重名
	public function checkAccount()
	{
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		$id = (int)$this->getQuery('id');
		
		if ($model->checkAccount($this->account,$id))
			$this->addError('account' , '你填写的 [帐号] 已存在.');
	}
	
	//检查密码
	public function checkPassword()
	{
		$id = (int)$this->getQuery('id');
		if ($id)
		{
			if ($this->password && (strlen($this->password) < 6 || strlen($this->password) > 15))
				$this->addError('password' , '密码必须在6-15位之内.');
		}else{
			if (strlen($this->password) < 6 || strlen($this->password) > 15)
				$this->addError('password' , '密码必须在6-15位之内.');
		}
	}
	
	//检查编号
	public function checkNumber()
	{
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		$id = (int)$this->getQuery('id');
	
		if ($model->checkNumber($this->number , $id))
			$this->addError('number' , '你填写的 [编号] 已存在.');
	}
	
	//检查两次输入的密码
	public function checkTwoPassword()
	{
		if ($this->password_new1 != $this->password_new2)
			$this->addError('password_new2' , '两次输入的密码不相等.');
		
		if ($this->password_new1 == $this->password_current)
			$this->addError('password_new1' , '新密码不能等于旧密码.');
	}
	
	//检查当前密码
	public function checkPasswordCurrent()
	{
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		if (!$model->checkPasswordCurrent($this->password_current))
			$this->addError('password_current' , '[当前密码] 不正确.');
	}
	
	public function checkPurviews()
	{
		$error = true;
		foreach ($this->purviews as $v)
		{
			if ((int)$v)
			{
				$error = false;
				break;
			}
		}
		
		if ($error)
			$this->addError('purviews' , '请至少选择一个角色!');
	}
}
