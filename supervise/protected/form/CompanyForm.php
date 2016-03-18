<?php
/**
 * 企业会员Form
 *
 * @author 夏勇高
 */
class CompanyForm extends UserForm
{
	public $com_name , $com_address , $com_num , $com_property , $com_license , $com_tax , $com_org , $com_license_timeout , $dict_one_id , $dict_two_id , $dict_three_id;
	
	// 基本验证
	public function rules()
	{
		if(Yii::app()->controller->action->id == 'create')
		{
			return array (
				array('dict_one_id,dict_two_id,dict_three_id,phone,com_name, com_property, com_address, com_org, com_tax,com_license, com_license_timeout', 'required'),
				array('com_name', 'checkCompany'),
				array('phone', 'numerical', 'integerOnly' => true),
				array('phone', 'checkPhone'),
				array('com_num , re_code,nickname,face,mer_name', 'checkNull'),
				array('password' , 'checkPassword'),
			);
		}else{
			return array (
				array('dict_one_id,dict_two_id,dict_three_id,com_name, com_property, com_address, com_org, com_tax,com_license, com_license_timeout', 'required'),
				array('com_name', 'checkCompany'),
				array('com_num , nickname,face,mer_name', 'checkNull'),
				array('password' , 'checkPassword'),
			);
		}
	}
	
	// 检查密码
	public function checkPassword()
	{
		if(Yii::app()->controller->action->id == 'create' || $this->password)
		{
			if (!Verify::isPassword($this->password))
				$this->addError('password' , '密码格式错误');
		}
	}
	
	// 检查 公司名称 是否重名
	public function checkCompany()
	{
		$model = ClassLoad::Only('Company');/* @var $model Company */
		if($model->checkComName($this->com_name , (int)$this->getQuery('id')))
			$this->addError('com_name' , '你填写的 [公司名称] 已存在.');
	}
}
?>