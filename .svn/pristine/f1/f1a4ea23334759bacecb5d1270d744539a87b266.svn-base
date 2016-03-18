<?php
class MerSignNextForm extends WebForm
{
	public $mer_name , $mer_card , $mer_card_front , $mer_card_back;
	
	public function rules()
	{
		return array(
			array('mer_name , mer_card , mer_card_front , mer_card_back', 'required'),
			array('mer_card', 'checkCard'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'mer_name'			=> '商家姓名',
			'mer_card'			=> '身份证号码',
			'mer_card_front'	=> '身份证正面照',
			'mer_card_back'		=> '身份证背面照'
		);
	}
	
	public function checkCard()
	{
		if (($code = Identity::check($this->mer_card)) !== true)
			$this->addError('mer_card', $code);
	}
}