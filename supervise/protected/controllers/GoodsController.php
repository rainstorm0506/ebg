<?php

/**
 * 商品 控制器
 *
 * @author 涂先锋
 */
class GoodsController extends SController
{
	#SEO 前缀
	const SEOCODE = 'g';
	
	//列表
	public function actionList()
	{
		$this->checkUserPurview('g.l');
		
		$shelf = (int)$this->getQuery('shelf');
		$search = array(
			'class_one_id'		=> (int)$this->getQuery('class_one'),
			'class_two_id'		=> (int)$this->getQuery('class_two'),
			'class_three_id'	=> (int)$this->getQuery('class_three'),
			'brand_id'			=> (int)$this->getQuery('brand'),
			'shelf_id'			=> $shelf === 419 ? 0 : $shelf,
			'delete_id'			=> $shelf === 419 ? 419 : 0,
			'status_id'			=> (int)$this->getQuery('verify'),
			'keyword'			=> trim((string)$this->getQuery('keyword')),
			'SEOCODE'			=> self::SEOCODE,
		);
		
		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getGoodsCount($search));
		$page->pageSize = 20;
		
		$this->render('list' , array(
			'page'			=> $page,
			'list'			=> $model->getGoodsList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
			'brand'			=> ClassLoad::Only('GoodsBrand')->getSelectList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'shelfStatus'	=> $model->getShelfStatus(),
			'verifyStatus'	=> $model->getVerifyStatus(),
		));
	}
	
	public function actionSeo()
	{
		$this->checkUserPurview('g.seo');
		
		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
		
		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		if (!$info = $model->getGoodsInfo($id))
			$this->error('你编辑的商品信息不存在!');
		
		if(isset($_POST['SeoForm']))
		{
			$form->attributes = $_POST['SeoForm'];
			if($form->validate())
			{
				GlobalSEO::setting($_POST['SeoForm'] , self::SEOCODE , $id);
				$this->redirect(array('list'));
			}
		}
		
		$this->render('seo' , array(
			'form'		=> $form,
			'info'		=> $info,
			'seo'		=> GlobalSEO::getSeoInfo(self::SEOCODE , $id),
			'tree'		=> GlobalGoodsClass::getTree(),
		));
	}
	
	//显示详情
	public function actionShow()
	{
		$this->checkUserPurview('g.s');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
		
		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		if (!$goods = $model->getGoodsInfo($id , true))
			$this->error('商品数据不存在!');
		
		$picMain = $picAttrs = array();
		if ($pic = $model->getGoodsPic($id))
		{
			$picMain = empty($pic['main']) ? array() : $pic['main'];
			unset($pic['main']);
			$picAttrs = $pic;
		}
		if (!$picMain)
			$this->error('商品图片不存在!');
		
		$attrVal = empty($goods['attrs']['attrVal']) ? array() : $goods['attrs']['attrVal'];
		$gAttrs = array();
		if (!empty($goods['attrs']['attrs']))
		{
			$classAttrs = GlobalGoodsAttrs::getClassAttrs($goods['class_one_id'] , $goods['class_two_id'] , $goods['class_three_id']);
			foreach ($goods['attrs']['attrs'] as $k => $v)
			{
				$title = isset($classAttrs[$k]['title']) ? $classAttrs[$k]['title'] : $k;
				$gAttrs[$title] = $v;
			}
		}

		$this->render('show' , array(
			'goods'			=> $goods,
			'picMain'		=> $picMain,
			'picAttrs'		=> $picAttrs,
			'attrVal'		=> $attrVal,
			'gAttrs'		=> $gAttrs,
			'merchant'		=> GlobalMerchant::getMerchantInfo($goods['merchant_id']),
		));
	}

	/**
	 * 审核 & 上下架
	 * 
	 * @param	get		int		gid		商品ID
	 * @param	get		int		K		商品状态
	 */
	public function actionCheck()
	{
		if (!$this->checkUserPurview('g.check' , false))
			$this->jsonOutput(1 , '你没有操作的权限!');
		
		if (!$gid = (int)$this->getQuery('gid'))
			$this->jsonOutput(2 , '错误的商品ID');
		
		if (!$new = (int)$this->getQuery('k'))
			$this->jsonOutput(3 , '错误的参数');
		
		if (!in_array($new , array(401 , 402 , 410 , 411)))
			$this->jsonOutput(3 , '错误的参数');
		
		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		if (!$goods = $model->getGoodsInfo($gid))
			$this->jsonOutput(4 , '商品数据不存在!');
		
		//商品上架 & 通过审核
		if ($new == 401 || $new == 410)
		{
			$mers = GlobalUser::getUserGroup(array($goods['merchant_id']));
			$mers = isset($mers[$goods['merchant_id']]) ? $mers[$goods['merchant_id']] : array();
			if (empty($mers['status_id']) || $mers['status_id'] != 710)
				$this->jsonOutput(4 , '商家状态不正常!');
			
			if ($goods['class_three_id']<= 0)
				$this->jsonOutput(4 , '商品分类错误!');
			
			if ($goods['brand_id']<= 0)
				$this->jsonOutput(4 , '商品品牌错误!');
			
			if (!$goods['goods_num'])
				$this->jsonOutput(4 , '商品货号错误!');

			if ($goods['retail_price']<= 0)
				$this->jsonOutput(4 , '零售价错误!');
			
			if ($goods['min_price'] == 0 && $goods['max_price'] == 0 && $goods['base_price'] == 0)
				$this->jsonOutput(4 , '价格设置错误!');
			
			if (!$goods['title'])
				$this->jsonOutput(4 , '商品标题错误!');
			
			if (!$goods['cover'])
				$this->jsonOutput(4 , '商品封面图错误!');
			
			if (!$goods['content'])
				$this->jsonOutput(4 , '商品详情错误!');
		}
		
		if ($model->setStatus($gid , $new))
			$this->jsonOutput(0);
		
		$this->jsonOutput(-1 , '操作异常!');
	}
	
	/**
	 * views层 显示 上下架状态 / 删除状态
	 * @param	int		$shelfID		商家上下架状态
	 * @param	int		$deleteID		商家删除状态
	 */
	public function viewsShelfName($shelfID , $deleteID)
	{
		if ($deleteID == 419)
		{
			return GlobalStatus::getStatusName($deleteID , 3);
		}else{
			return GlobalStatus::getStatusName($shelfID , 3);
		}
	}
	
	//删除商品
	public function actionDelete()
	{
		$this->checkUserPurview('g.d');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
		
		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		if (!$goods = $model->getGoodsInfo($id))
			$this->error('你删除的商品不存在!');
		
		$model->deletes($id , $goods);
		GlobalGoods::flush();
		$this->redirect(array('list'));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('g.c');
		
		$form = ClassLoad::Only('GoodsForm');/* @var $form GoodsForm */
		$post = isset($_POST['GoodsForm']) ? $_POST['GoodsForm'] : array();
		
		$imgGroup = array();
		if (isset($post['imgGroup']))
		{
			foreach ($post['imgGroup'] as $k => $v)
				$imgGroup[$k] = array_values($v);
		}
		$form->imgGroup		= $imgGroup;
		$form->attrs		= isset($post['attrs']) ? $post['attrs'] : array();
		$form->attrVal		= isset($post['attrVal']) ? $post['attrVal'] : array();
		$form->args			= isset($post['args']) ? $post['args'] : array();
		$form->img			= isset($post['img']) ? $post['img'] : array();
		$this->exitAjaxPost($form , 'formWrap');
		
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model = ClassLoad::Only('Goods');/* @var $model Goods */
				$model->create($post);
				GlobalGoods::flush();
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('create' , array(
			'brand'			=> ClassLoad::Only('GoodsBrand')->getSelectList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'userLayer'		=> GlobalUser::getLayerList(0),
			'tag'			=> GlobalGoodsTag::getTagKV(1),
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('g.m');
		
		$form = ClassLoad::Only('GoodsForm');/* @var $form GoodsForm */
		$post = isset($_POST['GoodsForm']) ? $_POST['GoodsForm'] : array();
		$imgGroup = array();
		if (isset($post['imgGroup']))
		{
			foreach ($post['imgGroup'] as $k => $v)
				$imgGroup[$k] = array_values($v);
		}
		$form->imgGroup		= $imgGroup;
		$form->attrs		= isset($post['attrs']) ? $post['attrs'] : array();
		$form->attrVal		= isset($post['attrVal']) ? $post['attrVal'] : array();
		$form->args			= isset($post['args']) ? $post['args'] : array();
		$form->img			= isset($post['img']) ? $post['img'] : array();
		$this->exitAjaxPost($form , 'formWrap');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
		
		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		if (!$goods = $model->getGoodsInfo($id , true))
			$this->error('商品数据不存在!');
		
		$picMain = $picAttrs = array();
		if ($pic = $model->getGoodsPic($id))
		{
			$picMain = empty($pic['main']) ? array() : $pic['main'];
			unset($pic['main']);
			$picAttrs = $pic;
		}
		if (!$picMain)
			$this->error('商品图片不存在!');
		
		$formError = array();

		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model->setGoodsData($post , $goods , $id , false);
				GlobalGoods::flush();
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}

		$this->render('modify' , array(
			'brand'			=> ClassLoad::Only('GoodsBrand')->getSelectList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'tag'			=> GlobalGoodsTag::getTagKV(1),
			'userLayer'		=> GlobalUser::getLayerList(0),
			'form'			=> $form,
			'formError'		=> $formError,
			'goods'			=> $goods,
			'merchant'		=> GlobalMerchant::getMerchantInfo($goods['merchant_id']),
			'picMain'		=> $picMain,
			'picAttrs'		=> $picAttrs,
		));
	}
	
	//复制商品
	public function actionCopy()
	{
		$this->checkUserPurview('g.copy');
		
		$form = ClassLoad::Only('GoodsForm');/* @var $form GoodsForm */
		$post = isset($_POST['GoodsForm']) ? $_POST['GoodsForm'] : array();
		$imgGroup = array();
		if (isset($post['imgGroup']))
		{
			foreach ($post['imgGroup'] as $k => $v)
				$imgGroup[$k] = array_values($v);
		}
		$form->imgGroup		= $imgGroup;
		$form->attrs		= isset($post['attrs']) ? $post['attrs'] : array();
		$form->attrVal		= isset($post['attrVal']) ? $post['attrVal'] : array();
		$form->args			= isset($post['args']) ? $post['args'] : array();
		$form->img			= isset($post['img']) ? $post['img'] : array();
		$this->exitAjaxPost($form , 'formWrap');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
		
		$model = ClassLoad::Only('Goods');/* @var $model Goods */
		if (!$goods = $model->getGoodsInfo($id , true))
			$this->error('商品数据不存在!');
		
		$picMain = $picAttrs = array();
		if ($pic = $model->getGoodsPic($id))
		{
			$picMain = empty($pic['main']) ? array() : $pic['main'];
			unset($pic['main']);
			$picAttrs = $pic;
		}
		if (!$picMain)
			$this->error('商品图片不存在!');
		
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model->setGoodsData($post , $goods , $id , true);
				GlobalGoods::flush();
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('copy' , array(
			'brand'			=> ClassLoad::Only('GoodsBrand')->getSelectList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'userLayer'		=> GlobalUser::getLayerList(0),
			'tag'			=> GlobalGoodsTag::getTagKV(1),
			'form'			=> $form,
			'formError'		=> $formError,
			'goods'			=> $goods,
			'merchant'		=> GlobalMerchant::getMerchantInfo($goods['merchant_id']),
			'picMain'		=> $picMain,
			'picAttrs'		=> $picAttrs,
		));
	}
	
	/**
	 * 根据分类获取品牌
	 */
	public function actionGetBrand()
	{
		$one_id=$this->getQuery('one_id');
		$two_id=$this->getQuery('two_id');
		$three_id=$this->getQuery('three_id');
		if (!GlobalGoodsClass::verifyClassChain($one_id , $two_id , $three_id))
			$this->jsonOutput(1 , '分类异常!');

		$data=GlobalBrand::getBrand(1 , $one_id , $two_id , $three_id);
		$this->jsonOutput(0,$data);
	}
}
