<?php
class PurchaseGoodsTmpForm extends WebForm
{
	public $link_man,$phone,$title,$goods,$goods_name,$params,$num,$unit,$descript,$img,$price_endtime,$is_tender_offer,$is_interview;

	//基本验证
	public function rules()
	{
		$rules =  array(
					array('link_man, phone, title, price_endtime','required'),
					array('phone' , 'numerical' , 'integerOnly'=>true),
					array('phone' , 'checkPhone'),
					array('is_tender_offer , is_interview','checkNull'),
				);
		 $rules[] = array('goods_name','checkGoodsName');
		 return $rules;
	}

	//设置标签属性
	public function attributeLabels()
	{
		return array(
			'link_man'			=> ' * 联系人',
			'phone'				=> ' * 联系电话',
			'price_endtime'		=> ' * 报价截止时间',
			'title'				=> ' * 商品总名称',
			'goods_name'		=> ' * 商品详细名称',	
		);
	}

	public function checkPhone()
	{
		if (!Verify::isPhone($this->phone))
			$this->addError('phone' , '手机号码错误');
	}
	public function checkGoodsName(){
		if(isset($this->goods_name) && !empty($this->goods_name)){
			foreach ($this->goods_name as $key => $val){
				if(empty($val)){
					$this->addError('goods_name'.$key, '商品名称不能为空!');
				}
			}
		}
	}
}