<?php

/**
 * 产品品牌
 *
 * @author：涂先锋
 */
class GoodsBrandController extends SController
{
	//品牌列表
	public function actionList()
	{
		$this->checkUserPurview('gbd.l');

		$search = array(
			'goods_one_id'		=> (int)$this->getQuery('goods_class_one'),
			'goods_two_id'		=> (int)$this->getQuery('goods_class_two'),
			'goods_three_id'	=> (int)$this->getQuery('goods_class_three'),
			'used_one_id'		=> (int)$this->getQuery('used_class_one'),
			'used_two_id'		=> (int)$this->getQuery('used_class_two'),
			'used_three_id'		=> (int)$this->getQuery('used_class_three'),
			'keyword'           => trim((string)$this->getQuery('keyword'))
		);
		$model = ClassLoad::Only('GoodsBrand');/* @var $model GoodsBrand */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getBrandCount($search));
		$page->pageSize = 50;
		
		$this->render('list' , array(
			'goodsClass'	=> GlobalGoodsClass::getTree(),
			'usedClass'		=>	GlobalUsedClass::getTree(),
			'keyword'		=> $search['keyword'],
			'page'			=> $page,
			'list'			=> $model->getBrandList($search , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}

	//新建
	public function actionCreate()
	{
		$this->checkUserPurview('gbd.c');
		
		$form = ClassLoad::Only('GoodsBrandForm');/* @var $form GoodsBrandForm */
		$formError=array();
		$form->logo = empty($_POST['GoodsBrandForm']['logo']) ? '' : $_POST['GoodsBrandForm']['logo'];
		$this->exitAjaxPost($form);
		
		$model = ClassLoad::Only('GoodsBrand');/* @var $model GoodsBrand */
		if(isset($_POST['GoodsBrandForm']))
		{
			$form->attributes = $_POST['GoodsBrandForm'];

			if($form->validate())
			{
				$model->create($_POST['GoodsBrandForm']);
				GlobalBrand::flush();
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}

		$goods_class=GlobalGoodsClass::getTree();
		$used_class=GlobalUsedClass::getTree();
		$this->render('append' , array(
			'form' 			=>	$form,
			'goods_class'	=>	$goods_class,
			'used_class'	=>	$used_class,
			'formError'		=>	$formError
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('gbd.m');
	
		$form = ClassLoad::Only('GoodsBrandForm');/* @var $form GoodsBrandForm */
		$formError=array();
		$form->logo = empty($_POST['GoodsBrandForm']['logo']) ? '' : $_POST['GoodsBrandForm']['logo'];
		$this->exitAjaxPost($form);
	
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
	
		$model = ClassLoad::Only('GoodsBrand');/* @var $model GoodsBrand */
		if (!$info = $model->getBrandInfo($id))
			$this->error('品牌数据不存在!');
	
		if(isset($_POST['GoodsBrandForm']))
		{
			$form->attributes = $_POST['GoodsBrandForm'];
			if($form->validate())
			{
				$model->modify($_POST['GoodsBrandForm'] , $id);
				GlobalBrand::flush();
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}

		$goods_class=GlobalGoodsClass::getTree();
		$used_class=GlobalUsedClass::getTree();

		$this->render('append' , array(
			'form' => $form,
			'info' => $info,
			'goods_class'	=>	$goods_class,
			'used_class'	=>	$used_class,
			'formError'		=>	$formError
		));
	}
	
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview('gbd.d');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('GoodsBrand');/* @var $model GoodsBrand */
		$model->deletes($id);
		GlobalBrand::flush();
		$this->redirect(array('list'));
	}
}