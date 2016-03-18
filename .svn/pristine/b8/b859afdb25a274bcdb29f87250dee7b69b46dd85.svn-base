<?php
/**
 * 经营范围Form
 */
class ScopeForm extends SForm {
	
	public $title,$rank,$describe;
	
	public function rules()
	{
		return array(
			array('title', 'required'),
			array('rank', 'numerical', 'integerOnly' => true),
			array('title','checkTitle')
		);
	}
	
	/**
	 * 检测经营范围名称是否重复
	 */
	public function checkTitle()
	{
		$model = ClassLoad::Only('Scope');/* @var $model Scope */
		$id = (int)$this->getQuery('id');
		if ($model -> checkName($this->title, $id))
			$this -> addError('title', '该经营范围已存在');
	}
}

?>