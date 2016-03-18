<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/1/23
 * Time: 18:35
 */
class StoreyForm extends SForm{
	public 	 $title  ,  $gather , $id;

	public function attributeLabels()
	{
		return array(
			'title'				=> '楼层',
		);
	}
	//基本验证
	public function rules()
	{
		$rules = array(
			array('title , gather',  'required', 'message'=>'<b>{attribute}</b> 是必填或必选项'),
			array('title', 'checkTitle')
		);

		return $rules;
	}
	//检查 是否重名
	public function checkTitle()
	{
		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$row = $model->checkStorey($this->title , (int)$this->gather,(int)$this->getQuery('id'));
		if ($row)
			$this->addError('title' , '该楼层已存在！');
	}
}