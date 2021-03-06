<?php
class UsedGoodsForm extends ApiForm
{
	public $id , $title ,  $goods_num , $brand_id , $lightspot , $dict_one_id , $dict_two_id , $dict_three_id , $imgsSet;
	public $buy_price , $sale_price , $stock , $weight , $content , $cover , $shelf , $use_time , $class_one_id , $class_two_id , $class_three_id;
	public $img = array() ;

	public function attributeLabels()
	{
		return array(
			'title'				=> '商品名称',
			'goods_num'			=> '商品货号',
			'use_time'			=>'二手成色',
			'brand_id'			=> '品牌',
			'class_three_id'	=> '商品分类',
			'cover'				=> '商品主图',
			'sale_price'		=> '售价',
			'weight'			=> '重量',
			'stock'				=> '库存',
		);
	}
	
	//基本验证
	public function rules()
	{
		$rules = array(
			array('title, use_time, cover'  , 'required', 'message'=>'<b>{attribute}</b> 是必填或必选项'),
			array('stock', 'numerical' , 'integerOnly'=>true),
			array('brand_id', 'numerical' , 'integerOnly'=>true , 'min'=>1),
			array('sale_price , weight', 'numerical'),
			array('title', 'checkTitle'),
			array('title','length','min'=>1,'max'=>50),
            array('use_time','length','min'=>1,'max'=>3),
			array('class_three_id', 'checkClass'),
			array('brand_id', 'checkBrand'),
			array('goods_num','length','min'=>0,'max'=>15),
			array('goods_num' , 'checkGoodsNum'),
			array('img , shelf , buy_price , lightspot , content , buy_price , dict_one_id , dict_two_id , dict_three_id , class_one_id , class_two_id , class_three_id',  'checkNull'),
		);

			$rules[] = array('sale_price , weight', 'numerical');
			$rules[] = array('sale_price , weight' , 'checkDouble');
			$rules[] = array('sale_price' , 'checkPrice');
			$rules[] = array('stock' , 'checkStock');

		return $rules;
	}

	//检查分类
	public function checkClass()
	{
		if ($this->class_one_id>0 && $this->class_two_id>0 && $this->class_three_id>0)
		{
			if (!GlobalUsedClass::verifyClassChain($this->class_one_id , $this->class_two_id , $this->class_three_id))
				$this->addError('class_three_id' , '请选择正确的 <b>商品分类</b>.');
		}else{
			$this->addError('class_three_id' , '请选择 <b>商品分类</b>.');
		}
	}

	//检查品牌
	public function checkBrand()
	{
		if (!GlobalBrand::isBrandID($this->brand_id))
			$this->addError('brand_id' , '请选择正确的 <b>商品品牌</b>.');
	}

	//检查商品货号
	public function checkGoodsNum()
	{
		if ($this->goods_num)
		{
			$model = ClassLoad::Only('Used');/* @var $model Used */
			if ($model->checkGoodsNum($this->goods_num))
				$this->addError('goods_num' , '你填写的 [商品货号] 已存在.');
		}
	}

	//检查浮点数是否小于0
	public function checkDouble($tag)
	{
		if ($this->{$tag} <= 0)
			$this->addError($tag , '<b>'.$this->getAttributeLabel($tag).'</b> 必须大于0');
	}

	//检查价格设定
	public function checkPrice()
	{
		if($this->buy_price>0){
			if ($this->buy_price < $this->sale_price)
				$this->addError('sale_price' , '<b>售价</b>不能大于<b>买价</b>');
		}
	}

	//检查库存
	public function checkStock()
	{
		if ($this->stock <= 0)
			$this->addError('stock' , '请设定正确的 <b>'.$this->getAttributeLabel('stock').'</b>');
	}
	
	//检查商品名称
	public function checkTitle()
	{
		$model = ClassLoad::Only('Used');/* @var $model Used */
		if ($model->checkTitle($this->title , (int)$this->id))
			$this->addError('title' , '你填写的 [商品名称] 已存在.');
	}
}