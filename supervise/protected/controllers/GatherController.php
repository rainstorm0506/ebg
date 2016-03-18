<?php
/**
 * 电脑城
 *
 * @author  谭甜
 */
class GatherController extends SController
{
	//列表
	public function actionList(){
		header('content-type:text/html;charset=utf-8;');
		$this->checkUserPurview('cp.lt');
		$keyword= trim((string)$this->getQuery('keyword'));

		$search=array(
			'keyword'=>$keyword,
		);
		$model=ClassLoad::Only('Gather');/* @var $model Gather */
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getListCount($search));
		$page->pageSize = 20;

		$this->render('list' , array(
			'list' => $model->getList($search,$page->getOffset() , $page->getLimit() , $page->getItemCount()),
			'page' => $page
		));
	}
	//添加电脑城
	public function actionCreate(){
		$this->checkUserPurview('cp.ce');
		$form = ClassLoad::Only('GatherForm');/* @var $form GatherForm */
		$post = isset($_POST['GatherForm']) ? $_POST['GatherForm'] : array();
		$this->exitAjaxPost($form);

		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model->create($post);
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		$this->render('create' , array(
			'form'=>$form,
		));
	}
	//添加楼层
	public function actionStorey(){
		$this->checkUserPurview('cp.ce');
		$form=ClassLoad::Only('StoreyForm');/* @var $form StoreyForm */
		$post = isset($_POST['StoreyForm']) ? $_POST['StoreyForm'] : array();
		$this->exitAjaxPost($form);

		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model->storey($post);
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		$this->render('storey' , array(
			'form'			=>$form,
			'formError'		=>$formError,
			'computer'		=>$model->getComputer()
		));
	}
	//添加店铺
	public function actionStore(){
		$this->checkUserPurview('cp.ce');
		$form=ClassLoad::Only('StoreForm');/* @var $form StoreForm */
		$post = isset($_POST['StoreForm']) ? $_POST['StoreForm'] : array();
		$this->exitAjaxPost($form);

		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$formError = array();
		if($this->isPost() && $post)
		{
			$form->attributes = $post;
			if($form->validate())
			{
				$model->store($post);
				$this->redirect(array('list'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		$this->render('store' , array(
			'form'          =>$form,
			'computer'      =>$model->getComputer()
		));
	}
	//删除
	public function actionClear(){
		$this->checkUserPurview('cp.cl');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的店铺id');

		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$model->clear($id);
		$this->redirect(array('list'));
	}
	//修改
	public function actionModify(){
		header('content-type:text/html;charset=utf-8;');
		$this->checkUserPurview('cp.my');
		if(!$id=(int)$this->getQuery('id'))
			$this->error('错误的店铺id');

		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$intro=$model->intro($id);
		if($intro['parent_id']==0){
			$form = ClassLoad::Only('GatherForm');/* @var $form GatherForm */
			$post = isset($_POST['GatherForm']) ? $_POST['GatherForm'] : array();
			$this->exitAjaxPost($form);

			$formError = array();
			if($this->isPost() && $post)
			{
				$form->attributes = $post;
				if($form->validate())
				{
					$model->mygather($post);
					$this->redirect(array('list'));
				}else{
					$formError = $form->getErrors();
					foreach ($formError as &$v)
						$v = array_unique($v);
					$form->clearErrors();
				}
			}
			$this->render('create' , array(
				'form'=>$form,
				'info'=>$model->intro($id)
			));
		}
		elseif($intro['parent_id']!=0&&$intro['title']){
			$form = ClassLoad::Only('StoreForm');/* @var $form StoreForm */
			$post = isset($_POST['StoreForm']) ? $_POST['StoreForm'] : array();
			$this->exitAjaxPost($form);

			$formError = array();
			if($this->isPost() && $post)
			{
				$form->attributes = $post;
				if($form->validate())
				{
					$model->mystore($post);
					$this->redirect(array('list'));
				}else{
					$formError = $form->getErrors();
					foreach ($formError as &$v)
						$v = array_unique($v);
					$form->clearErrors();
				}
			}
			$intro=$model->intro($id);
			$this->render('store' , array(
				'form'=>$form,
				'computer'=>$model->getComputer(),
				'storey'=>$model->getstorey($intro['parent_id']),
				'info'=>$model->intro($id)
			));
		}
		else{
			$form = ClassLoad::Only('StoreyForm');/* @var $form StoreyForm */
			$post = isset($_POST['StoreyForm']) ? $_POST['StoreyForm'] : array();
			$this->exitAjaxPost($form);

			$formError = array();
			if($this->isPost() && $post)
			{
				$form->attributes = $post;
				if($form->validate())
				{
					$model->mystorey($post);
					$this->redirect(array('list'));
				}else{
					$formError = $form->getErrors();
					foreach ($formError as &$v)
						$v = array_unique($v);
					$form->clearErrors();
				}
			}
			$intro=$model->intro($id);
			$this->render('storey' , array(
				'form'=>$form,
				'computer'=>$model->getComputer(),
				'info'=>$intro
			));
		}
	}
	//获得楼层
	public function actionGetStorey(){
		if(!$id=(int)$this->getQuery('gather'))
			$this->error('错误的电脑城id');

		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$this->jsonOutput(0,$model->getstorey($id));
	}
	//获得楼层对应店铺编号
	public function actionGetstore(){
		if(!$gather=(int)$this->getQuery('gather'))
			$this->error('错误的电脑城id');

		if(!$storey=(int)$this->getQuery('storey'))
			$this->error('错误的楼层id');

		$model = ClassLoad::Only('Gather');/* @var $model Gather */
		$this->jsonOutput(0,$model->getstore($gather,$storey));
	}
	public function actionGetUnidList()
	{
		$oneId = (int)$this->getQuery('gather');
		$twoId = (int)$this->getQuery('storey');
		$threeId = (int)$this->getQuery('store');

		$this->jsonOutput(0 , GlobalGather::getGatherTree(true ,$oneId));
	}
}