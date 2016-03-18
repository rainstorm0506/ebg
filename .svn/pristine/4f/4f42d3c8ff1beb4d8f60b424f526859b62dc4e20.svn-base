<?php
class GoodsApiForm extends ApiForm
{
	public $cid , $apt , $class , $page , $status , $keyword , $action , $mid;
	
	public $type , $gid , $class_one_id , $class_two_id , $class_three_id , $brand_id , $goods_num , $title , $vice_title , $retail_price , $base_price;
	public $stock , $weight , $cover;
	public $attrs = array() , $attrVal = array() , $img = array() , $args = array() , $userLayer = array() , $amount = array();
	
	public function rules()
	{
		//适配 IOS
		if (!empty($this->attrs))
		{
			$attrs = array();
			foreach ($this->attrs as $k => $v)
			{
				if (is_int($k))
					$attrs = array_merge($attrs , $v);
			}
			$this->attrs = $attrs;
		}
		
		$rules = array(
			array('apt , type , gid , class_one_id , class_two_id , class_three_id , brand_id , title , retail_price , img , cover , userLayer , amount' ,'required') ,
			array('type' , 'numerical' , 'integerOnly' => true , 'min'=>1 , 'max'=>3) ,
			array('gid' , 'numerical' , 'integerOnly' => true , 'min' => 0) ,
			array('stock' , 'numerical' , 'integerOnly' => true) ,
			array('title , vice_title' , 'length' , 'max' => 100) ,
			array('goods_num' , 'length' , 'max' => 15) ,
			array('cover' , 'length' , 'max' => 250) ,
			array('attrs , attrVal , img , args , userLayer , amount' , 'type' , 'type'=>'array') ,
			array('apt , class_one_id , class_two_id , class_three_id , brand_id' , 'numerical' , 'integerOnly' => true , 'min' => 1) ,
			array('title', 'checkTitle'),
			array('class_three_id', 'checkClass'),
			array('brand_id', 'checkBrand'),
			array('goods_num' , 'checkGoodsNum'),
			array('args' , 'checkArgs'),
		);
		
		if ($this->attrs)
		{
			$rules[] = array('attrs' , 'checkAttrs');
		}else{
			$rules[] = array('retail_price , base_price , weight', 'numerical');
			$rules[] = array('retail_price , base_price , weight' , 'checkDouble');
			$rules[] = array('base_price' , 'checkPrice');
			$rules[] = array('stock' , 'checkStock');
		}
		
		return $rules;
	}
	
	public function validateAudit()
	{
		$rt = true;
		if ($this->gid < 1)
		{
			$rt = false;
			$this->addError('gid' , '商品ID错误');
		}
		
		if ($this->action < 1 || $this->status > 2)
		{
			$rt = false;
			$this->addError('action' , '操作动作错误');
		}
		
		if ($this->apt < 1)
		{
			$rt = false;
			$this->addError('apt' , '抛送时间错误');
		}
		
		$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
		if (!$goods = $model->getGoodsInfo($this->gid , false))
		{
			$rt = false;
			$this->addError('gid' , '商品不存在');
		}
		
		if (empty($goods['merchant_id']) || $this->getMerchantID() != $goods['merchant_id'])
		{
			$rt = false;
			$this->addError('gid' , '你不能操作别人的商品');
		}
		
		return $rt;
	}
	
	public function validateGoodsList()
	{
		$rt = true;
		if ($this->type < 1 || $this->type > 3)
		{
			$rt = false;
			$this->addError('type' , '类型错误');
		}
		
		if ($this->type == 3 && $this->mid <= 0)
		{
			$rt = false;
			$this->addError('mid' , '商家ID错误');
		}
		
		if ($this->status < 0 || $this->status > 5)
		{
			$rt = false;
			$this->addError('type' , '状态错误');
		}
		
		if ($this->page < 1)
		{
			$rt = false;
			$this->addError('page' , '页码错误');
		}
		
		if ($this->apt < 1)
		{
			$rt = false;
			$this->addError('apt' , '抛送时间错误');
		}
		
		return $rt;
	}
	
	public function validateGoodsShow()
	{
		$rt = true;
		if ($this->type >2 || $this->type < 1)
		{
			$rt = false;
			$this->addError('type' , '类型错误');
		}
		return $rt;
	}
	
	public function attributeLabels()
	{
		return array(
			'title'				=> '商品名称',
			'vice_title'		=> '商品副标题',
			'goods_num'			=> '商品货号',
			'brand_id'			=> '品牌',
			'class_three_id'	=> '商品分类',
			'cover'				=> '商品主图',
			'retail_price'		=> '零售价',
			'base_price'		=> '基础价',
			'weight'			=> '重量',
			'stock'				=> '库存',
		);
	}
	
	//检查库存
	public function checkStock()
	{
		if ($this->stock != -999 && $this->stock <= 0)
			$this->addError('stock' , '请设定正确的库存'.$this->getAttributeLabel('stock'));
	}
	
	//检查价格设定
	public function checkPrice()
	{
		if ($this->base_price > $this->retail_price)
			$this->addError('retail_price' , '基础价不能大于零售价');
	}
	
	//检查浮点数是否小于0
	public function checkDouble($tag)
	{
		if ($this->{$tag} <= 0)
			$this->addError($tag , $this->getAttributeLabel($tag).'必须大于0');
	}
	
	//检查属性组设定
	public function checkAttrs()
	{
		switch (count($this->attrs))
		{
			case 1		: $this->_checkAttrs_1(); break;
			case 2		: $this->_checkAttrs_2(); break;
			case 3		: $this->_checkAttrs_3(); break;
			default		: $this->addError('attrs' , '商品属性异常!');
		}
	}
	
	private function _checkAttrs_1()
	{
		$retailPrice = (double)$this->retail_price;
		foreach (current($this->attrs) as $ak => $av)
		{
			#价格
			$price = empty($this->attrVal['price'][$ak]) ? 0 : (double)$this->attrVal['price'][$ak];
			if ($price <= 0)
				$this->addError('attrs' , '商品属性的基础价必须大于0');
			elseif ($price > $retailPrice)
				$this->addError('attrs' , '商品属性的基础价不能大于零售价');
			
			#库存
			$stock = empty($this->attrVal['stock'][$ak]) ? 0 : (int)$this->attrVal['stock'][$ak];
			if ($stock == -999)
			{
				$this->attrVal['inStock'][$ak] = -999;
				unset($this->attrVal['stock'][$ak]);
			}else{
				$this->attrVal['inStock'][$ak] = '';
				
				if ($stock <= 0)
					$this->addError('attrs' , '商品属性的库存在非无限库存的情况下必须大于0');
			}
			
			#重量
			$weight = empty($this->attrVal['weight'][$ak]) ? 0 : (double)$this->attrVal['weight'][$ak];
			if ($weight <= 0)
				$this->addError('attrs' , '商品属性的重量必须大于0');
		}
	}
	
	private function _checkAttrs_2()
	{
		$retailPrice = (double)$this->retail_price;
	
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
				$price = empty($this->attrVal['price'][$ak][$bk]) ? 0 : (double)$this->attrVal['price'][$ak][$bk];
				if ($price <= 0)
					$this->addError('attrs' , '商品属性的基础价必须大于0');
				elseif ($price > $retailPrice)
				$this->addError('attrs' , '商品属性的基础价不能大于零售价');
				
				#库存
				$stock = empty($this->attrVal['stock'][$ak][$bk]) ? 0 : (int)$this->attrVal['stock'][$ak][$bk];
				if ($stock == -999)
				{
					$this->attrVal['inStock'][$ak][$bk] = -999;
					unset($this->attrVal['stock'][$ak][$bk]);
				}else{
					$this->attrVal['inStock'][$ak][$bk] = '';
				
					if ($stock <= 0)
						$this->addError('attrs' , '商品属性的库存在非无限库存的情况下必须大于0');
				}
				
				#重量
				$weight = empty($this->attrVal['weight'][$ak][$bk]) ? 0 : (double)$this->attrVal['weight'][$ak][$bk];
				if ($weight <= 0)
					$this->addError('attrs' , '商品属性的重量必须大于0');
			}
		}
	}
	
	private function _checkAttrs_3()
	{
		$retailPrice = (double)$this->retail_price;
	
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
					$price = empty($this->attrVal['price'][$ak][$bk][$ck]) ? 0 : (double)$this->attrVal['price'][$ak][$bk][$ck];
					if ($price <= 0)
						$this->addError('attrs' , '商品属性的基础价必须大于0');
					elseif ($price > $retailPrice)
					$this->addError('attrs' , '商品属性的基础价不能大于零售价');
						
					#库存
					$stock = empty($this->attrVal['stock'][$ak][$bk][$ck]) ? 0 : (int)$this->attrVal['stock'][$ak][$bk][$ck];
					if ($stock == -999)
					{
						$this->attrVal['inStock'][$ak][$bk][$ck] = -999;
						unset($this->attrVal['stock'][$ak][$bk][$ck]);
					}else{
						$this->attrVal['inStock'][$ak][$bk][$ck] = '';
					
						if ($stock <= 0)
							$this->addError('attrs' , '商品属性的库存在非无限库存的情况下必须大于0');
					}
					
					#重量
					$weight = empty($this->attrVal['weight'][$ak][$bk][$ck]) ? 0 : (double)$this->attrVal['weight'][$ak][$bk][$ck];
					if ($weight <= 0)
						$this->addError('attrs' , '商品属性的重量必须大于0');
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
					$this->addError('args' , '请填写参数组名称');
	
				foreach ($vv as $wk => $wv)
				{
					if (!(isset($name[$vk][$wk]) && is_string($name[$vk][$wk]) && trim($name[$vk][$wk]) != ''))
						$this->addError('args' , '请填写参数名');
				}
			}
		}
	}
	
	//检查商品编号
	public function checkGoodsNum()
	{
		if ($this->goods_num)
		{
			$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
			if ($model->checkGoodsNum($this->goods_num , $this->type == 2 ? $this->gid : 0))
				$this->addError('goods_num' , '你填写的商品货号已存在.');
		}
	}
	
	//检查品牌
	public function checkBrand()
	{
		if (!GlobalBrand::isBrandID($this->brand_id))
			$this->addError('brand_id' , '请选择正确的商品品牌.');
	}
	
	//检查分类
	public function checkClass()
	{
		if ($this->class_one_id>0 && $this->class_two_id>0 && $this->class_three_id>0)
		{
			if (!GlobalGoodsClass::verifyClassChain($this->class_one_id , $this->class_two_id , $this->class_three_id))
				$this->addError('class_three_id' , '请选择正确的 商品分类.');
		}else{
			$this->addError('class_three_id' , '请选择 商品分类.');
		}
	}
	
	//检查商品名称
	public function checkTitle()
	{
		$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
		if ($model->checkTitle($this->title , $this->gid , $this->type == 3))
			$this->addError('title' , '你填写的商品名称已存在.');
	}
	
	public function validateAttrsAndArgs()
	{
		$rt = true;
		if (empty($this->cid) || $this->cid <= 0)
		{
			$rt = false;
			$this->addError('cid' , '分类ID错误');
		}
		
		$this->class = GlobalGoodsClass::getClassChainById($this->cid);
		if (empty($this->class[2]) || $this->class[2] <= 0)
		{
			$rt = false;
			$this->addError('cid' , '请传递正确的分类ID');
		}
		
		return $rt;
	}
}