<?php
class UserLayerSetForm extends SForm
{
	public $user_type , $name , $start_exp , $end_exp , $describe , $goods_rate , $fraction_rate , $exp_rate , $money_rate , $free_freight;
	
	//基本验证
	public function rules()
	{
		return array
		(
			array('user_type , name , start_exp , end_exp , describe , goods_rate , fraction_rate , exp_rate , money_rate , free_freight', 'required'),
			array('user_type , start_exp , end_exp', 'numerical' , 'integerOnly'=>true),
			array('goods_rate , fraction_rate , exp_rate , money_rate , free_freight', 'numerical'),
			array('name' , 'checkName'),
			array('start_exp , end_exp' , 'checkExp')
		);
	}
	
	public function checkName()
	{
		if ($this->user_type < 1)
			$this->addError('name' , '请先选择会员类型.');
		
		$model = ClassLoad::Only('UserLayerSet');/* @var $model UserLayerSet */
		$id = (int)$this->getQuery('id');
		
		if ($model->checkName($this->name , $id , $this->user_type))
			$this->addError('name' , '你填写的 [等级名称] 已存在.');
	}
	
	public function checkExp()
	{
		if ($this->start_exp >= $this->end_exp)
			$this->addError('end_exp' , '结束成长值必须大于开始成长值.');
	}
}