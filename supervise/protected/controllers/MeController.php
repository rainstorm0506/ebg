<?php
/**
 * 我的数据
 * @author simon
 */
class MeController extends SController
{
	//我的信息
	public function actionInfo()
	{
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		$branch = ClassLoad::Only('Branch');/* @var $branch Branch */
		$this->render('info' , array(
			'des'		=> $model->getGovernorInfo($this->getUid()),
			'branch'	=> $branch->getListPair(),
		));
	}

	public function actionHome()
	{
		$this->render('home');
	}
	
	//修改我的密码
	public function actionPassword()
	{
		$form = ClassLoad::Only('GovernorForm');/* @var $model GovernorForm */
		$this->exitAjaxPost($form);
		
		$uid = (int)Yii::app()->getUser()->getId();
		$user = Yii::app()->getUser()->getName();
		
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		
		$form->attributes = isset($_POST['GovernorForm']) ? $_POST['GovernorForm'] : array();
		if(isset($_POST['GovernorForm']) && $form->validate())
		{
			$model->editToPassword($_POST['GovernorForm'] , $uid);
			
			#更新session中的password
			$identity = new GovernorIdentity($user['account'] , '');
			$identity->authenticate(false);
			if($identity->errorCode === GovernorIdentity::ERROR_NONE)
				Yii::app()->getUser()->login($identity , 0);
			
			$this->redirect(array('me/info'));
		}
		
		$branch = ClassLoad::Only('Branch');/* @var $branch Branch */
		$this->render('password' , array(
			'model'		=> $form,
			'des'		=> $model->getGovernorInfo($uid),
			'branch'	=> $branch->getListPair(),
		));
	}
}