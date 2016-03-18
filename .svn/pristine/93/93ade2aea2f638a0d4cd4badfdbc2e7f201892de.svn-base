<?php
/**
 * description： 用户信息Form
 * @author 夏勇高
 */
class UserForm extends SForm
{
	// $user_layer,
	public $phone , $password , $re_code , $reg_time , $last_time , $status , $nickname , $face , $fraction , $exp , $money , $user_type;
	
	// 基本验证
	public function rules()
	{
		if(Yii::app()->controller->action->id == 'create')
		{
			return array(
				array('phone' , 'required'),
				array('phone' , 'numerical', 'integerOnly' => true),
				array('phone' , 'checkPhone'),
				array('re_code , nickname , face' , 'checkNull'),
				array('password' , 'checkPassword'),
			);
		}else{
			return array(
				array('nickname , face' , 'checkNull'),
				array('password' , 'checkPassword'),
			);
		}
	}
	
	// 检查密码
	public function checkPassword()
	{
		if(Yii::app()->controller->action->id != 'modify' || $this->password)
		{
			if (!Verify::isPassword($this->password))
				$this->addError('password' , '密码格式错误');
		}
	}
	// 验证电话号码
	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
		{
			$this->addError('phone' , '手机号码错误');
			return false;
		}
		
		$model = ClassLoad::Only('User'); /* @var $model User */
		if($model->checkUserPhone($this->phone , (int)$this->getQuery('id') , $this->user_type))
			$this->addError('phone' , '该手机号码已存在');
	}
}
