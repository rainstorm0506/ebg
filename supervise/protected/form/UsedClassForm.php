<?php
class UsedClassForm extends SForm
{
	public $one_id , $two_id , $title , $is_show , $price=array();

	//基本验证
	public function rules()
	{
		return array
		(
			array('title', 'required'),
			array('title', 'length', 'min'=>1, 'max'=>50),
			array('title', 'checkTitle'),
			array('price' , 'checkPrice'),
		);
	}

	//检查 分类名称 是否重名
	public function checkTitle()
	{
		$model = ClassLoad::Only('UsedClass');/* @var $model UsedClass */
		$row = $model->checkTitle($this->title , (int)$this->getQuery('id'));
		if ($row)
			$this->addError('title' , '你填写的 [分类名称] 已存在.');
	}
	//检查价格区间
	public function checkPrice()
	{
		if (!empty($this->price['s']))
		{
			$last = $end = 0;
			foreach ($this->price['s'] as $k => $v)
			{
				$end = isset($this->price['e'][$k]) ? (int)$this->price['e'][$k] : 0;
				#$next = isset($this->price['s'][$k+1]);
				#if ($next && $v >= $end)
				if ($v >= $end)
					$this->addError('price' , '价格开始值必须大于结束值');

				if ($v != $last+1)
					$this->addError('price' , '价格开始值必须是上一个结束值 + 1');

				$last = $end;
			}
		}
	}
}