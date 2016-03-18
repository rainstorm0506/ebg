<?php

/**
 * 积分商城 控制器
 * @author 谭甜
 */
class PointsGoodsController extends SController
{
	#SEO 前缀
	const SEOCODE = 'pg';

	/**
	 * 列表
	 */
	public function actionList(){
		$this->checkUserPurview('pt.lt');
		$keyword= trim((string)$this->getQuery('keyword'));

		$search=array(
			'keyword'=>$keyword,
			'SEOCODE'=> self::SEOCODE,
		);
		$model=ClassLoad::Only('PointsGoods');/* @var $model PointsGoods */
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getListCount($search));
		$page->pageSize = 20;
		$this->render('list' , array(
			'page'			=> $page,
			'list'			=> $model->getList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount())
		));
	}
	/**
	 * 关键字设置
	 */
	public function actionSeo()
	{
		$this->checkUserPurview('pt.seo');

		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);

		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');

		$model = ClassLoad::Only('PointsGoods');/* @var $model PointsGoods */
		if (!$info = $model->intro($id))
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
		));
	}

	/**
	 * 添加
	 */
	public function actionCreate(){
		$this->checkUserPurview('pt.ce');
		$form = ClassLoad::Only('PointsGoodsForm');/* @var $form PointsGoodsForm */
		$post = isset($_POST['PointsGoodsForm']) ? $_POST['PointsGoodsForm'] : array();
		$this->exitAjaxPost($form );

		$formError = array();

		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model = ClassLoad::Only('PointsGoods');/* @var $model PointsGoods */
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
			'brand'			=> ClassLoad::Only('GoodsBrand')->getSelectList(),
			'class'			=> GlobalGoodsClass::getTree(),
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	/**
	 * 删除
	 */
	public function actionClear(){
		$this->checkUserPurview('pt.cl');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id！');

		$model = ClassLoad::Only('PointsGoods');/* @var $model PointsGoods */
		$model->clear($id);
		$this->redirect(array('list'));
	}
	/**
	 * 编辑
	 */
	public function actionModify(){
		$this->checkUserPurview('pt.my');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id！');

		$model=ClassLoad::Only('PointsGoods');/* @var $model PointsGoods */
		if (!$goods = $model->intro($id))
			$this->error('商品数据不存在!');

		$form = ClassLoad::Only('PointsGoodsForm');/* @var $form PointsGoodsForm */
		$post = isset($_POST['PointsGoodsForm']) ? $_POST['PointsGoodsForm'] : array();
		$form->attrs		= isset($post['attrs']) ? $post['attrs'] : array();
		$form->attrVal		= isset($post['attrVal']) ? $post['attrVal'] : array();
		$form->args			= isset($post['args']) ? $post['args'] : array();
		$form->img			= isset($post['img']) ? $post['img'] : $goods['img'];
		$this->exitAjaxPost($form , 'formWrap');

		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model->modify($post);
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
			'form'			=> $form,
			'formError'		=> $formError,
			'goods'			=> $goods
		));
	}
	/**
	* 详情
	*/
	public function actionIntro(){
		header('content-type:text/html;charset=utf-8;');
		$this->checkUserPurview('pt.my');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id！');

		$model=ClassLoad::Only('PointsGoods');/* @var $model PointsGoods */
		if (!$goods = $model->intro($id))
			$this->error('商品数据不存在!');

		$this->render('intro' , array(
			'intro'			=> $goods,
			'attr'          =>$model->getGoodsAttr($goods['id'])
		));
	}

	/**
	 * 上架，下架
	 */
	public function actionHandleShelf(){
		if(!$id=$this->getQuery('id'))
			$this->error('商品id错误');

		if(!$shelf=$this->getQuery('shelf'))
			$this->error('错误的操作');

		$model=ClassLoad::Only('PointsGoods');/* @var $model PointsGoods */
		$row=$model->handleShelf($shelf,$id);

		if(empty($row)){
			$this->jsonOutput(2 , '操作有误');
		}else{
			$this->jsonOutput(0,$row);
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