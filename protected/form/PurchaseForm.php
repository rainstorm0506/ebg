<?php
class PurchaseForm extends WebForm
{
	public $starttime, $endtime,$vxcode, $price_endtime, $is_closed, $is_interview, $is_tender_offer, $phone, $link_man, $codeNum, $file_data;

	//基本验证
	public function rules()
	{
		return array(
			array('link_man, phone, vxcode, codeNum, price_endtime', 'required'),
			array('phone, codeNum' , 'numerical' , 'integerOnly'=>true),
			array('phone' , 'checkPhone'),
			array('codeNum' , 'checkCodeNum'),
			array('is_tender_offer , is_interview' , 'checkNull')
		);
	}

	//设置标签属性
	public function attributeLabels()
	{
		return array(
			'link_man'			=> ' * 联系人',
			'phone'				=> ' * 李晓电话',
			'codeNum'			=> ' * 短信验证码',
			'price_endtime'		=> ' * 报价截止时间'
		);
	}

	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
			$this->addError('phone' , '手机号码错误');
	}
	
	public function checkCodeNum()
	{
	
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
		if (!Verify::isPhone($phone))
			return array(1 , '手机号码错误');
	
		$session = Yii::app()->session;
		//print_r($session['reg_member']);exit;
		if (empty($session['reg_member']['phone']))
			return array(2 , '没有发送验证短信');
	
		if ($session['reg_member']['phone'] != $phone)
			return array(3 , '当前手机号码和验证号码不一致');
	
		if (empty($session['reg_member']['verCode']))
			return array(4 , '此号码没有发送过验证短信');
	
		if ($session['reg_member']['verCode'] != $code)
			return array(5 , '短信验证码错误');
	
		if ((time() - $second) > (isset($session['reg_member']['sendTime'])?(int)$session['reg_member']['sendTime']:0))
			return array(6 , '验证超时 , 请重新验证!');
	
		return true;
	}
}