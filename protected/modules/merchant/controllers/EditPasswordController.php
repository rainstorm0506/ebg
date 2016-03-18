<?php
/**
 * 商家中心--登录密码管理 - 控制器
 * 
 * @author simon
 */
class EditPasswordController extends MerchantController
{
	//修改密码页操作
	public function actionIndex()
	{
		$this->leftNavType = 'store';
		$model = ClassLoad::Only('User'); /* @var $model User */
		$form = ClassLoad::Only('EditPasswordForm'); /* @var $form EditPasswordForm */
		$form->personType = 'modVerify';
		$id = (int)$this->getMerchantID();
		$this->exitAjaxPost($form,'formBox');
		$personData = $model->getPersonInfo($id);
		if ($this->isPost() && isset ( $_POST['EditPasswordForm'] )){
			$form->attributes = $_POST['EditPasswordForm'];
			if ($form->validate()) {
				$this->redirect(array('EditPassword/editPassword'));
			}
		}
		// 查询列表并 渲染试图
		$this->render('modVerify' , array(
			'form'			=>	$form,
			'personData'	=>	$personData
		));
	}

	//修改密码页操作
	public function actionEditPassword()
	{
		$this->leftNavType = 'store';
		$model = ClassLoad::Only('User'); /* @var $model User */
		$form = ClassLoad::Only('EditPasswordForm'); /* @var $form EditPasswordForm */
		$form->personType = 'editPassword';
		$id = (int)$this->getMerchantID();

		if ($this->isPost() && isset ( $_POST['EditPasswordForm'])){
			$form->attributes = $_POST['EditPasswordForm'];
			if ($form->validate()) {
				$model->setUserPassword ( $_POST['EditPasswordForm'], $id);
				$this->redirect(array('EditPassword/modComplete'));
			}
		}
		// 查询列表并 渲染试图
		$this->render('modLoginPassword' , array(
			'form'=>$form
		));
	}

	//修改密码完成页操作
	public function actionModComplete()
	{
	
		$this->leftNavType = 'store';
		$model = ClassLoad::Only('User'); /* @var $model User */
		$form = ClassLoad::Only('EditPasswordForm'); /* @var $form EditPasswordForm */
		$id = (int)$this->getMerchantID();
		
		// 查询列表并 渲染试图
		$this->render('modComplete' , array(
			'form'=>$form
		));
	}

}