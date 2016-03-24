<?php
class EditPasswordForm extends WebForm
{
	public $member = array();
	public $phone, $verifycode, $password ,$vxcode, $confirmPassword, $codeNum, $personType;

	//基本验证
	public function rules()
	{
		switch ($this->personType){
			case 'index':
				return array(
					array('codeNum, phone' , 'required'),
					array('codeNum' , 'checkMember')
				);
			case 'modVerify':
				return array(
						array('codeNum, vxcode,phone' , 'required'),
						array('codeNum' , 'checkMember')
						//array('verifycode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
					);
			case 'editPassword':
				return array(array('password , confirmPassword' , 'required'));
		}
	}
	
	/**
	 * 设置字段标签名称
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'codeNum' => '* 手机验证码' ,
			'phone' => '* 手机号码' ,
			'vxcode' => '* 图形验证码'
		);
	}
	
	public function checkMember()
	{
		$user = $this->getUser();
		if($this->phone != $user['phone'])
			$this->addError('codeNum' , ' * 短信验证与当前手机号不一致');

		if (($returned = $this->verifySmsCode($this->phone , (int)$this->codeNum)) !== true)
			$this->addError('codeNum' , $returned[1]);
	}
	
	/**
	 * 验证 短信
	 * @param		string		$phone		手机号码
	 * @param		string		$code		验证码
	 */
	public function verifySmsCode($phone , $code , $second = 300)
	{
		$type = (string)$this->getPost('type');
		if (empty($this->userType[$type]))
			exit;

		$form = ClassLoad::Only('SignForm');/* @var $form SignForm */
		$sessKey = $this->userType[$type];
		$form->userType = $this->userType;
		$form->sessKey = $sessKey;
		
		return $form->verifySmsCode($phone , $code , $second);
	}
}