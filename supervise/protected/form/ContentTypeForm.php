<?php
class ContentTypeForm extends SForm
{
	public $addtime , $orderby , $foot_show, $name , $id;
	/**
	 *
	 * @return array 设置访问规则
	 */
	public function rules()
	{
		return array(
			array(
				'name' ,
				'required' 
			) ,
			array(
				'name' ,
				'checkTypeName' 
			) 
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
			'id' => 'ID' ,
			'name' => '分类名称' 
		);
	}
	
	// 检查 文章分类名称 是否重名
	public function checkTypeName($tag)
	{
		if ($this->{$tag} =='')
			$this->addError($tag , '分类名称不能为空。');
		
		$model = ClassLoad::Only('ContentType'); /* @var $model ContentType */
		if ($model->checkName($tag , $this->{$tag} , (int)$this->getQuery('id')))
			$this->addError($tag , '你填写的 [分类名称] 已存在.');
	}
}