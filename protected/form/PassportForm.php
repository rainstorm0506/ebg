<?php
/**
 * 第三方账号验证
 * 
 * @author root
 *
 */
class PassportForm extends WebForm
{
	public $source , $seat , $code , $openid , $password , $isReg;
	
	public $phone , $vxcode , $smscode , $recode , $agree;
	
	public function viferyCallback()
	{
		if (!in_array($this->source , array('wb' , 'wx' , 'qq')))
			return '访问来源错误 , 这是一个非法请求!';
		
		if (!in_array($this->seat , array(1 , 2)))
			return '请求位置错误 , 这是一个非法请求!';
		
		$this->code = trim($this->code);
		if ($this->code == '')
			return '第三方回调错误!';
		
		return true;
	}

	public function rules()
	{
		return array(
			array('phone , vxcode, smscode , agree', 'required'),
			array('vxcode , smscode', 'numerical' , 'integerOnly'=>true , 'min'=>10000 , 'max'=>999999),
			array('recode , openid , password , isReg', 'checkNull'),
			array('phone', 'checkPhone'),
			array('password', 'checkPassword'),
		);
	}
	
	public function checkPassword()
	{
		if ($this->isReg && $this->password)
		{
			$model = ClassLoad::Only('Home');/* @var $model Home */
			if ($u = $model->getUserInfo($this->phone, $this->seat == 2))
			{
				if (!GlobalUser::validatePassword($this->password , $u['password']))
					$this->addError('password' , '密码错误!');
			}
		}
	}
	
	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
			return $this->addError('phone' , '手机号码错误');
		
		$_xx = '';
		$model = ClassLoad::Only('Passport');/* @var $model Passport */
		if ($model->isRepeat($this->phone , $this->source , $this->seat , $this->openid , $_xx))
			$this->addError('phone' , '此账号已绑定过'.$_xx.'账号!');
	}
}