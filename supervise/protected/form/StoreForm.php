<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/1/23
 * Time: 18:35
 */
class StoreForm extends SForm{
	public 	 $title  ,$gather , $storey , $id , $rank;

	public function attributeLabels()
	{
		return array(
			'title'				=> '店铺编号',
		);
	}
	//基本验证
	public function rules()
	{
		$rules = array(
			array('title , gather , storey',  'required', 'message'=>'<b>{attribute}</b> 是必填或必选项'),
			array('title', 'checkTitle')
		);

		return $rules;
	}
    //检查 是否重名
    public function checkTitle()
    {
		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$row = $model->checkStore($this->title , (int)$this->gather , (int)$this->storey,(int)$this->getQuery('id'));
		if ($row)
			$this->addError('title' , '该编号已存在！');
    }
}