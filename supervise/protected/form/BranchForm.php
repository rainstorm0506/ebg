<?php
class BranchForm extends SForm
{
	public $name , $rank;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('name , rank', 'required'),
			array('name', 'length', 'min'=>2, 'max'=>50),
			array('name', 'checkName'),
		);
	}
	
	//检查 部门名称 是否重名
	public function checkName()
	{
		$model = ClassLoad::Only('Branch');/* @var $model Branch */
		
		if ($model->checkName($this->name , (int)$this->getQuery('id')))
			$this->addError('name' , '你填写的 [部门名称] 已存在.');
	}
}