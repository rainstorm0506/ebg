<?php
class ExpressFreightForm extends SForm
{
	public $dict_one_id , $dict_two_id , $dict_three_id , $dict_four_id;
	#public  $dict_one_unify , $dict_two_unify , $dict_three_unify;
	public $default_weight , $default_price , $interval_weight , $interval_price , $rank , $checkFreSet;
	
	//基本验证
	public function rules()
	{
		return array
		(
			#array('dict_one_id , default_weight , default_price , interval_weight , interval_price , rank', 'required'),
			#array('dict_one_id , dict_one_unify , dict_two_id , dict_two_unify , dict_three_id , dict_three_unify , dict_four_id , rank', 'numerical' , 'integerOnly'=>true),
			#array('default_weight , default_price , interval_weight , interval_price', 'numerical' , 'integerOnly'=>false),
			#array('dict_one_id , dict_two_id , dict_three_id , dict_four_id' , 'checkFreSet'),
			
			array('dict_one_id , default_weight , default_price , interval_weight , interval_price , rank', 'required'),
			array('dict_one_id , dict_two_id , dict_three_id , dict_four_id , rank', 'numerical' , 'integerOnly'=>true),
			array('default_weight , default_price , interval_weight , interval_price', 'numerical' , 'integerOnly'=>false),
			array('dict_one_id , dict_two_id , dict_three_id , dict_four_id' , 'checkFreSet'),
			array('dict_one_id , default_weight , default_price , interval_weight , interval_price' , 'checkZero')
		);
	}
	
	public function checkZero($tag)
	{
		if ($this->{$tag} <= 0)
		{
			$this->addError($tag , '必须大于0');
			$this->addError('checkFreSet' , '重量 , 价格 必须大于0');
		}
	}
	
	public function checkFreSet()
	{
		$model = ClassLoad::Only('Express');/* @var $model Express */
		$id = (int)$this->getQuery('id');
		$fid = (int)$this->getQuery('fid');
		
		if ($model->checkFreSet(
				(int)$this->dict_one_id ,
				(int)$this->dict_two_id ,
				(int)$this->dict_three_id ,
				(int)$this->dict_four_id ,
				$id ,
				$fid)
		)
			$this->addError('checkFreSet' , '你设置的 [区域运费] 已存在.');
	}
}