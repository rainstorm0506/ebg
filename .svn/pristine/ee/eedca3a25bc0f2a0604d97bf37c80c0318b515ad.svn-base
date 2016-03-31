<?php
class ActFreeForm extends SForm
{
	public $id , $title , $rank , $userexp , $companyexp , $gtype , $userlimitce ,$remark , $userdaylimit;
	public $nums=array() , $onece=array() , $price=array() , $start=array() , $end=array();

	public function attributeLabels()
	{
		return array(
			'title'				=> '活动名称',
		);
	}
	
	//基本验证
	public function rules()
	{
		return array(
			array('title , userexp , companyexp' , 'required', 'message'=>'<b>{attribute}</b> 是必填或必选项'),
			array('title' , 'checkTitle'),
			array('title' ,'length','min'=>1,'max'=>50),
			array('rank , nums , onece , price , remark ，userdaylimit' ,  'checkNull'),
		);
	}
	
	//检查活动名称
	public function checkTitle()
	{
		$model = ClassLoad::Only('ActFree');/* @var $model ActFree */
		if ($model->checkTitle($this->title , (int)$this->getQuery('id')))
			$this->addError('title' , '你填写的 [活动名称] 已存在');
	}
}