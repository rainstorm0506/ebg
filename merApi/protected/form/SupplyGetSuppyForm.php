<?php
class SupplyGetSuppyForm extends ApiForm
{
	public $brand_id , $class_id , $order , $apt , $page;
	
	public function rules()
	{
		return array(
			array('apt , page' ,'required') ,
			array('brand_id , brand_id , order , apt , page' ,'numerical' , 'integerOnly'=>true , 'min'=>1) ,
		);
	}
}
