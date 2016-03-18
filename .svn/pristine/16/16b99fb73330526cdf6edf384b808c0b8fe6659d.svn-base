<?php
/**
 * 广告管理 控制器
 * @author jeson.Q
 */
class PromotionController extends SController
{
	// 广告列表
	public function actionList()
	{
		// 设置访问权限
		$this->checkUserPurview('promotion:l');
		$keyword = $this->getParam('keyword');
		$model = ClassLoad::Only('Promotion'); /* @var $model Promotion */
		
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($keyword);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		// 查询列表并 渲染试图
		$promotion = $model->getList($keyword , $offset , $page->pageSize);
		$this->render('list' , array(
			'promotion' => $promotion ,
			'page' => $page ,
			'keyword' => $keyword 
		));
	}
	
	// 编辑广告
	public function actionEdit()
	{
		// 设置访问权限
		$this->checkUserPurview('promotion:e');
		// 加载类 、检查数据
		$form = ClassLoad::Only('PromotionForm'); /* @var $form PromotionForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Promotion'); /* @var $model Promotion */
		if (!$info = $model->getActiveInfo($id))
			$this->error('栏位不存在!');
		// 是否提交数据
		if (isset($_POST['PromotionForm']))
		{
			$promotionPostData = $_POST['PromotionForm'];
			$form->attributes = $promotionPostData;
			if ($form->validate())
			{
				$model->modifys($promotionPostData , $id);
				GlobalAdver::flush();
				$this->redirect(array(
					'promotion/list' 
				));
			}
		}
		//查询商品分类ID
		$selectAllData = $model->getClassName();
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form ,
			'info' => $info ,
			'selectAllData' => $selectAllData
		));
	}
	
	// 添加 广告
	public function actionCreate()
	{
		// 设置访问权限
		$this->checkUserPurview('promotion:c');
		$form = ClassLoad::Only('PromotionForm'); /* @var $model Promotion */
		$this->exitAjaxPost($form);
		$model = ClassLoad::Only('Promotion'); /* @var $model Governor */
		// 是否提交数据
		if (isset($_POST['PromotionForm']))
		{
			$form->attributes = $_POST['PromotionForm'];
			if ($form->validate())
			{
				$model->modifys($_POST['PromotionForm'] , null);
				GlobalAdver::flush();
				$this->redirect(array(
					'promotion/list' 
				));
			}
		}
		//查询商品分类ID
		$selectAllData = $model->getClassName();
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form,
			'selectAllData' => $selectAllData
		));
	}
	
	// 删除 广告
	public function actionClear()
	{
		// 设置访问权限
		$this->checkUserPurview('promotion:d');
		// 检查ID是否正确
		if (!$id = (int)$this->getQuery('id' , 0))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Promotion'); /* @var $model Promotion */
		$model->clear($id);
		GlobalAdver::flush();
		$this->redirect(array(
			'promotion/list',
		));
	}
	// ajax查询图片宽度和高度限制
	public function actionGetImageSize()
	{
		if (!$id = $_POST['id'])
		{
			echo 0;die('');
		}
		$model = ClassLoad::Only('PromotionType'); /* @var $model PromotionType */
		$typeInfo = $model->getActiveInfo($id);
		
		echo $typeInfo['width'].'-'.$typeInfo['height'];
	}
}