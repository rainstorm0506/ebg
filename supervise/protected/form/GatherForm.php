<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/1/23
 * Time: 18:35
 */
class GatherForm extends SForm{
	public 	 $dict_one_id , $dict_two_id , $dict_three_id  , $title  , $id;

	public function attributeLabels()
	{
		return array(
			'title'			=> '电脑城名称',
		);
	}
	//基本验证
	public function rules()
	{
		$rules = array(
			array('title',  'required', 'message'=>'<b>{attribute}</b> 是必填或必选项'),
			array('title', 'checkTitle')
		);

		return $rules;
	}
	//检查 是否重名
	public function checkTitle()
	{
		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$row = $model->checkTitle($this->title , (int)$this->getQuery('id'));
		if ($row)
			$this->addError('title' , '该电脑城已存在！');
	}
}