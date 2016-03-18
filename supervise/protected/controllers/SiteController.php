<?php
class SiteController extends SController
{
	//验证码
	public function actions()
	{
		return array
		(
			'captcha' => array
			(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
				'minLength' => 4,
				'maxLength' => 6,
				'height' => 40,
				'width' => 150,
			),
			'page' => array('class' => 'CViewAction')
		);
	}
	
	//更新缓存数据
	public function actionFlushCache()
	{
		CacheBase::setCache('memCache');
		CacheBase::flush();
		
		CacheBase::setCache('file');
		CacheBase::flush();
		
		$this->jsonOutput(0);
	}
	
	//登录后的首页
	public function actionHome()
	{
		$this->layout = false;
		$this->pageTitle = Yii::app()->name;
		
		$user = $this->getUser();
		$user = empty($user['true_name']) ? $user['account'] : $user['true_name'];
		$this->render('home' , array(
			'governorName' => $user,
		));
	}
	
	//登录到用户中心
	public function actionIndex()
	{
		$this->layout = false;
		
		//如果已登录,跳转到默认登录后页面
		if ($this->getUid())
			$this->redirect(array('site/home'));
		
		$form = ClassLoad::Only('SLoginForm');/* @var $form SLoginForm */
		$this->exitAjaxPost($form , 'login-form');
		
		if(isset($_POST['SLoginForm']))
		{
			$form->attributes = $_POST['SLoginForm'];
			if($form->validate() && $form->login(self::PURV))
				$this->redirect(array('site/home'));
		}
		
		$this->render('login' , array('form' => $form));
	}
	
	//错误处理模块
	public function actionError()
	{
		$app = Yii::app();
		if($error = $app->errorHandler->error)
		{
			if($app->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->renderPartial('error' , array('error'=>$error));
		}
	}
	
	//退出登录
	public function actionLogout()
	{
		Yii::app()->getUser()->logout();
		$this->redirect(array('site/index'));
	}
	
	//后台的导航栏
	public function getBackField()
	{
		$model = ClassLoad::Only('Governor');/* @var $model Governor */
		return $model->getBackField();
	}
}
