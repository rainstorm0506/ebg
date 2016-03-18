<?php
class PromotionTypeForm extends SForm
{
	public $is_show , $name , $width , $height , $describe , $id;
	/**
	 *
	 * @return array 设置访问规则
	 */
	public function rules()
	{
		return array(

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
			'name' => '分类名称' ,
			'width' => '图片宽度' ,
			'height' => '图片高度' 
		);
	}
	
	// 检查 广告分类名称 是否重名
	public function checkTypeName($tag)
	{
		if ($this->{$tag} =='')
			$this->addError($tag , '分类名称不能为空。');
		
		$model = ClassLoad::Only('PromotionType'); /* @var $model PromotionType */
		if ($model->checkName($tag , $this->{$tag} , $this->getQuery('id')))
			$this->addError($tag , '你填写的 [分类名称] 已存在.');
	}
}