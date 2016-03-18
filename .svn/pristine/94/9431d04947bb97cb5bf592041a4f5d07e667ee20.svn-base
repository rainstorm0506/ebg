<?php
/**
 * 经营范围控制器
 */
class ScopeController extends SController {
	
	function actionList() {
		$this->checkUserPurview("scp.l");
		
		$model=ClassLoad::Only("Scope");
		$keyword=$this->getQuery("keyword");
		
		$page = ClassLoad::Only('CPagination');/* @var $page CPagination */
		$page->pageVar = 'p';
		$page->setItemCount($model->getCount($keyword));
		$page->pageSize = 50;
		
		$this->render("list",array(
			'list'	=>	$model->getList($keyword, $page->getOffset(), $page->getLimit(), $page->getItemCount())
		));
	}
	
	public function actionCreate()
	{
		$this->checkUserPurview("scp.c");
		
		$form=ClassLoad::Only('ScopeForm');/* @var $form ScopeForm */
		$this->exitAjaxPost($form);
		if(isset($_POST['ScopeForm'])) {
			$form->attributes=$_POST['ScopeForm'];

			if($form->validate()) {
				$model=ClassLoad::Only('Scope');/* @var $model User */
				$model->create($_POST['ScopeForm']);
				$this->redirect(array('scope/list'));
			}
		}
		$this->render("append",array(
			'form'	=>	$form
		));
	}
	
	public function actionModify()
	{
		$this->checkUserPurview("scp.m");
		
		$form=ClassLoad::Only('ScopeForm');/* @var $form ScopeForm */
		$this->exitAjaxPost($form);
		$id=(int)$this->getQuery('id');
		if(!$id)
			$this->error('错误的ID');
		
		$model=ClassLoad::Only("Scope");
		$form->attributes=isset($_POST['ScopeForm'])?$_POST['ScopeForm']:array();
			if(isset($_POST['ScopeForm']) && $form->validate())
			{
				$model->modify($_POST['ScopeForm'], $id);
				$this->redirect(array('scope/list'));
			}
		$this->render("append", array(
			'form'	=>	$form,
			'info'	=>	$model->getInfo($id)
		));
	}
	
	/**
	 * 删除经营范围信息
	 */
	public function actionDelete()
	{
		$this->checkUserPurview("scp.d");
		$id=$this->getQuery("id");
		
		$model=ClassLoad::Only("Scope");
		$model->clear($id);
		$this->redirect("scope/list");
	}
}

?>