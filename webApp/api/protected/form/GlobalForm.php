<?php
class GlobalForm extends WebApiForm
{
	public $type , $code , $phone , $one , $two , $three;
	
	public function viferyDict()
	{
		if ($this->one<0 || $this->two<0 || $this->three<0)
		{
			$this->addError('one' , '地区ID错误!');
			return false;
		}
		
		$vifery = true;
		if ($this->one>0 && $this->two>0 && $this->three>0)
		{
			if (!GlobalDict::getAreaName($this->three , $this->one , $this->two))
			{
				$vifery = false;
				$this->addError('one' , '地区ID错误!');
			}
		}elseif($this->one>0 && $this->two>0){
			if (!GlobalDict::getAreaName($this->two , $this->one))
			{
				$vifery = false;
				$this->addError('one' , '地区ID错误!');
			}
		}elseif($this->one>0){
			if (!GlobalDict::getAreaName($this->one))
			{
				$vifery = false;
				$this->addError('one' , '地区ID错误!');
			}
		}
		return $vifery;
	}
	
	public function viferyImgCode(array $sessCode)
	{
		$vifery = true;
		if (empty($sessCode['code']) || $sessCode['code'] != $this->code)
		{
			$vifery = false;
			$this->addError('code' , '验证码错误!');
		}
		
		return $vifery;
	}
	
	public function viferyViferySmsCode(array $sessKey , array $smsCode)
	{
		if ($this->viferySendSmsCode())
		{
			$sessKey	= $sessKey[$this->type];
			$smsCode	= empty($smsCode[$sessKey]) ? array() : $smsCode[$sessKey];
			
			if (!$smsCode)
			{
				$this->addError('phone' , '你未发送过验证号码!');
				return false;
			}
			
			if ($smsCode['phone'] != $this->phone)
			{
				$this->addError('phone' , '你接收短信验证的手机号码错误!');
				return false;
			}
			
			if ($smsCode['verCode'] != $this->code)
			{
				$this->addError('code' , '短信验证码错误!');
				return false;
			}
			
			if (time() > $smsCode['expire'])
			{
				$this->addError('code' , '短信验证码已过期 , 请重新获取!');
				return false;
			}
			
			return true;
		}
		
		return false;
	}
	
	public function viferySendSmsCode()
	{
		$vifery = true;
		if ($this->type < 1 || $this->type > 3)
		{
			$vifery = false;
			$this->addError('type' , '类型错误!');
		}
		
		if (!Verify::isPhone($this->phone))
		{
			$vifery = false;
			$this->addError('phone' , '手机号码错误!');
		}
		
		//验证注册手机号码
		$model = ClassLoad::Only('WAuser');/* @var $model WAuser */
		if ($model->getUserInfo($this->phone))
		{
			$vifery = false;
			$this->addError('phone' , '此手机号码已注册!');
		}
		
		return $vifery;
	}
}