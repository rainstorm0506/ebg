<?php
class ActFreeController extends SController
{
	/**
	 * 列表
	 */
	public function actionList()
	{
		$this->checkUserPurview('af.lt');
		$model = ClassLoad::Only('ActFree');/* @var $model ActFree */
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getGoodsCount());
		$page->pageSize = 20;
		$this->render('list' , array(
			'page'			=> $page,
			'list'			=> $model->getList($page->getOffset() , $page->getLimit() , $page->getItemCount())
		));
	}
	/**
	 * 添加活动0元购
	 */
	public function actionCreate()
	{
		$this->checkUserPurview('af.ce');
		$form = ClassLoad::Only('ActFreeForm');/* @var $form ActFreeForm */
		$post = isset($_POST['ActFreeForm']) ? $_POST['ActFreeForm'] : array();
		$this->exitAjaxPost($form , 'formWrap');
		$formError=array();

		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model = ClassLoad::Only('ActFree');/* @var $model ActFree */
				$model->create($post);
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);

				$form->clearErrors();
			}
		}
		$usertype=GlobalUser::getLayerList(1);
		$companytype=GlobalUser::getLayerList(2);
		$usertp=array();
		$companytp=array();
		foreach($usertype as $key=>$val)
		{
			$usertp[$val['id']] = $val['name'];
		}
		foreach($companytype as $key=>$val)
		{
			$companytp[$val['id']] = $val['name'];
		}
		$this->render('create' , array(
			'formError'		=> $formError,
			'form'			=> $form,
			'usertype'		=> $usertp,
			'companytype'	=> $companytp
		));
	}
	/**
	 * 详情
	 */
	public function actionInfo()
	{
		$this->checkUserPurview('af.lt');
		if(!$id=$this->getQuery('id'))
			$this->error('错误的操作!');

		$model = ClassLoad::Only('ActFree');/* @var $model ActFree */
		if (!$info = $model->getInfo($id))
			$this->error('零元购数据不存在!');

		$this->render('info' , array(
			'info'		=> $info
		));
	}
	/**
	 * 删除
	 */
	public function actionClear()
	{
		$this->checkUserPurview('af.cl');
		if(!$id=$this->getQuery('id'))
			$this->error('错误的操作!');

		$model = ClassLoad::Only('ActFree');/* @var $model ActFree */
		if (!$info = $model->getInfo($id))
			$this->error('零元购数据不存在!');

		$model->clear($id);
		$this->redirect(array('list'));
	}
	/**
	 * 修改
	 */
	public function actionModify()
	{
		$this->checkUserPurview('af.my');
		if(!$id=$this->getQuery('id'))
			$this->error('错误的操作!');

		$model = ClassLoad::Only('ActFree');/* @var $model ActFree */
		if (!$info = $model->getInfo($id))
			$this->error('零元购数据不存在!');

		$form = ClassLoad::Only('ActFreeForm');/* @var $form ActFreeForm */
		$post = isset($_POST['ActFreeForm']) ? $_POST['ActFreeForm'] : array();
		$this->exitAjaxPost($form , 'formWrap');
		$formError=array();

		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model = ClassLoad::Only('ActFree');/* @var $model ActFree */
				$model->modify($post);
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);

				$form->clearErrors();
			}
		}
		$usertype=GlobalUser::getLayerList(1);
		$companytype=GlobalUser::getLayerList(2);
		$usertp=array();
		$companytp=array();
		foreach($usertype as $key=>$val)
		{
			$usertp[$val['id']] = $val['name'];
		}

		foreach($companytype as $key=>$val)
		{
			$companytp[$val['id']] = $val['name'];
		}

		$this->render('modify' , array(
			'formError'		=> $formError,
			'form'			=> $form,
			'info'			=> $info,
			'usertype'		=> $usertp,
			'companytype'	=> $companytp
		));
	}
}