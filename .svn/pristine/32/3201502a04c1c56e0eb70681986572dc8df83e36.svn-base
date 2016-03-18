<?php
/**
 * 广告类型管理 控制器
 * @author jeson.Q
 */
class PromotionTypeController extends SController
{
	// 广告列表
	public function actionList()
	{
		// 设置访问权限
		$this->checkUserPurview('promotionType:c');
		$keyword = $this->getParam('keyword');
		$model = ClassLoad::Only('PromotionType'); /* @var $model PromotionType */
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$count = $model->getTotalNumber($keyword);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		// 查询列表并 渲染试图
		$promotionType = $model->getList($keyword , $offset , $page->pageSize);
		$this->render('list' , array(
			'promotionType' => $promotionType ,
			'page' => $page ,
			'keyword' => $keyword 
		));
	}
	
	// 编辑广告
	public function actionEdit()
	{
		// 设置访问权限
		$this->checkUserPurview('promotionType:c');
		// 加载类 、检查数据
		$form = ClassLoad::Only('PromotionTypeForm'); /* @var $form PromotionTypeForm */
		$this->exitAjaxPost($form);
		
		if (!$id = $this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('PromotionType'); /* @var $model PromotionType */
		if (!$info = $model->getActiveInfo($id))
			$this->error('栏位不存在!');
			// 是否提交数据
		if (isset($_POST['PromotionTypeForm']))
		{
			$form->attributes = $_POST['PromotionTypeForm'];
			if ($form->validate())
			{
				$model->modifys($_POST['PromotionTypeForm'] , $id);
				GlobalAdver::flush();
				$this->redirect(array(
					'promotionType/list' 
				));
			}
		}
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form ,
			'info' => $info 
		));
	}
	
	// 添加 广告
	public function actionCreate()
	{
		// 设置访问权限
		$this->checkUserPurview('promotionType:c');
		$form = ClassLoad::Only('PromotionTypeForm'); /* @var $model PromotionType */
		$this->exitAjaxPost($form);
		
		// 是否提交数据
		if (isset($_POST['PromotionTypeForm']))
		{
			$form->attributes = $_POST['PromotionTypeForm'];
			if ($form->validate())
			{
				$model = ClassLoad::Only('PromotionType'); /* @var $model PromotionType */
				$model->modifys($_POST['PromotionTypeForm'] , null);
				GlobalAdver::flush();
				$this->redirect(array(
					'promotionType/list' 
				));
			}
		}
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form 
		));
	}
}