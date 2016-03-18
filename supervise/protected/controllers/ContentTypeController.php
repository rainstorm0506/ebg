<?php
/**
 * 文章类型管理 控制器
 * @author jeson.Q
 */
class ContentTypeController extends SController
{
	// 文章类型列表
	public function actionList()
	{
		// 设置访问权限
		$this->checkUserPurview('contentType:l');
		$keyword = $this->getParam('keyword');
		// 设置分页
		$pageNow = $this->getParam('pagenum' , 0);
		$model = ClassLoad::Only('ContentType'); /* @var $model ContentType */
		$count = $model->getTotalNumber($keyword);
		$page = new CPagination();
		$page->pageVar = 'pagenum';
		$page->pageSize = 20;
		$page->setItemCount($count);
		$offset = $pageNow >1 ? ($pageNow -1) *$page->pageSize : 0;
		
		// 查询列表并 渲染试图
		$contentType = $model->getList($keyword , $offset , $page->pageSize);
		$this->render('list' , array(
			'contentType' => $contentType ,
			'page' => $page ,
			'keyword' => $keyword 
		));
	}
	
	// 添加 文章类型
	public function actionCreate()
	{
		// 设置访问权限
		$this->checkUserPurview('contentType:c');
		$form = ClassLoad::Only('ContentTypeForm'); /* @var $model ContentTypeForm */
		$this->exitAjaxPost($form);
		
		if (isset($_POST['ContentTypeForm']))
		{
			$form->attributes = $_POST['ContentTypeForm'];
			if ($form->validate())
			{
				$model = ClassLoad::Only('ContentType'); /* @var $model ContentType */
				$model->modifys($_POST['ContentTypeForm'] , null);
				GlobalContent::flush();
				$this->redirect(array(
					'contentType/list' 
				));
			}
		}
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form 
		));
	}
	
	// 编辑文章类型
	public function actionEdit()
	{
		// 设置访问权限
		$this->checkUserPurview('contentType:e');
		// 加载类 、检查数据
		$form = ClassLoad::Only('ContentTypeForm'); /* @var $form ContentTypeForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('ContentType'); /* @var $model ContentType */
		if (!$info = $model->getActiveInfo($id))
			$this->error('栏位不存在!');
		
		if (isset($_POST['ContentTypeForm']))
		{
			$form->attributes = $_POST['ContentTypeForm'];
			if ($form->validate())
			{
				$model->modifys($_POST['ContentTypeForm'] , $id);
				GlobalContent::flush();
				$this->redirect(array(
					'contentType/list' 
				));
			}
		}
		// 渲染试图
		$this->render('edit' , array(
			'form' => $form ,
			'info' => $info 
		));
	}
	
	// 删除文章类型
	public function actionClear()
	{
		// 设置访问权限
		$this->checkUserPurview('contentType:d');
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
			// 加载 ContentType 模型
		$model = ClassLoad::Only('ContentType'); /* @var $model ContentType */
		$model->clear($id);
		GlobalContent::flush();
		$this->redirect(array(
			'contentType/list' 
		));
	}
}