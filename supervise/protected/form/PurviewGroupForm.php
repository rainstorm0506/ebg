<?php
class PurviewGroupForm extends SForm
{
	public $title , $explain , $fields;
	
	//基本验证
	public function rules()
	{
		return array(
			array('title', 'required'),
			array('title' , 'length' , 'min'=>2),
			array('title' , 'checkGroupTitle'),
		);
	}
	
	public function checkGroupTitle($tag)
	{
		$model = ClassLoad::Only('PurviewGroup');/* @var $model PurviewGroup */
		$id = (int)$this->getQuery('id');
		
		if ($model->checkGroupTitle($this->title , $id))
			$this->addError($tag , '你填写的 [角色 名称] 已存在.');
	}
}
