<?php
/**
 * 默认控制器 - 控制器
 * 
 * @author simon
 */
class HomeController extends WebController
{
	public function init()
	{
		$this->layout = 'default';
	}
	
	public function actionFindPassword()
	{
		$form = ClassLoad::Only('FindPassForm');/* @var $form FindPassForm */
		$form->userType = $this->userType;
		$form->step = 'one';
		$this->exitAjaxPost($form);
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('findPassword', 0));
		
		$formError = array();
		if($this->isPost() && isset($_POST['FindPassForm']))
		{
			$form->attributes = $_POST['FindPassForm'];
			if($form->validate())
			{
				$session = Yii::app()->session;
				$session['findPassword'] = $_POST['FindPassForm'];
				$this->redirect(array('home/findPassTwo'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('fpOne' , array(
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	public function actionFindPassTwo()
	{
		$form = ClassLoad::Only('FindPassForm');/* @var $form FindPassForm */
		$form->userType = $this->userType;
		$form->step = 'two';
		$this->exitAjaxPost($form);
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('findPassword', 0));
		$formError = array();
		if($this->isPost() && isset($_POST['FindPassForm']))
		{
			$session = Yii::app()->session;
			$form->attributes = $_POST['FindPassForm'];
			if($form->validate() && !empty($session['findPassword']))
			{
				$post = array_merge($session['findPassword'] , $_POST['FindPassForm']);
				$model = ClassLoad::Only('Home');/* @var $model Home */
				
				if ($info = $model->getUserInfo($post['phone'] , $post['ut'] === 'merchant'))
				{
					//如果当前密码等于原来的密码 直接通过.
					if ($info['password'] == GlobalUser::hashPassword($post['password']))
					{
						unset($session['findPassword']);
						$this->redirect(array('home/findSucceed'));
					}else{
						if ($model->findPassword($post , $this->userType))
						{
							unset($session['findPassword']);
							$this->redirect(array('home/findSucceed'));
						}else{
							$formError['fp'][-1] = '写入数据失败!';
						}
					}
				}else{
					$formError['fp'][-1] = '找不到注册的数据!';
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('fpTwo' , array(
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	public function actionFindSucceed()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('findPassword', 0));
		
		$this->render('findSucceed');
	}
	
	//首页
	public function actionIndex()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('default', 0));
		$this->render('index' , array(
			'banner'	=> GlobalAdver::getAdverByCode('default_banner'),
		));
	}
	
	//商家注册
	public function actionMerSign()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('merSign', 0));
		
		$form = ClassLoad::Only('SignForm');/* @var $form SignForm */
		$form->userType = $this->userType;
		$form->signType = 'merchant';
		$this->exitAjaxPost($form);
		
		$formError = array();
		if($this->isPost() && isset($_POST['SignForm']))
		{
			$form->attributes = $_POST['SignForm'];
			#print_r($_POST);exit;
			if($form->validate())
			{
				$session = Yii::app()->session;
				$session['merchant'] = $_POST['SignForm'];
				$this->redirect(array('home/merSignNext'));
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('merSign' , array(
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	public function actionMerSignNext()
	{
		$form = ClassLoad::Only('MerSignNextForm');/* @var $form MerSignNextForm */
		$this->exitAjaxPost($form);
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('merSign', 0));
		
		$formError = array();
		if($this->isPost() && isset($_POST['MerSignNextForm']))
		{
			$session = Yii::app()->session;
				
			$form->attributes = $_POST['MerSignNextForm'];
			if($form->validate() && !empty($session['merchant']))
			{
				$model = ClassLoad::Only('Home');/* @var $model Home */
				if ($model->signMerchant(array_merge($session['merchant'] , $_POST['MerSignNextForm'])))
				{
					unset($session['merchant'] , $session[$this->userType['merchant']]);
					$this->render('await');
					Yii::app()->end();
					//$this->redirect(array('home/login' , 's'=>'merchant'));
				}else{
					$formError['merchant'][-1] = '注册时写入数据失败!';
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
		
				if (empty($session['merchant']))
				{
					$formError['merchant'][-1] = '异常错误,请重新注册!';
					$this->redirect(array('home/merSign'));
				}
				$form->clearErrors();
			}
		}
		
		$this->render('merSignNext' , array(
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	//如果已登录,跳转到默认登录后页面
	private function _loginJump()
	{
		if (($user = $this->getUser()) && !empty($user['user_type']))
		{
			switch ($user['user_type'])
			{
				case 1 : $this->redirect(array('/member'));
				case 2 : $this->redirect(array('/enterprise'));
				case 3 : $this->redirect(array('/merchant'));
			}
			throw new CHttpException(404 , '用户登录异常' , 404);
		}
	}
	
	//退出
	public function actionLogout()
	{
		Yii::app()->getUser()->logout();
		$this->redirect(array('home/login'));
	}
	
	//登录
	public function actionLogin()
	{
		$form = ClassLoad::Only('LoginForm');/* @var $form LoginForm */
		$this->exitAjaxPost($form);
		
		//如果已登录,跳转到默认登录后页面
		$this->_loginJump();
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('login', 0));
		
		$formError = array();
		if($this->isPost() && isset($_POST['LoginForm']))
		{
			$form->attributes = $_POST['LoginForm'];
			if($form->validate() && $form->login())
			{
				$this->_loginJump();
				throw new CHttpException(404 , '用户登录异常' , 404);
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('login' , array(
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	//注册
	public function actionSign()
	{
		$form				= ClassLoad::Only('SignForm');/* @var $form SignForm */
		$form->signType		= (string)$this->getQuery('s') == 'enterprise' ? 'enterprise' : 'member';
		$form->userType		= $this->userType;
		$this->exitAjaxPost($form);
		
		$this->_loginJump();
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('sign', 0));
		
		$formError = array();
		if($this->isPost() && isset($_POST['SignForm']))
		{
			$form->attributes = $_POST['SignForm'];
			#print_r($_POST);exit;
			if($form->validate())
			{
				$session = Yii::app()->session;
				
				if ($form->signType == 'enterprise')
				{
					$session['enterprise'] = $_POST['SignForm']['enterprise'];
					$this->redirect(array('home/enterprise'));
				}else{
					$model = ClassLoad::Only('Home');/* @var $model Home */
					if ($model->signMember($_POST['SignForm']['member']))
					{
						unset($session[$this->userType['member']]);
						
						//个人用户 注册成功后 , 自动登录
						$phone = isset($_POST['SignForm']['member']['phone']) ? $_POST['SignForm']['member']['phone'] : 0;
						
						$redirect = array();
						if ($phone && GlobalUser::setReflushUser(array('phone'=> $phone , 'user_type'=>1) , 1))
							$redirect = array('/member');
						else
							$redirect = array('home/login' , 's'=>'member');
						
						$this->redirect($redirect);
					}
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		if ($code = (string)$this->getQuery('code'))
			$code = Verify::isPhone((substr($code , 0 , 1) == 'S') ? substr($code , 1) : $code) ? $code : '';
		
		$this->render('sign' , array(
			'code'			=> $code,
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	//企业注册第二步
	public function actionEnterprise()
	{
		$form = ClassLoad::Only('SignForm');/* @var $form SignForm */
		$form->userType = $this->userType;
		$form->signType = 'enterpriseTwo';
		$this->exitAjaxPost($form);
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('sign', 0));
		
		$formError = array();
		if($this->isPost() && isset($_POST['SignForm']))
		{
			$session = Yii::app()->session;
			
			$form->attributes = $_POST['SignForm'];
			if($form->validate() && !empty($session['enterprise']))
			{
				$model = ClassLoad::Only('Home');/* @var $model Home */
				if ($model->signEnterprise(array_merge($session['enterprise'] , $_POST['SignForm'])))
				{
					unset($session['enterprise'] , $session[$this->userType['enterprise']]);
					$this->render('await');
					Yii::app()->end();
					//$this->redirect(array('home/login' , 's'=>'enterprise'));
				}else{
					$formError['enterprise'][-1] = '注册时写入数据失败!';
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				
				if (empty($session['enterprise']))
				{
					$formError['enterprise'][-1] = '异常错误,请重新注册!';
					$this->redirect(array('home/sign' , 's'=>'enterprise'));
				}
				
				$form->clearErrors();
			}
		}
		
		$companyNumber = array_values(GlobalStatus::getStatusColumn(60 , 'user_title'));
		$companyNumber = CMap::mergeArray(array(''=>'请选择') , array_combine($companyNumber , $companyNumber));
		
		$companyType = array_values(GlobalStatus::getStatusColumn(61 , 'user_title'));
		$companyType = CMap::mergeArray(array(''=>'请选择') , array_combine($companyType , $companyType));
		$this->render('enterprise' , array(
			'form'			=> $form,
			'companyNumber'	=> $companyNumber,
			'companyType'	=> $companyType,
			'formError'		=> $formError,
		));
	}
	
	public function actionError()
	{
		$this->setPageSeo(GlobalSEO::getSeoInfo('error', 0));
		
		$app = Yii::app();
		if($error = $app->errorHandler->getError())
		{
			if($app->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->renderPartial('error' , array('error'=>$error) , false , true);
		}
	}
}