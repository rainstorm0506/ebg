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
		$referrer = (string)$this->getQuery('referrer');
		$session = Yii::app()->session;
		$session->open();
		$session['third_referrer'] = $referrer;
		$this->_loginJump();
		ClassLoad::Only('ThirdLoginQQ')->login((int)$this->getQuery('u') == 2 ? 2 : 1);
	}
	
	//新浪微博授权登录
	public function actionWeibo()
	{
		$referrer = (string)$this->getQuery('referrer');
		$session = Yii::app()->session;
		$session->open();
		$session['third_referrer'] = $referrer;
		$this->_loginJump();
		ClassLoad::Only('ThirdLoginWB')->login((int)$this->getQuery('u') == 2 ? 2 : 1);
	}
	
	//微信授权登录
	public function actionWechat()
	{
		$referrer = (string)$this->getQuery('referrer');
		$session = Yii::app()->session;
		$session->open();
		$session['third_referrer'] = $referrer;
		$this->_loginJump();
		ClassLoad::Only('ThirdLoginWechat')->login((int)$this->getQuery('u') == 2 ? 2 : 1);
	}
	
	//授权统一回调页
	public function actionCallback()
	{
		$this->_loginJump();
		
		$form			= ClassLoad::Only('PassportForm');/* @var $form PassportForm */
		$args			= explode('.' , (string)$this->getQuery('x'));
		$form->source	= empty($args[0]) ? '' : $args[0];
		$form->seat		= empty($args[1]) ? 0 : (int)$args[1];
		$form->code		= (string)$this->getQuery('code');
		
		if (($errString = $form->viferyCallback()) !== true)
			$this->error($errString);
		
		if (!$third = $this->getThirdUserInfo($form))
			$this->error('第三方回调错误 !');
		
		//检查是否已绑定
		if (!$this->bindUser($form , $third['openid']))
		{
			//未绑定
			$session = Yii::app()->session;
			$session->open();
			$third['source']		= $form->source;
			$third['seat']			= $form->seat;
			$third['code']			= $form->code;
			$session['ThirdBind']	= $third;
			$session->close();
			
			$this->redirect(array('thirdBind'));
		}
	}
	
	//未绑定用户
	public function actionThirdBind()
	{
		$this->_loginJump();
		
		$session = Yii::app()->session;
		$session->open();
		if (empty($session['ThirdBind']))
			$this->error('第三方回调错误 !');
		#print_r($session['ThirdBind']);exit;
		$third			= $session['ThirdBind'];
		$formError		= array();
		$form			= ClassLoad::Only('PassportForm');/* @var $form PassportForm */
		$form->source	= isset($third['source']) ? $third['source'] : '';
		$form->seat		= isset($third['seat']) ? $third['seat'] : 0;
		$form->openid	= isset($third['openid']) ? $third['openid'] : '';
		
		if($this->isPost() && isset($_POST['PassportForm']))
		{
			$form->attributes = $_POST['PassportForm'];
			if($form->validate())
			{
				$model = ClassLoad::Only('Passport');/* @var $model Passport */
				//已注册用户
				if ($user = $model->getUserInfo($form->phone , $third['seat']==2))
				{
					if ($model->bindExtUser($user['id'] , $third , $form))
					{
						$this->autoLogin($form->phone , $user['user_type']==3);
					}else{
						$this->error('未知错误 !');
					}
				}else{
					//未注册用户
					if ($third['seat'] == 2)
					{
						$session['ThirdBind'] = array_merge($third , $_POST['PassportForm']);
						$session->close();
						$this->redirect(array('thirdBindMerchant'));
					}else{
						//注册个人用户
						$gg = $model->signMember($third , $form);
						if ($gg)
						{
							$this->autoLogin($form->phone , 0);
						}elseif ($gg === null){
							$this->error('此手机已绑定过同类社交账号 !');
						}else{
							$this->error('注册失败 !');
						}
					}
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('callback', array(
			'third'			=> $third,
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	/**
	 * 自动登录
	 * @param		bigint		$phone		手机号码
	 * @param		int			$type		是否是商家 1=是 , 0=不是
	 */
	private function autoLogin($phone , $isMerchant)
	{
		$isMerchant = $isMerchant ? 1 : 0;
		//自动登录
		$form = ClassLoad::Only('LoginForm');/* @var $form LoginForm */
		$form->attributes = array(
			'userPhone'		=> $phone,
			'type'			=> $isMerchant,
			'password'		=> 'no password',
		);
		
		if($form->validate() && $form->login(false))
		{
			$this->_loginJump();
		}else{
			$user		= ClassLoad::Only('Home');/* @var $user Home */
			$info		= $user->getUserInfo($phone , $isMerchant);
			
			$session	= Yii::app()->session;
			$session->open();
			$session['ThirdImproperStatus'] = isset($info['status_id']) ? $info['status_id'] : 0;
			$session->close();
			
			$this->redirect(array('improper'));
		}
	}
	
	//绑定第二步 , 商家信息
	public function actionThirdBindMerchant()
	{
		$form = ClassLoad::Only('MerSignNextForm');/* @var $form MerSignNextForm */
		$this->exitAjaxPost($form);
		
		$formError = array();
		if($this->isPost() && isset($_POST['MerSignNextForm']))
		{
			$session = Yii::app()->session;
			$session->open();
			#print_r($session['ThirdBind']);exit;
			
			$form->attributes = $_POST['MerSignNextForm'];
			if($form->validate() && !empty($session['ThirdBind']))
			{
				$post = array_merge($session['ThirdBind'] , $_POST['MerSignNextForm']);
				$post['password'] = '';
				
				$passport	= ClassLoad::Only('Passport');/* @var $passport Passport */
				$model		= ClassLoad::Only('Home');/* @var $model Home */
				$source		= isset($passport->source[$post['source']]) ? (int)$passport->source[$post['source']] : 0;
				$uid		= 0;
				if ($model->signMerchant($post , $source , $uid))
				{
					$passport			= ClassLoad::Only('Passport');/* @var $passport Passport */
					$tmpForm			= new PassportForm();
					$tmpForm->source	= $post['source'];
					$tmpForm->openid	= $post['openid'];
					$passport->regUserPassport($uid , $tmpForm , $post['third_info']);
					
					$this->autoLogin($post['phone'] , 1);
				}else{
					$formError['merchant'][-1] = '注册时写入数据失败!';
				}
			}else{
				$formError = $form->getErrors();
				foreach ($formError as &$v)
					$v = array_unique($v);
				$form->clearErrors();
			}
		}
		
		$this->render('thirdBindMerchant' , array(
			'form'			=> $form,
			'formError'		=> $formError,
		));
	}
	
	
	//绑定过的非正常用户视图
	public function actionImproper()
	{
		$session = Yii::app()->session;
		$session->open();
		if (isset($session['ThirdImproperStatus']))
		{
			$icx = array('title'=>'' , 'content'=>'');
			switch ($session['ThirdImproperStatus'] % 100)
			{
				case 11 : $icx = array('title'=>'已禁用' , 'content'=>'该社交账号关联的平台账号已禁用，如有疑问，请联系平台客服'); break;
				case 12 : $icx = array('title'=>'已删除' , 'content'=>'该社交账号关联的平台账号已删除，如有疑问，请联系平台客服'); break;
				case 13 : $icx = array('title'=>'审批中' , 'content'=>'该社交账号关联的平台账号正在审批中，我们会在3个工作日内处理完毕，请耐心等待'); break;
				case 14 : $icx = array('title'=>'审核未通过' , 'content'=>'该社交账号关联的平台账号审批未通过，如有疑问，请联系平台客服'); break;
			}
			$this->render('improper' , $icx);
		}else{
			$this->error('未知错误!');
		}
	}
	
	//已绑定过平台账号，直接登录
	private function bindUser(PassportForm $form , $openid)
	{
		$model = ClassLoad::Only('Passport');/* @var $model Passport */
		if ($user = $model->getBindUser($form , $openid))
		{
			$this->autoLogin($user['phone'] , $user['user_type']==3);
			return true;
		}
		return false;
	}
	
	//获取最靠近的非空值
	private function getClosestVal()
	{
		foreach (array_unique(array_filter(func_get_args())) as $val)
		{
			if (($val = trim($val)) != '')
				return $val;
		}
		
		return '';
	}
	
	/**
	 * 获取第三方用户数据
	 * @param		PassportForm		$form		第三方数据对象
	 */
	private function getThirdUserInfo(PassportForm $form)
	{
		$model = ClassLoad::Only('Passport');/* @var $model Passport */
		
		switch ($form->source)
		{
			case 'wb':
				if ($info = $model->getWeiboInfo($form->code))
				{
					return array
					(
						'nickname'		=> $info['screen_name'],
						'access_token'	=> $info['access_token'],
						'openid'		=> $info['id'],
						'face'			=> $this->getClosestVal($info['avatar_hd'] , $info['avatar_hd'] , $info['profile_image_url']),
						'third_info'	=> $info,
					);
				}else{
					$this->error('微博接口请求出错 !');
				}
			break;
			
			case 'wx':
				if ($info = $model->getWechatInfo($form->code))
				{
					return array
					(
						'nickname'		=> $info['nickname'],
						'access_token'	=> $info['access_token'],
						'openid'		=> $info['openid'],
						'face'			=> $info['headimgurl'],
						'third_info'	=> $info,
					);
				}else{
					$this->error('微信接口请求出错 !');
				}
			break;
			
			case 'qq':
				if ($info = $model->getQQInfo($form->code))
				{
					return array
					(
						'nickname'		=> $info['nickname'],
						'access_token'	=> $info['access_token'],
						'openid'		=> $info['openid'],
						'face'			=> $this->getClosestVal($info['figureurl_qq_2'] , $info['figureurl_qq_1'] , $info['figureurl_2'] , $info['figureurl_1'] , $info['figureurl']),
						'third_info'	=> $info,
					);
				}else{
					$this->error('QQ接口请求出错 !');
				}
			break;
		}
	}
}