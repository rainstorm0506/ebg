<?php
/**
 * 管理员 控制器
 * @author simon
 */
class GovernorController extends SController
{
	//列表
	public function actionList()
	{
		$this->checkUserPurview('gv.lt');
		$keyword = (string)$this->getQuery('keyword');
		
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getGoverCount($keyword));
		$page->pageSize = 50;
		
		$this->render('list' , array(
			'keyword'	=> $keyword,
			'page'		=> $page,
			'list'		=> $model->getGoverList($keyword , $page->getOffset() , $page->getLimit() , $page->getItemCount()),
		));
	}
	
	//添加
	public function actionCreate()
	{
		$this->checkUserPurview('gv.ce');
		
		$form = ClassLoad::Only('GovernorForm');/* @var $form GovernorForm */
		$this->exitAjaxPost($form);
		
		if(isset($_POST['GovernorForm']))
		{
			$form->attributes = $_POST['GovernorForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('Governor');/* @var $model Governor */
				$model->create($_POST['GovernorForm']);
				$this->redirect(array('governor/list'));
			}
		}
		
		$purview = ClassLoad::Only('PurviewGroup');/* @var $purview PurviewGroup */
		$branch = ClassLoad::Only('Branch');/* @var $branch Branch */
		$this->render('append' , array(
			'purviewGroup'	=> $purview->getList(),
			'branch'		=> $branch->getListPair(),
			'form'			=> $form
		));
	}
	
	//编辑
	public function actionModify()
	{
		$this->checkUserPurview('gv.my');
		
		$form = ClassLoad::Only('GovernorForm');/* @var $form GovernorForm */
		$this->exitAjaxPost($form);
		
		if (!$id = (int)$this->getQuery('id' , 0))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		$userSession = Yii::app()->getUser()->getName();
		if (!($des = $model->getGovernorInfo($id)) || $des['account'] == $userSession['account'])
			$this->message('非法操作');
		
		$form->attributes = isset($_POST['GovernorForm']) ? $_POST['GovernorForm'] : array();
		if(isset($_POST['GovernorForm']) && $form->validate())
		{
			$model->modify($_POST['GovernorForm'] , $id);
			$this->redirect(array('governor/list'));
		}
		
		$purview = ClassLoad::Only('PurviewGroup');/* @var $purview PurviewGroup */
		$branch = ClassLoad::Only('Branch');/* @var $branch Branch */
		$this->render('append' , array(
			'purviewGroup'	=> $purview->getList(),
			'branch'		=> $branch->getListPair(),
			'form'			=> $form,
			'des'			=> $des
		));
	}
	
	//删除
	public function actionClear()
	{
		$this->checkUserPurview('gv.cl');
		
		if (!$id = (int)$this->getQuery('id'))
			$this->error('错误的ID');
		
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		$model->clear($id);
		$this->redirect(array('governor/list'));
	}
}