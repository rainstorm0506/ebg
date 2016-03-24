<?php
class ActGoodsController extends SController
{
	/**
	 * 商品列表
	 */
	public function actionList()
	{
		$this->checkUserPurview('ag.lt');

		$search=array(
			'keyword'	=> trim((string)$this->getQuery('keyword')),
			'shelf'		=> (int)$this->getQuery('shelf')
		);
		$shelfStatus=array(0=>'未启用' , 1=>'已启用' );
		$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getGoodsCount($search));
		$page->pageSize = 20;
		$this->render('list' , array(
			'page'			=> $page,
			'shelfStatus'	=> $shelfStatus,
			'list'			=> $model->getList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount())
		));
	}
	/**
	 * 添加活动商品
	 */
	public function actionCreate()
	{
		$this->checkUserPurview('ag.cr');
		$form = ClassLoad::Only('ActGoodsForm');/* @var $form ActGoodsForm */
		$post = isset($_POST['ActGoodsForm']) ? $_POST['ActGoodsForm'] : array();
		$form->img		= isset($post['img']) ? $post['img'] : array();
		$this->exitAjaxPost($form , 'formWrap');

		$class=GlobalGoodsClass::getTree();
		$brand=ClassLoad::Only('GoodsBrand')->getSelectList();
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
				$model->create($post);
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);

				$form->clearErrors();
			}
		}
		$this->render('create' , array(
			'formError'	=> $formError,
			'form'		=> $form,
			'class'		=> $class,
			'brand'		=> $brand
		));
	}
	/**
	 * 删除活动商品
	 */
	public function actionClear()
	{
		$this->checkUserPurview('ag.cl');
		if(!$id=$this->getQuery('id'))
			$this->error('错误的操作!');

		$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
		if (!$goods = $model->getGoodsInfo($id))
			$this->error('你要删除的商品不存在!');

		$model->clear($id);
		$this->redirect(array('list'));
	}
	/**
	 * 活动商品详情
	 */
	public function actionInfo()
	{
		$this->checkUserPurview('ag.lt');
		if(!$id=$this->getQuery('id'))
			$this->error('错误的操作!');

		$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
		if (!$info = $model->getGoodsInfo($id , true))
			$this->error('商品数据不存在!');

		$attrVal = empty($info['attrs']['attrVal']) ? array() : $info['attrs']['attrVal'];
		$gAttrs = array();
		if (!empty($info['attrs']['attrs']))
		{
			$classAttrs = GlobalGoodsAttrs::getClassAttrs($info['class_one_id'] , $info['class_two_id'] , $info['class_three_id']);
			foreach ($info['attrs']['attrs'] as $k => $v)
			{
				$title = isset($classAttrs[$k]['title']) ? $classAttrs[$k]['title'] : $k;
				$gAttrs[$title] = $v;
			}
		}
		$this->render('info' , array(
			'merchant'	=> GlobalMerchant::getMerchantInfo($info['merchant_id']),
			'attrVal'	=> $attrVal,
			'gAttrs'	=> $gAttrs,
			'info'		=> $info
		));
	}
	/**
	 * 编辑
	 */
	public function actionModify()
	{
		$this->checkUserPurview('ag.my');
		if(!$id=$this->getQuery('id'))
			$this->error('错误的操作!');

		$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
		if (!$info = $model->getGoodsInfo($id , true))
			$this->error('商品数据不存在!');
	}
	/**
	 * 全新复制列表
	 */
	public function actionGoodsList()
	{
		$this->checkUserPurview('ag.cp');
		$attrs = array(
			'keyword'			=> (string)$this->getQuery('keyword')
		);

		$model = ClassLoad::Only('Goods');/* @var $model Goods */

		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->setItemCount($model->getGoodsCount($attrs , true));
		$page->pageSize = 10;

		$this->render('copyList' , array_merge($attrs , array(
			'page'			=> $page,
			'list'			=> $model->getGoodsList($attrs , true , $page->getOffset() , $page->getLimit() , $page->getItemCount())
		)));
	}
	/**
	 * 复制商品
	 */
	public function actionCopy()
	{
		$this->checkUserPurview('ag.cp');
		$form = ClassLoad::Only('ActGoodsForm');/* @var $form ActGoodsForm */
		$post = isset($_POST['ActGoodsForm']) ? $_POST['ActGoodsForm'] : array();
		$form->img		= isset($post['img']) ? $post['img'] : array();
		$this->exitAjaxPost($form , 'formWrap');

		$class=GlobalGoodsClass::getTree();
		$brand=ClassLoad::Only('GoodsBrand')->getSelectList();
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model = ClassLoad::Only('ActGoods');/* @var $model ActGoods */
				$model->create($post);
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);

				$form->clearErrors();
			}
		}
		$info=array();
		$this->render('copy' , array(
			'info'		=> $info,
			'formError'	=> $formError,
			'form'		=> $form,
			'class'		=> $class,
			'brand'		=> $brand
		));
	}
}