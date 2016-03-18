<?php
/**
 * 第三方账号登录
 * 
 * @author root
 *
 */
class PassportController extends WebController
{
	/**
	 * QQ授权登录
	 * 
	 * @return void
	 */
	public function actionTencent()
	{
		Yii::import('system.extensions.qq.QQ');
		$qq = new QQ();
		$qq->login();
	}
	
	/**
	 * 新浪微博授权登录
	 * 
	 * @return void
	 */
	public function actionWeibo()
	{
		Yii::import('system.extensions.weibo.Weibo');
		$weibo = new Weibo();
		header('Location:' . $weibo->loginUrl());
	}
	
	/**
	 * 授权回调页
	 * 
	 * @return void
	 */
	public function actionCallback()
	{
		$form				= ClassLoad::Only('SignForm');/* @var $form SignForm */
		$form->signType		= (string)$this->getQuery('s') == 'enterprise' ? 'enterprise' : 'member';
		$form->userType		= $this->userType;

		$this->_loginJump();
		
		$this->setPageSeo(GlobalSEO::getSeoInfo('sign', 0));
		
		$formError = array();

		if ($code = (string)$this->getQuery('code')) {
			$code = Verify::isPhone((substr($code , 0 , 1) == 'S') ? substr($code , 1) : $code) ? $code : '';
		}
		
		$this->render('callback' , array(
				'code'			=> $code,
				'form'			=> $form,
				'formError'		=> array(),
				'pid'			=> $this->getQuery('pid')
		));
	}
	
	/**
	 * 第三方账号注册
	 * 
	 * @return 
	 */
	public function actionRegister()
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
			if($form->validate())
			{
				$session = Yii::app()->session;
		
				if ($form->signType == 'enterprise')
				{
					// 如果是企业，判断账号是否存在，如果存在，则判断是否审核，如果不存在，则跳到企业注册页，注册并绑定
// 					$session['enterprise'] = $_POST['SignForm']['enterprise'];
// 					$this->redirect(array('home/enterprise'));
				}else{
					// 1：判断手机号是否存在，存在则绑定第三方账号，并登录
					// 2：不存在则获取第三方账号信息进行注册，再绑定再写入其他表对应的表（包含下载头像）
					// 3：推荐码的设置
					
					
// 					$model = ClassLoad::Only('Home');/* @var $model Home */
// 					if ($model->signMember($_POST['SignForm']['member']))
// 					{
// 						unset($session[$this->userType['member']]);
		
// 						//个人用户 注册成功后 , 自动登录
// 						$phone = isset($_POST['SignForm']['member']['phone']) ? $_POST['SignForm']['member']['phone'] : 0;
		
// 						$redirect = array();
// 						if ($phone && GlobalUser::setReflushUser(array('phone'=> $phone , 'user_type'=>1) , 1))
// 							$redirect = array('/member');
// 						else
// 							$redirect = array('home/login' , 's'=>'member');
		
// 						$this->redirect($redirect);
// 					}
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
	
	/**
	 * QQ授权回调处理
	 * 
	 * @return void
	 */
	public function actionQQCallback()
	{
		var_dump($_REQUEST);
		
		// $qc = new QC();
		// echo $qc->qq_callback();
		// echo $qc->get_openid();
		
		// require_once("../API/qqConnectAPI.php");
		
		// $qc     = new QC();
		// $result = $qc->get_user_info();
		
		// print_r($result);		
	}
	
	
	/**
	 * 新浪微博授权回调处理
	 * 
	 * @return void
	 */
	public function actionWeiboCallback()
	{		
		
// 		if (isset($_REQUEST['code'])) {
// 			Yii::import('system.extensions.weibo.Weibo');
			
// 			$weibo	= new Weibo();
// 			$token	= $weibo->getAccessTokenToCode($_REQUEST['code']);

// 			if (isset($token['access_token'])) {
// 				$api  = $weibo->apiObj($token['access_token']);
// 				$uid  = $api->get_uid();
// 				$user = $api->show_user_by_id($uid['uid']);
				
// 				print_r($user);
// 			}
// 		}

	}
}
?>