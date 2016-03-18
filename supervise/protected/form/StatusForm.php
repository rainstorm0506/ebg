<?php
class StatusForm extends SForm
{
	public $type,$user_title,$merchant_title,$back_title,$user_describe,$merchant_describe,$back_describe;

	/**
	* @return array  设置访问规则
	*/
	public function rules()
	{
		return array(
			array('user_title,merchant_title,back_title,user_describe,merchant_describe,back_describe', 'required'),
		);
	}
	
}