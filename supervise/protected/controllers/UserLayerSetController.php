<?php
/**
 * 会员等级设定 控制器
 * @author simon
 */
class UserLayerSetController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('uls.l');
		$type = (int)$this->getQuery('type');
		
		$model = ClassLoad::Only('UserLayerSet');/* @var $model UserLayerSet */
		$this->render('list' , array(
			'list' => $model->getList($type),
		));
	}

	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('uls.c');
	
		$form = ClassLoad::Only('UserLayerSetForm');/* @var $form UserLayerSetForm */
		$this->exitAjaxPost($form);
	
		$model = ClassLoad::Only('UserLayerSet');/* @var $model UserLayerSet */
		if(isset($_POST['UserLayerSetForm']))
		{
			$form->attributes = $_POST['UserLayerSetForm'];
			if($form->validate())
			{
				if ($model->create($_POST['UserLayerSetForm']))
					GlobalUser::getLayerList(0,true);
				
				$this->redirect(array('userLayerSet/list'));
			}
		}
	
		$this->render('append' , array(
			'form' => $form
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('uls.m');
		
		$form = ClassLoad::Only('UserLayerSetForm');/* @var $form UserLayerSetForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('UserLayerSet');/* @var $model UserLayerSet */
		if (!$info = $model->getInfo($id))
			$this->error('你编辑的信息不存在!');
		
		$form->attributes = isset($_POST['UserLayerSetForm']) ? $_POST['UserLayerSetForm'] : array();
		if(isset($_POST['UserLayerSetForm']) && $form->validate())
		{
			if ($model->modify($_POST['UserLayerSetForm'] , $id))
				GlobalUser::getLayerList(0,true);
			$this->redirect(array('userLayerSet/list'));
		}
		
		$this->render('append' , array(
			'form' => $form,
			'info' => $info
		));
	}
	
	//删除
	public function actionDelete()
	{
		$this->checkUserPurview('uls.d');
	
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
	
		$model = ClassLoad::Only('UserLayerSet');/* @var $model UserLayerSet */
		if ($model->deletes($id))
			GlobalUser::getLayerList(0,true);
		
		$this->redirect(array('userLayerSet/list'));
	}
}