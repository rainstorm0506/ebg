<?php
class MyStoreForm extends WebForm
{
	public $store_name, $store_avatar, $store_tel, $store_describe, $store_address, $store_environment, $image_url, $phone, $codeNum, $vxcode;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('store_name , image_url, store_tel, store_describe,store_address', 'required'),
			array('store_tel', 'length', 'min'=>10, 'max'=>16),
			array('store_describe', 'length', 'max'=>1000),
		);
	}
	/**
	 * 设置字段标签名称
	 *
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'store_name' => '店铺名称' ,
			'store_avatar' => '店铺头像' ,
			'store_tel' => '店铺电话' ,
			'store_describe' => '店铺描述',
			'store_address' => '店铺地址' 
		);
	}

	//用户修改手机号ajax短信验证
	public function checkPhone($phone, $codeNum =null)
	{
		if (($returned = $this->verifySmsCode($phone , $codeNum)) !== true)
			return $returned[1];
		else
			return 1;
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