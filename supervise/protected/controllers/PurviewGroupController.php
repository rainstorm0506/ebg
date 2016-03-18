<?php
/**
 * 角色设置
 * @author simon
 */
class PurviewGroupController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('pg.lt');
		
		$model = ClassLoad::Only('PurviewGroup');/* @var $model PurviewGroup */
		
		$this->render('list' , array(
			'list' => $model->getList()
		));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('pg.ce');
		
		$form = ClassLoad::Only('PurviewGroupForm');/* @var $form PurviewGroupForm */
		$this->exitAjaxPost($form);
	
		$model = ClassLoad::Only('PurviewGroup');/* @var $model PurviewGroup */
		if(isset($_POST['PurviewGroupForm']))
		{
			$form->attributes = $_POST['PurviewGroupForm'];
			if($form->validate())
			{
				$model->create($_POST['PurviewGroupForm']);
				$this->redirect(array('purviewGroup/list'));
			}
		}
		
		$this->render('append' , array(
			'form'		=> $form,
			'purviews'	=> $model->getAllPurview()
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('pg.my');
		
		$form = ClassLoad::Only('PurviewGroupForm');/* @var $form PurviewGroupForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('PurviewGroup');/* @var $model PurviewGroup */
		if (!$info = $model->getPurviewGroup($id))
			$this->error('数据不存在!');
		
		if(isset($_POST['PurviewGroupForm']))
		{
			$form->attributes = $_POST['PurviewGroupForm'];
			if($form->validate())
			{
				$model->modify($_POST['PurviewGroupForm'] , $id);
				$this->redirect(array('purviewGroup/list'));
			}
		}
		
		$this->render('append' , array(
			'form'		=> $form,
			'purviews'	=> $model->getAllPurview(),
			'info'		=> $info
		));
	}
	
	//查看
	public function actionShow()
	{
		$this->checkUserPurview('pg.sw');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
	
		$model = ClassLoad::Only('PurviewGroup');/* @var $model PurviewGroup */
		if (!$info = $model->getPurviewGroup($id))
			$this->error('数据不存在!');
		
		$this->render('show' , array(
			'purviews'	=> $model->getAllPurview(),
			'info'		=> $info
		));
	}
	
	//删除
	public function actionClear()
	{
		$this->checkUserPurview('pg.cl');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
	
		$model = ClassLoad::Only('PurviewGroup');/* @var $model PurviewGroup */
		$model->clear($id);
		$this->redirect(array('purviewGroup/list'));
	}
	
	//后台的导航栏
	public function getBackField()
	{
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		return $model->getBackField();
	}
}