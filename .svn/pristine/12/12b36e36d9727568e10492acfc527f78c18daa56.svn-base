<?php
class ContentForm extends SForm
{
	public $addtime , $orderby , $is_show ,$foot_show, $content , $title , $type , $id;
	/**
	 *
	 * @return array 设置访问规则
	 */
	public function rules()
	{
		return array(
			array('title, content' , 'required'),
			array('title' , 'checkName'),
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
			'title' => '文章标题' ,
			'content' => '文章内容' ,
			'type' => '分类名称' 
		);
	}
	
	// 检查 文章标题 是否重名
	public function checkName($tag)
	{
		if ($this->{$tag} =='')
			$this->addError($tag , '文章标题不能为空。');
		
		$model = ClassLoad::Only('Content'); /* @var $model Content */
		if ($model->checkName($tag , $this->{$tag} , (int)$this->getQuery('id')))
			$this->addError($tag , '你填写的 [文章标题] 已存在.');
	}
}