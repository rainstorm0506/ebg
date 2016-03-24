<?php
class ActGoodsForm extends SForm
{
	public $id , $title ,  $goods_num , $merchant , $merchant_id , $vice_title , $brand_id , $shelf , $rank , $attrVal;
	public $act_price , $original_price , $stock , $attrInStock , $weight , $content , $cover , $class_one_id , $class_two_id , $class_three_id;
	public $img = array() ,
			$attrs = array() ,
			$args = array();

	public function attributeLabels()
	{
		return array(
			'title'				=> '商品名称',
			'goods_num'			=> '商品货号',
			'merchant_id'		=> '所属商家',
			'brand_id'			=> '品牌',
			'cover'				=> '商品主图',
			'class_three_id'	=> '商品分类',
			'original_price'	=> '原价',
			'weight'			=> '重量',
			'content'			=> '商品详情',
			'stock'				=> '库存'
		);
	}
	
	//基本验证
	public function rules()
	{
		return array(
			array('title , merchant_id , cover , content'  , 'required', 'message'=>'<b>{attribute}</b> 是必填或必选项'),
			array('stock', 'numerical' , 'integerOnly'=>true),
			array('merchant_id, brand_id', 'numerical' , 'integerOnly'=>true , 'min'=>1),
			array('original_price , weight , class_one_id , class_two_id , class_three_id', 'numerical'),
			array('title', 'checkTitle'),
			array('title','length','min'=>1,'max'=>50),
			array('class_three_id', 'checkClass'),
			array('brand_id', 'checkBrand'),
			array('goods_num','length','min'=>1,'max'=>15),
			array('goods_num' , 'checkGoodsNum'),
			array('args' , 'checkArgs'),
			array('img , cover , merchant , vice_title , original_price , rank , attrVal , attrs , args , attrInStock',  'checkNull'),
		);
		if($this->attrs)
		{
			$rules[]=array('attrs' , 'checkAttrs');
		}
		else
		{
			$rules[] = array('original_price , weight' , 'checkDouble');
			$rules[] = array('original_price' , 'checkPrice');
			$rules[] = array('stock' , 'checkStock');
		}
	}
	
	//检查分类
	public function checkClass()
	{
		if ($this->class_one_id>0 && $this->class_two_id>0 && $this->class_three_id>0)
		{
			if (!GlobalGoodsClass::verifyClassChain($this->class_one_id , $this->class_two_id , $this->class_three_id))
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
			$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
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
		if($this->original_price<=0){
				$this->addError('original_price' , '<b>原价</b>必须大于<b>0</b>');
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
		$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
		if ($model->checkTitle($this->title , (int)$this->getQuery('id')))
			$this->addError('title' , '你填写的 [商品名称] 已存在.');
	}
	//检查属性组设定
	public function checkAttrs()
	{
		switch (count($this->attrs))
		{
			case 1        :
				$this->_checkAttrs_1();
				break;
			case 2        :
				$this->_checkAttrs_2();
				break;
			case 3        :
				$this->_checkAttrs_3();
				break;
			default        :
				$this->addError('attrs', '商品属性异常!');
		}
	}
	private function _checkAttrs_1()
	{
		foreach (current($this->attrs) as $ak => $av)
		{
			#价格
			if (empty($this->attrVal['original_price'][$ak]))
			{
				$this->addError('attrs' , '请填写 <b>商品属性的原价</b>');
			}else{
				$price = (double)$this->attrVal['original_price'][$ak];
				if ($price <= 0)
					$this->addError('attrs' , '<b>商品属性的原价</b> 必须大于0');
			}

			#库存
			if (empty($this->attrVal['stock'][$ak]))
			{
				if (empty($this->attrVal['inStock'][$ak]))
				{
					$this->addError('attrs' , '请设定 <b>商品属性的库存</b>');
				}else{
					$_tp = (int)$this->attrVal['inStock'][$ak];
					if (!($_tp === -999 || $_tp > 0))
						$this->addError('attrs' , '<b>商品属性的库存</b> 在无限库存的情况设定错误');
				}
			}else{
				if ((int)$this->attrVal['stock'][$ak] < 1)
					$this->addError('attrs' , '<b>商品属性的库存</b> 在非无限库存的情况下必须大于0');
			}

			#重量
			if (empty($this->attrVal['weight'][$ak]))
				$this->addError('attrs' , '请填写 <b>商品属性的重量</b>');
			elseif ((double)$this->attrVal['weight'][$ak] <= 0)
				$this->addError('attrs' , '<b>商品属性的重量</b> 必须大于0');
		}
	}
	private function _checkAttrs_2()
	{
		#list($alist , $blist) = $this->attrs;
		#此处不能使用list , list仅能用于数字索引的数组并假定数字索引从 0 开始。
		$alist = current($this->attrs);
		next($this->attrs);
		$blist = current($this->attrs);

		foreach ($alist as $ak => $av)
		{
			foreach ($blist as $bk => $bv)
			{
				#价格
				if (empty($this->attrVal['original_price'][$ak][$bk]))
				{
					$this->addError('attrs' , '请填写 <b>商品属性的基础价</b>');
				}else{
					$price = (double)$this->attrVal['original_price'][$ak][$bk];
					if ($price <= 0)
						$this->addError('attrs' , '<b>商品属性的基础价</b> 必须大于0');
				}

				#库存
				if (empty($this->attrVal['stock'][$ak][$bk]))
				{
					if (empty($this->attrVal['inStock'][$ak][$bk]))
					{
						$this->addError('attrs' , '请设定 <b>商品属性的库存</b>');
					}else{
						$_tp = (int)$this->attrVal['inStock'][$ak][$bk];
						if (!($_tp === -999 || $_tp > 0))
							$this->addError('attrs' , '<b>商品属性的库存</b> 在无限库存的情况设定错误');
					}
				}else{
					if ((int)$this->attrVal['stock'][$ak][$bk] < 1)
						$this->addError('attrs' , '<b>商品属性的库存</b> 在非无限库存的情况下必须大于0');
				}

				#重量
				if (empty($this->attrVal['weight'][$ak][$bk]))
					$this->addError('attrs' , '请填写 <b>商品属性的重量</b>');
				elseif ((double)$this->attrVal['weight'][$ak][$bk] <= 0)
					$this->addError('attrs' , '<b>商品属性的重量</b> 必须大于0');
			}
		}
	}
	private function _checkAttrs_3()
	{
		#list($alist , $blist , $clist) = $this->attrs;
		#此处不能使用list , list仅能用于数字索引的数组并假定数字索引从 0 开始。
		$alist = current($this->attrs);
		next($this->attrs);
		$blist = current($this->attrs);
		next($this->attrs);
		$clist = current($this->attrs);

		foreach ($alist as $ak => $av)
		{
			foreach ($blist as $bk => $bv)
			{
				foreach ($clist as $ck => $cv)
				{
					#价格
					if (empty($this->attrVal['original_price'][$ak][$bk][$ck]))
					{
						$this->addError('attrs' , '请填写 <b>商品属性的基础价</b>');
					}else{
						$price = (double)$this->attrVal['original_price'][$ak][$bk][$ck];
						if ($price <= 0)
							$this->addError('attrs' , '<b>商品属性的基础价</b> 必须大于0');
					}

					#库存
					if (empty($this->attrVal['stock'][$ak][$bk][$ck]))
					{
						if (empty($this->attrVal['inStock'][$ak][$bk][$ck]))
						{
							$this->addError('attrs' , '请设定 <b>商品属性的库存</b>');
						}else{
							$_tp = (int)$this->attrVal['inStock'][$ak][$bk][$ck];
							if (!($_tp === -999 || $_tp > 0))
								$this->addError('attrs' , '<b>商品属性的库存</b> 在无限库存的情况设定错误');
						}
					}else{
						if ((int)$this->attrVal['stock'][$ak][$bk][$ck] < 1)
							$this->addError('attrs' , '<b>商品属性的库存</b> 在非无限库存的情况下必须大于0');
					}

					#重量
					if (empty($this->attrVal['weight'][$ak][$bk][$ck]))
						$this->addError('attrs' , '请填写 <b>商品属性的重量</b>');
					elseif ((double)$this->attrVal['weight'][$ak][$bk][$ck] <= 0)
						$this->addError('attrs' , '<b>商品属性的重量</b> 必须大于0');
				}
			}
		}
	}
	//检查参数组
	public function checkArgs()
	{
		if ($this->args)
		{
			$title		= !empty($this->args['title']) && is_array($this->args['title']) ? $this->args['title'] : array();
			$name		= !empty($this->args['name']) && is_array($this->args['name']) ? $this->args['name'] : array();
			$value		= !empty($this->args['value']) && is_array($this->args['value']) ? $this->args['value'] : array();

			foreach ($value as $vk => $vv)
			{
				if (!(isset($title[$vk]) && is_string($title[$vk]) && trim($title[$vk]) != ''))
					$this->addError('args' , '请填写 <b>参数组名称</b>');

				foreach ($vv as $wk => $wv)
				{
					if (!(isset($name[$vk][$wk]) && is_string($name[$vk][$wk]) && trim($name[$vk][$wk]) != ''))
						$this->addError('args' , '请填写 <b>参数名</b>');
				}
			}
		}
	}
}