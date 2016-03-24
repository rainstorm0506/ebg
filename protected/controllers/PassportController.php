<?php
/**
 * 第三方账号登录
 * 
 * @author 严成林 , 涂先锋
 */
class PassportController extends WebController
{
	//QQ授权登录
	public function actionTencent()
	{
		$model	= ClassLoad::Only('ThirdLoginQQ');/* @var $model ThirdLoginQQ */
		$u		= (int)$this->getQuery('u');
		$u		= $model->config['callback'] . '.' . ($u == 1 ? 1 : 2);
		$model->login($u);
	}
	
	/**
	 * 新浪微博授权登录
	 * 
	 * @return void
	 */
	public function actionWeibo()
	{
		$model	= ClassLoad::Only('ThirdLoginWB');/* @var $model ThirdLoginWB */
		$u		= (int)$this->getQuery('u');
		$u		= $model->config['callback'] . '.' . ($u == 1 ? 1 : 2);
		$model->login($u);
	}
	
	/**
	 * 微信授权登录
	 *
	 * @return void
	 */
	public function actionWechat()
	{
		$model	= ClassLoad::Only('ThirdLoginWechat');/* @var $model ThirdLoginWechat */
		$u		= (int)$this->getQuery('u');
		$u		= $model->config['callback'] . '.' . ($u == 1 ? 1 : 2);
		$model->login($u);
	}
	
	/**
	 * 授权统一回调页
	 * 
	 * @return void
	 */
	public function actionCallback()
	{	
		$this->_loginJump();
		
		$utype = $this->getQuery('utype');
		$ptype = $this->getQuery('ptype');
		$form  = ClassLoad::Only('PassportForm');

		$form->ruleType		= 'callback';
		$form->attributes	= $_GET;
		
		if (!$form->validate()) {
			print_r($form->getErrors());
			exit;
		}
		
		// 获取用户数据
		$passport = ClassLoad::Only('Passport');
		switch ($ptype) {
			case 1:
				$info = $passport->getWeiboInfo($form->code);
				$user = array(
						'nickname'		=> $info['screen_name'],
						'avatar'		=> $info['avatar_large'],
						'access_token'	=> $info['access_token'],
						'id'			=> $info['idstr']
				);
				break;
			case 2:
				$info = $passport->getWechatInfo($form->code);
				$user = array(
						'nickname'		=> $info['nickname'],
						'avatar'		=> $info['headimgurl'],
						'access_token'	=> $info['access_token'],
						'id'			=> $info['openid']
				);
				break;
			case 3:
				$info = $passport->getQQInfo($form->code);
		}

		if (empty($info)) {
			$this->redirect($this->createUrl('home/login', array('s' => $utype==1?'member':'enterprise')));
		}
		
		// 如果绑定过平台账号，直接登录
		$phone = $passport->isBind($form->ptype, $user['id']);
		if ($phone) {
			$passport->login($phone);
			$this->redirect('/member');
		}
		
		$this->render('callback', array(
				'utype' => $utype,
				'ptype' => $ptype,
				'user'	=> $user,
				'form'	=> $form
		));
		
	}
	
	/**
	 * 注册或绑定用户
	 * 
	 * @return json
	 */
	public function actionRegister()
	{
		// 判断登录
			
		$form		= ClassLoad::Only('PassportForm');
		$post		= $this->getPost('PassportForm');
		$passport	= ClassLoad::Only('Passport');
		
		if($this->isPost() && isset($_POST['PassportForm']))
		{
			$form->ruleType		= 'register';
			$form->attributes	= $_POST['PassportForm'];
			
			// 加强验证..
			if (!$form->validate()) {
				$this->jsonOutput(1, '参数错误');
			}
			
			// 第三方账号数据
			switch ($form->ptype) {
				case 1:
					$pinfo  = $passport->getWeiboInfoByToken($form->access_token);
					$common = array(
							'nickname'	=> $pinfo['screen_name'],
							'avatar'	=> $pinfo['avatar_large']
					);
					break;
				case 2:
					$pinfo  = $passport->getWechatInfoByToken($form->access_token, $form->id);
					$common = array(
							'nickname'	=> $pinfo['nickname'],
							'avatar'	=> $pinfo['headimgurl']
					);
					break;
				default:
					$pinfo = array();
			}
			
			if (empty($pinfo)) {
				$this->jsonOutput(1, '无法获取账号数据');
			}
			
			// 会员绑定操作
			if ($passport->isRegister($form->phone)) {
				if ($passport->isRepeat($form->phone, $form->ptype)) {
					$this->jsonOutput(1, '一个手机号不能绑定同一种第三方账号');
				}
				// 更新推荐码
				if ($form->recode) {
					$passport->changeRecode($form->phone, $form->recode);
				}
				// 用户数据
				$uinfo = ClassLoad::Only('Home')->getUserInfo($form->phone, false);
				$uid   = $uinfo['id'];
			} else {
				// 会员新增操作
				$uid = $passport->register($form->phone, $form->recode, $form->ptype, $common['avatar'], $common['nickname']);				
				if (empty($uid)) {
					$this->jsonOutput(1, '账号注册失败');
				}
			}
		
			// 关联第三方账号
			if ($passport->createPassport($uid, $form->phone, $form->ptype, $pinfo)) {
				if ($form->utype == 1) {
					$passport->login($form->phone);
					$this->jsonOutput(0, array('url' => $this->createUrl('/member')));
				}
			}
			$this->jsonOutput(2, '绑定失败，请返回重试');
		}
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
}
?>