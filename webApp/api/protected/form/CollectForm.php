<?php
class CollectForm extends WebApiForm
{
	public $type , $user_id , $collect_id ;
	
	//基本验证
	public function rules()
	{
		$rules = array(
			array('type , collect_id , user_id', 'required'),
			array('collect_id' , 'checkCollect'),
		);

		return $rules;
	}
	
	public function checkCollect()
	{
		$model = ClassLoad::Only('Collect'); /* @var $model Collect */
		if($this->type==1 && !$model->checkGoods($this->collect_id))
			return $this->addErrors('collect_id' , '你收藏的商品不存在');

		if($this->type==2 && !GlobalMerchant::CheckUser($this->collect_id))
			return $this->addErrors('collect_id' , '你收藏的店铺不存在');

		if($this->type==3 && !$model->checkUsed($this->collect_id))
			return $this->addErrors('collect_id' , '你收藏的二手商品不存在');

	}
}