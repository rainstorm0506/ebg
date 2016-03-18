<?php
class ExpressForm extends SForm
{
	public $firm_name , $abbreviation , $address , $usable , $contacts , $tel , $website , $rank;
	//基本验证
	public function rules()
	{
		return array
		(
			array('firm_name , usable , rank', 'required'),
			array('firm_name , abbreviation', 'length' , 'min'=>2 , 'max'=>100),
			array('rank', 'numerical' , 'integerOnly'=>true),
			array('firm_name' , 'checkFirmName'),
		);
	}
	
	public function checkFirmName()
	{
		$model = ClassLoad::Only('Express');/* @var $model Express */
		$id = (int)$this->getQuery('id');
		
		if ($model->checkFirmName($this->firm_name , $id))
			$this->addError('firm_name' , '你填写的 [公司名称] 已存在.');
	}
}