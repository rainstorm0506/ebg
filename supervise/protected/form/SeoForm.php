<?php
class SeoForm extends SForm
{
	public $seo_title , $seo_keywords , $seo_description;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('seo_title , seo_keywords , seo_description', 'checkNull'),
		);
	}
}