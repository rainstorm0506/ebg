<?php
class GoodsAttrsForm extends SForm
{
	public $class_one_id , $class_two_id , $class_three_id , $title , $val , $rank;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('class_one_id , class_two_id , class_three_id', 'required'),
			array('class_one_id , class_two_id , class_three_id', 'numerical' , 'integerOnly'=>true),
			array('class_three_id', 'checkClass'),
			array('title', 'checkTitle'),
			array('val', 'checkVal'),
			array('rank' , 'checkNull'),
		);
	}
	
	public function checkClass()
	{
		if ($this->class_one_id>0 && $this->class_two_id>0 && $this->class_three_id>0)
		{
			if (!GlobalGoodsClass::verifyClassChain($this->class_one_id , $this->class_two_id , $this->class_three_id))
				$this->addError('class_error' , '请选择正确的分类.');
		}else{
			$this->addError('class_error' , '请选择分类.');
		}
	}
	
	//检查 商品分类属性名称 是否重名
	public function checkTitle()
	{
		$tmp = $title = array_filter($this->title);
		foreach ($title as $key => $val)
		{
			unset($tmp[$key]);
			foreach ($tmp as $tk => $tmpVal)
			{
				if ($val == $tmpVal)
				{
					$this->addError("title[{$key}]" , '你填写的 [商品分类属性名称] 存在重复.');
					$this->addError("title[{$tk}]" , '你填写的 [商品分类属性名称] 存在重复.');
				}
			}
		}
	}
	
	//检查 商品分类属性组中的属性 是否重名
	public function checkVal()
	{
		foreach ($this->val as $ak => $attrs)
		{
			$tmp = $attrs = array_filter($attrs);
			foreach ($attrs as $key => $val)
			{
				unset($tmp[$key]);
				foreach ($tmp as $tk => $tmpVal)
				{
					if ($val == $tmpVal)
					{
						$this->addError("val_error[{$ak}]" , '你填写的 [属性值] 存在重复.');
						$this->addError("val_error[{$ak}]" , '你填写的 [属性值] 存在重复.');
					}
				}
			}
		}
	}
}