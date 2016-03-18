<?php

/**
 * 二手商品 控制器
 * @author 谭甜
 */
class UsedGoodsController extends SController
{
	#SEO 前缀
	const SEOCODE = 'ug';
	//列表
	public function actionList()
	{
		$this->checkUserPurview('ug.lt');
		$model = ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */

		$shelf = (int)$this->getQuery('shelf');
		$search = array(
			'class_one_id'		=> (int)$this->getQuery('class_one'),
			'class_two_id'		=> (int)$this->getQuery('class_two'),
			'class_three_id'	=> (int)$this->getQuery('class_three'),
			'shelf_id'          => $shelf === 419 ? 0 : $shelf,
			'status_id'         => (int)$this->getQuery('verify'),
			'keyword'           => trim((string)$this->getQuery('keyword')),
			'SEOCODE'			=> self::SEOCODE,
		);
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getListCount($search));
		$page->pageSize = 20;
		$this->render('list' , array(
			'class'				=> GlobalUsedClass::getTree(),
			'brand'				=> ClassLoad::Only('GoodsBrand')->getSelectList(),
			'shelfStatus'		=> $model->getShelfStatus(),
			'verifyStatus'		=> $model->getVerifyStatus(),
			'page'				=> $page,
			'list'				=> $model->getList($search,$page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}
	
	/**
	 * 关键字设置
	 */
	public function actionSeo()
	{
		$this->checkUserPurview('ug.seo');

		$form = ClassLoad::Only('SeoForm');/* @var $form SeoForm */
		$this->exitAjaxPost($form);

		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的商品ID');

		$model = ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
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
			'tree'		=> GlobalUsedClass::getTree(),
		));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('ug.ce');
		$form = ClassLoad::Only('UsedGoodsForm');/* @var $form UsedGoodsForm */
		$post = isset($_POST['UsedGoodsForm']) ? $_POST['UsedGoodsForm'] : array();

		$form->img		= isset($post['img']) ? $post['img'] : array();
		$this->exitAjaxPost($form , 'formWrap');

		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model = ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
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
			'class'			=> GlobalUsedClass::getTree(),
			'tag'			=> GlobalGoodsTag::getTagKV(1),
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview('ug.cl');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id');

		$model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
		$model->clear($id);
		$this->redirect(array('list'));
	}

	//详情
	public function actionIntro()
	{
		$this->checkUserPurview('ug.tr');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id');

		$model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
		$this->render('intro' , array(
			'intro'=>$model->intro($id)
		));
	}

	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('ug.my');

		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id');

		$model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
		if (!$intro = $model->intro($id))
			$this->error('找不到商品数据');

		$form=ClassLoad::Only('UsedGoodsForm');/* @var $form UsedGoodsForm */
		$post = isset($_POST['UsedGoodsForm']) ? $_POST['UsedGoodsForm'] : array();

		$form->img = isset($post['img']) ? $post['img'] : $intro['img'];
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
			'used'			=> $intro,
			'brand'			=> ClassLoad::Only('GoodsBrand')->getSelectList(),
			'class'			=> GlobalUsedClass::getTree(),
			'tag'			=> GlobalGoodsTag::getTagKV(1),
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	//审核
	public function actionAudi($id){
		$this->checkUserPurview('ug.au');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id');

		$status=(int)$this->getQuery('status');

		$model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
		//审核成功
		if($status==1){
			$arr=array('status_id'=>1013);
			$model->status($id,$arr);
			$this->redirect(array('intro','id'=>$id));
		}
		//审核失败
		if($status==2){
			$arr=array('status_id'=>1014);
			$model->status($id,$arr);
			$this->redirect(array('intro','id'=>$id));
		}
		$this->render('audi' , array(
			'intro'=>$model->intro($id)
		));
	}

	//批量操作
	public function actionBatch(){
		$this->checkUserPurview('ug.my');
		if($_POST){
			if(empty($_POST['id']))
				$this->error('错误的操作方式！');

			$model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
			$model->batch($_POST);
			$this->redirect(array('list'));
		}
	}
	//上下架
	public function actionShelf(){
		$this->checkUserPurview('ug.my');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的商品id');

		if(!$shelf=(int)$this->getQuery('shelf'))
			$this->error('错误的操作');

		if($shelf==1001){
			$arr=array(
				'shelf_id'  =>	$shelf,
				'shelf_time'=>	time(),
			);
		}
		if($shelf==1002){
			$arr=array(
				'shelf_id'  =>	$shelf,
			);
		}
		$model=ClassLoad::Only('UsedGoods');/* @var $model UsedGoods */
		$model->shelf($id,$arr);

		$this->redirect(array('intro','id' => $id));
	}
	/**
	 * 根据分类获取品牌
	 */
	public function actionGetBrand()
	{
		$one_id=$this->getQuery('one_id');
		$two_id=$this->getQuery('two_id');
		$three_id=$this->getQuery('three_id');

		$data=GlobalBrand::getBrand(2 , $one_id , $two_id , $three_id);
		$this->jsonOutput(0,$data);
	}
}