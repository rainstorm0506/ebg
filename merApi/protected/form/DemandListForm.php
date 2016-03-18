<?php
class DemandListForm extends ApiForm
{
	public $brandID , $classID , $apt , $page;
	
	public function rules()
	{
		return array(
			array('apt , page' ,'required') ,
			array('brandID , classID , apt , page' ,'numerical' , 'integerOnly'=>true , 'min'=>1) ,
		);
	}
}
