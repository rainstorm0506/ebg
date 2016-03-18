<?php
/**
 * Description of ModifyPhoneForm
 * 修改账户的 手机号码
 * @author Administrator
 */
class ModifyPhoneForm extends WebApiForm{
    public $code,$new_phone,$vxCode;
    public function rules() {
        return array(
            array('vxCode','checkVxCode'),
           // array('code','checkSmsCode')
        );
    }
    public function checkSmsCode()
	{
		$session	= Yii::app()->session;
		$smsCode	= empty($session['smsCode']['com_user']) ? array() : (array)$session['smsCode']['com_user'];
		
		if (!$smsCode)
			return $this->addError('new_phone' , '你未发送过验证号码!');
		
		if ($smsCode['phone'] != $this->new_phone)
			return $this->addError('new_phone' , '你接收短信验证的手机号码错误!');
		
		if ($smsCode['verCode'] != $this->code)
			return $this->addError('code' , '短信验证码错误!');
			
		if (time() > $smsCode['expire'])
			return $this->addError('code' , '短信验证码已过期 , 请重新获取!');
	}
	
	public function checkVxCode()
	{
		$session	= Yii::app()->session;
		$sessCode	= empty($session['imgCode']['com_user']) ? array() : $session['imgCode']['com_user'];
		
		if (empty($sessCode['code']) || $sessCode['code'] != $this->vxCode)
			$this->addError('vxCode' , '图像验证码错误!');
	}    
}
