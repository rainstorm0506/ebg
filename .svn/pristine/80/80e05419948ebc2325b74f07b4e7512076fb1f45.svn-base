<?php
/**
 * 商品
 * 
 * @author simon
 */
Yii::import('system.extensions.editor.UEditor');
class GoodsController extends MerchantController
{
	const SEOCODE = 'g';
	
	//会员类型
	public $userType = array(1=>'个人会员' , 2=>'企业会员' , 3=>'商家会员');
	
	public function actionSeo()
	{
		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);
	
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
	
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		if (!$goods = $model->getGoodsInfo($id , false , false))
			$this->error('你编辑的商品信息不存在!');
	
		if(isset($_POST['SeoForm']))
		{
			$form->attributes = $_POST['SeoForm'];
			if($form->validate())
			{
				GlobalSEO::setting($_POST['SeoForm'] , self::SEOCODE , $id);
				$this->redirect(array('index'));
			}
		}
	
		$this->render('seo' , array(
			'form'		=> $form,
			'info'		=> $goods,
			'seo'		=> GlobalSEO::getSeoInfo(self::SEOCODE , $id),
			'tree'		=> GlobalGoodsClass::getTree(),
		));
	}
	
	//复制列表
	public function actionCopy()
	{
		$attrs = array(
			'keyword'			=> (string)$this->getQuery('keyword'),
			'class_one_id'		=> (int)$this->getQuery('class_one_id'),
			'class_two_id'		=> (int)$this->getQuery('class_two_id'),
			'class_three_id'	=> (int)$this->getQuery('class_three_id'),
		);
		
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->setItemCount($model->getGoodsCount($attrs , true));
		$page->pageSize = 10;
		
		$this->render('copyList' , array_merge($attrs , array(
			'page'			=> $page,
			'list'			=> $model->getGoodsList($attrs , true , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
			'class'			=> GlobalGoodsClass::getTree(),
		)));
	}
	
	//复制显示详情
	public function actionCopyShow()
	{
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
	
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		if (!$goods = $model->getGoodsInfo($id , true , true))
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
	
		$this->render('copyShow' , array(
			'goods'			=> $goods,
			'picMain'		=> $picMain,
			'picAttrs'		=> $picAttrs,
			'attrVal'		=> $attrVal,
			'gAttrs'		=> $gAttrs,
		));
	}
	
	//复制
	public function actionCopyExec()
	{
		$form = ClassLoad::Only('GoodsForm');/* @var $form GoodsForm */
		$form->copy = true;
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
		
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		if (!$goods = $model->getGoodsInfo($id , true , true))
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
				$this->redirect(array('index'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('copyExec' , array(
			'brand'			=> $model->getBrandList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'userLayer'		=> $model->getUserLayer(0),
			'form'			=> $form,
			'formError'		=> $formError,
			'goods'			=> $goods,
			'picMain'		=> $picMain,
			'picAttrs'		=> $picAttrs,
			'tag'			=> GlobalGoodsTag::getTagKV(1),
		));
	}
	
	public function actionIndex()
	{
		$timeStart	= (string)$this->getQuery('timeStart');
		$timeEnd	= (string)$this->getQuery('timeEnd');
		$attrs = array(
			'keyword'			=> (string)$this->getQuery('keyword'),
			'shelf_id'			=> (int)$this->getQuery('shelf_id'),
			'status_id'			=> (int)$this->getQuery('status_id'),
			'class_one_id'		=> (int)$this->getQuery('class_one_id'),
			'class_two_id'		=> (int)$this->getQuery('class_two_id'),
			'class_three_id'	=> (int)$this->getQuery('class_three_id'),
			'timeStart'			=> min($timeStart , $timeEnd),
			'timeEnd'			=> max($timeStart , $timeEnd),
		);
		
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->setItemCount($model->getGoodsCount($attrs , false));
		$page->pageSize = 10;
		
		$this->render('index' , array_merge($attrs , array(
			'page'			=> $page,
			'list'			=> $model->getGoodsList($attrs , false , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
			'class'			=> GlobalGoodsClass::getTree(),
			'shelfStatus'	=> $model->getShelfStatus(),
			'verifyStatus'	=> $model->getVerifyStatus(),
		)));
	}
	
	//添加
	public function actionCreate()
	{
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
		
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model->create($post);
				GlobalGoods::flush();
				$this->redirect(array('index'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('create' , array(
			'brand'			=> $model->getBrandList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'userLayer'		=> $model->getUserLayer(0),
			'form'			=> $form,
			'formError'		=> $formError,
			'tag'			=> GlobalGoodsTag::getTagKV(1),
		));
	}
	
	//编辑
	public function actionModify()
	{
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
	
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		if (!$goods = $model->getGoodsInfo($id , false , true))
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
				$this->redirect(array('index'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
	
		$this->render('modify' , array(
			'brand'			=> $model->getBrandList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'userLayer'		=> $model->getUserLayer(0),
			'form'			=> $form,
			'formError'		=> $formError,
			'goods'			=> $goods,
			'picMain'		=> $picMain,
			'picAttrs'		=> $picAttrs,
			'tag'			=> GlobalGoodsTag::getTagKV(1),
		));
	}
	
	//删除商品
	public function actionDelete()
	{
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
	
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		if (!$goods = $model->getGoodsInfo($id , false , false))
			$this->error('你删除的商品不存在!');
	
		$model->deletes($id , $goods);
		GlobalGoods::flush();
		$this->redirect(array('index'));
	}
	
	/**
	 * 审核上下架
	 *
	 * @param	get		int		gid		商品ID
	 * @param	get		int		K		商品状态
	 */
	public function actionCheck()
	{
		if (!$gid = (int)$this->getQuery('gid'))
			$this->jsonOutput(1 , '错误的商品ID');
		
		if (!in_array(($new = (int)$this->getQuery('k')) , array(1 , 0)))
			$this->jsonOutput(2 , '错误的参数');
		
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		if (!$goods = $model->getGoodsInfo($gid , false , false))
			$this->jsonOutput(3 , '商品数据不存在!');
		
		//商品上架
		if ($new == 1)
		{
			$mers = GlobalUser::getUserGroup(array($this->getMerchantID()));
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
	
	//显示详情
	public function actionShow()
	{
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');
		
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		if (!$goods = $model->getGoodsInfo($id , false , true))
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
		));
	}
	
	public function actionGetClassArgs()
	{
		$oneId = (int)$this->getQuery('one_id');
		$twoId = (int)$this->getQuery('two_id');
		$threeId = (int)$this->getQuery('three_id');
	
		if (!GlobalGoodsClass::verifyClassChain($oneId , $twoId , $threeId))
			$this->jsonOutput(1 , '分类异常!');
		
		$model = ClassLoad::Only('GoodsMer');/* @var $model GoodsMer */
		$this->jsonOutput(0 , array_values($model->getArgsInfo($oneId , $twoId , $threeId)));
	}
	
	public function actionGetClassAttrs()
	{
		$oneId = (int)$this->getQuery('one_id');
		$twoId = (int)$this->getQuery('two_id');
		$threeId = (int)$this->getQuery('three_id');
		
		if (!GlobalGoodsClass::verifyClassChain($oneId , $twoId , $threeId))
			$this->jsonOutput(1 , '分类异常!');
		
		$data = array();
		foreach (GlobalGoodsAttrs::getClassAttrs($oneId , $twoId , $threeId) as $key => $val)
		{
			unset($val['parent_unite_code'] , $val['unite_code'] , $val['class_one_id'] , $val['class_two_id'] , $val['class_three_id'] , $val['rank']);
			
			$ct = array();
			if (!empty($val['child']))
			{
				foreach ($val['child'] as $k => $v)
					$ct[$k] = $v['title'];
			}
			
			$val['child'] = $ct;
			$data[$key] = $val;
		}

		$this->jsonOutput(0 , $data);
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
	/**
	 * 根据分类获取品牌
	 */
	public function actionGetBrand()
	{
		$one_id=$this->getQuery('one_id');
		$two_id=$this->getQuery('two_id');
		$three_id=$this->getQuery('three_id');

		$data=GlobalBrand::getBrand(1 , $one_id , $two_id , $three_id);
		$this->jsonOutput(0,$data);
	}
}