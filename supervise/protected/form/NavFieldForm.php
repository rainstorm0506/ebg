<?php
class NavFieldForm extends SForm
{
	public $parent_id , $title , $route , $rank;
	//基本验证
	public function rules()
	{
		return array
		(
			array('parent_id , title', 'required'),
			array('parent_id , rank', 'numerical' , 'allowEmpty'=>false , 'integerOnly'=>true),
			array('title', 'length', 'min'=>1, 'max'=>50),
		);
	}
}
