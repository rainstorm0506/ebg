<?php
class GoodsController extends ApiController
{
	/**
	 * 全新商品模块 - 上下架商品
	 * 
	 * @param		int				gid				商品ID
	 * @param		int				action			操作动作 , 1=上架 , 2=下架
	 * @param		int				apt				APP抛数据的时间
	 */
	public function actionAudit()
	{
		$form			= ClassLoad::Only('GoodsApiForm');/* @var $form GoodsApiForm */
		$form->gid		= (int)$this->getPost('gid');
		$form->action	= (int)$this->getPost('action');
		$form->apt		= (int)$this->getPost('apt');
		
		if($form->validateAudit())
		{
			$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
			if ($model->goodsAudit($form->gid , $form->action))
			{
				$this->jsonOutput(0);
			}else{
				$this->jsonOutput(2 , '操作失败!');
			}
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	/**
	 * 全新商品模块 - 商品列表
	 * @param		int				type			类型 , 1=商家自身的商品列表 , 2=复制的商品列表(全部商家) , 3=其他商家的商品列表
	 * @param		int				mid				当 type=3 的时候 , mid>0 , 其他时候 mid=0
	 * @param		string			keyword			搜索关键词
	 * @param		int				status			状态 , 0=全部 , 1=待审核 , 2=通过审核的 , 3=审核拒绝的 , 4=上架 , 5=下架
	 * @param		int				page			页码
	 * @param		int				apt				APP抛数据的时间
	 */
	public function actionList()
	{
		$form			= ClassLoad::Only('GoodsApiForm');/* @var $form GoodsApiForm */
		$form->type		= (int)$this->getPost('type');
		$form->mid		= (int)$this->getPost('mid');
		$form->status	= (int)$this->getPost('status');
		$form->page		= (int)$this->getPost('page');
		$form->apt		= (int)$this->getPost('apt');
		$form->keyword	= (string)$this->getPost('keyword');
		
		if($form->validateGoodsList())
		{
			$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
			
			$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
			$page->pageSize = 10;
			$page->setCurrentPage($form->page > 0 ? ($form->page - 1) : 0);
			$page->setItemCount($model->getGoodsCount($form));
			
			if ($list = $model->getGoodsList($form , $page , $form->page))
			{
				$this->jsonOutput ( 0, $list );
			}else{
				$this->jsonOutput ( 2, '没有数据！' );
			}
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	//商品的三级分类 & 数量组的默认值
	public function actionClass()
	{
		$ct = (int)$this->getPost('ct');
		$this->jsonOutput(0 , array(
			'category'	=> $ct ==2 ? GlobalGoodsClass::getTree() : GlobalGoodsClass::getApiTree(),
			'amount'	=> GlobalGoods::getApiAmountScope(),
		));
	}
	
	//商品分类对应的属性组 & 参数组
	public function actionAttrsAndArgs()
	{
		$form = ClassLoad::Only('GoodsApiForm');/* @var $form GoodsApiForm */
		$form->cid = (int)$this->getPost('cid');
		$form->apt = (int)$this->getPost('apt');
		
		if($form->validateAttrsAndArgs())
		{
			$row=GlobalBrand::getBrand(1 , 0 , 0 , $form->cid);
			$data=array();
			foreach($row as $k=>$v)
			{
				$data[]=array('id'=>$k,'title'=>$v);
			}
			$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
			$this->jsonOutput(0 , array(
				'attrs' => $model->getClassAttrs($form->class[1] , $form->class[2] , $form->cid),
				'brand'	=> $data
			));
		}
		$this->jsonOutput(1,$this->getFormError($form));
	}
	
	/**
	 * 全新商品模块 - 商品详情
	 * @param		int				type				获得数据后的用途 , 1=复制操作 , 2=其他操作(编辑 , 显示等)
	 * @param		int				gid					商品ID
	 * @param		int				apt					APP抛数据的时间
	 */
	public function actionShow()
	{
		$form			= ClassLoad::Only('GoodsApiForm');/* @var $form GoodsApiForm */
		$form->type		= (int)$this->getPost('type');
		$form->gid		= (int)$this->getPost('gid');
		$form->apt		= (int)$this->getPost('apt');
		
		if($form->validateGoodsShow())
		{
			$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
			if ($goods = $model->getGoodsInfo($form->gid , true))
			{
				if ($form->type == 1)
				{
					$goods['goods_num']		= '';
					$goods['title']			= '';
				}
				$goods['pic'] = $model->getGoodsPic($form->gid);
				
				$goods['class_one_name']	= GlobalGoodsClass::getClassName($goods['class_one_id']);
				$goods['class_two_name']	= GlobalGoodsClass::getClassName($goods['class_two_id']);
				$goods['class_three_name']	= GlobalGoodsClass::getClassName($goods['class_three_id']);
				
				$blist						= GlobalBrand::getList(0);
				$goods['brand_zh_name']		= empty($blist[$goods['brand_id']][0]) ? '' : $blist[$goods['brand_id']][0];
				$goods['brand_en_name']		= empty($blist[$goods['brand_id']][1]) ? '' : $blist[$goods['brand_id']][1];
				
				if (!empty($goods['user_layer_ratio']))
				{
					$tx = array();
					foreach ($goods['user_layer_ratio'] as $k => $v)
						$tx[] = array('key'=>$k , 'value'=>$v);
					
					$goods['user_layer_ratio'] = $tx;
				}
				
				unset($goods['content']);
				
				$goods['amount_ratio']		= empty($goods['amount_ratio']) ? null : $goods['amount_ratio'];
				$goods['user_layer_ratio']	= empty($goods['user_layer_ratio']) ? null : $goods['user_layer_ratio'];
				$goods['attrs']				= empty($goods['attrs']) ? null : $goods['attrs'];
				$goods['join_goods']		= empty($goods['join_goods']) ? null : $goods['join_goods'];
				$goods['pic']				= empty($goods['pic']) ? null : $goods['pic'];
				
				$this->jsonOutput(0 , $goods);
			}else{
				$this->jsonOutput(2 , '查询不到数据');
			}
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
	
	/**
	 * 全新商品模块 - 添加/修改/复制商品
	 * 
	 * @param		int				type				类别 , 1=添加 , 2=修改 , 3=复制
	 * @param		int				gid					商品ID (当 type=1 , gid=0 ; type=2 , gid=修改商品ID ; type=3 , gid=被复制的商品ID)
	 * @param		int				class_one_id		第一级分类ID
	 * @param		int				class_two_id		第二级分类ID
	 * @param		int				class_three_id		第三级分类ID
	 * @param		int				brand_id			品牌ID
	 * @param		int				stock				库存 (-999表示无限库存)
	 * @param		int				apt					APP抛数据的时间
	 * 
	 * @param		varchar(15)		goods_num			商品货号 (不填时,自动生成)
	 * @param		varchar(100)	title				商品名称
	 * @param		varchar(100)	vice_title			商品副标题
	 * @param		varchar(250)	cover				商品主图片
	 * 
	 * @param		double(10,2)	retail_price		零售价
	 * @param		double(10,2)	base_price			基础价
	 * @param		double(10,2)	weight				重量 (KG)
	 * 
	 * @param		array			attrs				商品属性
	 * @param		array			attrVal				 商品属性组合后的 [基础价 , 库存 , 重量]
	 * @param		array			img					图片组
	 * @param		array			args				商品参数
	 * @param		array			userLayer			会员及价格
	 * @param		array			amount				数量及价格
	 */
	public function actionUpdate()
	{
		$form = ClassLoad::Only('GoodsApiForm');/* @var $form GoodsApiForm */
		$form->type				= (int)$this->getPost('type');
		$form->gid				= (int)$this->getPost('gid');
		$form->class_one_id		= (int)$this->getPost('class_one_id');
		$form->class_two_id		= (int)$this->getPost('class_two_id');
		$form->class_three_id	= (int)$this->getPost('class_three_id');
		$form->brand_id			= (int)$this->getPost('brand_id');
		$form->stock			= (int)$this->getPost('stock');
		$form->apt				= (int)$this->getPost('apt');
		$form->goods_num		= (string)$this->getPost('goods_num');
		$form->title			= (string)$this->getPost('title');
		$form->vice_title		= (string)$this->getPost('vice_title');
		$form->cover			= (string)$this->getPost('cover');
		$form->retail_price		= (double)$this->getPost('retail_price');
		$form->base_price		= (double)$this->getPost('base_price');
		$form->weight			= (double)$this->getPost('weight');
		$form->attrs			= (array)$this->getPost('attrs');
		$form->attrVal			= (array)$this->getPost('attrVal');
		$form->args				= (array)$this->getPost('args');
		$form->img				= (array)$this->getPost('img');
		$form->userLayer		= (array)$this->getPost('userLayer');
		$form->amount			= (array)$this->getPost('amount');
		
		if ($form->validate())
		{
			$model = ClassLoad::Only('GoodsApi');/* @var $model GoodsApi */
			if ($gid = $model->goodsUpdate($form))
				$this->jsonOutput(0 , array('id' => $gid));
			else
				$this->jsonOutput(2 , '操作失败!');
		}
		$this->jsonOutput(1 , $this->getFormError($form));
	}
}