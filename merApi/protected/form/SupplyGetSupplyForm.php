<?php
class SupplyGetSupplyForm extends ApiForm
{
	public $brandID , $classID , $order , $apt , $page;
	
	public function rules()
	{
		return array(
			array('apt , page' ,'required') ,
			array('classID , brandID , order , apt , page' ,'numerical' , 'integerOnly'=>true , 'min'=>1) ,
		);
	}
	
}
