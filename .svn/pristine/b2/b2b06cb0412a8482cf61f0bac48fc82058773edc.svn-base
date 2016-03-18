<?php
/**
 * 导航栏位
 * @author simon
 */
class NavFieldController extends SController
{
	private $cacheName = 'backField';
	
	//列表
	public function actionList()
	{
		$this->checkUserPurview('nf.lt');
		
		$model = ClassLoad::Only('NavField');/* @var $model NavField */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getListCount());
		$page->pageSize = 50;
		
		$this->render('list' , array(
			'page' => $page,
			'list' => $model->getList($page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('nf.ce');
		
		$form = ClassLoad::Only('NavFieldForm');/* @var $form NavFieldForm */
		$this->exitAjaxPost($form);
	
		$model = ClassLoad::Only('NavField');/* @var $model NavField */
		if(isset($_POST['NavFieldForm']))
		{
			$form->attributes = $_POST['NavFieldForm'];
			if($form->validate())
			{
				$model->create($_POST['NavFieldForm']);
				$this->clearCache($this->cacheName);
				$this->redirect(array('navField/list'));
			}
		}
		
		$this->render('append' , array(
			'form'		=> $form,
			'parent'	=> $model->getRootField(),
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('nf.my');
		
		$form = ClassLoad::Only('NavFieldForm');/* @var $form NavFieldForm */
		$this->exitAjaxPost($form);
	
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
	
		$model = ClassLoad::Only('NavField');/* @var $model NavField */
		if (!$info = $model->getFieldInfo($id))
			$this->error('栏位不存在!');
		
		if(isset($_POST['NavFieldForm']))
		{
			$form->attributes = $_POST['NavFieldForm'];
			if($form->validate())
			{
				$model->modify($_POST['NavFieldForm'] , $id);
				$this->clearCache($this->cacheName);
				$this->redirect(array('navField/list'));
			}
		}
		
		$this->render('append' , array(
			'form'		=> $form,
			'parent'	=> $model->getRootField(),
			'info'		=> $info
		));
	}
	
	//删除
	public function actionClear()
	{
		$this->checkUserPurview('nf.cl');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
	
		$model = ClassLoad::Only('NavField');/* @var $model NavField */
		$model->clear($id);
		$this->clearCache($this->cacheName);
		$this->redirect(array('navField/list'));
	}
	
	//编辑权限
	public function actionPrivilege()
	{
		$this->checkUserPurview('nf.pe');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('NavField');/* @var $model NavField */
		if (!$info = $model->getFieldInfo($id))
			$this->error('栏位不存在!');
		
		if(!empty($_POST['group']))
		{
			if ($model->privilege($_POST['group'] , $id))
				$this->redirect(array('navField/list'));
			else
				$this->error('操作失败!');
		}
		
		$this->render('privilege' , array(
			'group'	=> $model->getFieldPurview($id),
			'info'	=> $info
		));
	}
}