<?php
/**
 * 部门 控制器
 * @author simon
 */
class BranchController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('gb.l');
		
		$model = ClassLoad::Only('Branch');/* @var $model Branch */
		$this->render('list' , array(
			'list' => $model->getList(),
		));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('gb.c');
		
		$form = ClassLoad::Only('BranchForm');/* @var $form BranchForm */
		$this->exitAjaxPost($form);
		
		if(isset($_POST['BranchForm']))
		{
			$form->attributes = $_POST['BranchForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('Branch');/* @var $model Branch */
				$model->create($_POST['BranchForm']);
				$this->redirect(array('list'));
			}
		}
		
		$this->render('append' , array(
			'form' => $form
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('gb.m');
		
		$form = ClassLoad::Only('BranchForm');/* @var $form BranchForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Branch');/* @var $model Branch */
		if (!$info = $model->getInfo($id))
			$this->error('你编辑的信息不存在!');
		
		$form->attributes = isset($_POST['BranchForm']) ? $_POST['BranchForm'] : array();
		if(isset($_POST['BranchForm']) && $form->validate())
		{
			$model->modify($_POST['BranchForm'] , $id);
			$this->redirect(array('list'));
		}
		
		$this->render('append' , array(
			'form' => $form,
			'info' => $info
		));
	}
	
	//删除
	public function actionClear()
	{
		$this->checkUserPurview('gb.d');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Branch');/* @var $model Branch */
		$model->clear($id);
		$this->redirect(array('list'));
	}
}