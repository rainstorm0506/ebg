<?php
class PromotionForm extends SForm
{
	public $addtime , $is_show , $image_url , $link , $title , $class_one_id, $code_key , $id , $code_key_one , $code_key_two;
	/**
	 *
	 * @return array 设置访问规则
	 */
	public function rules()
	{
		return array(
			array('title, image_url' ,'required') ,
			array('title' ,'checkName'),
			array('image_url , code_key_two' ,'checkNull'),
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
			'title' => '* 广告名称' ,
			'addtime' => '* 创建时间',
			'image_url'=>'* 广告图片'
		);
	}
	
	// 检查 广告名称 是否重名
	public function checkName($tag)
	{
		if ($this->{$tag} =='')
			$this->addError($tag , '广告名称不能为空。');
		
		$model = ClassLoad::Only('Promotion'); /* @var $model Promotion */
		if ($model->checkName($tag , $this->{$tag} , (int)$this->getQuery('id')))
			$this->addError($tag , '你填写的 [广告名称] 已存在.');
	}
}