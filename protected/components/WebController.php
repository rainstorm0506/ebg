<?php

class WebController extends CController
{
	const CARTKEY = 'cart';
	
	public $layout = 'main';
	public $mainLayoutNav = true;
	
	public $pageTitle = 'e办公商城-办公用品一站式采购平台，让办公e如反掌';
	public $pageKeywords = '';
	public $pageDescription = '';

	//在视图中显示的用户名称和ID
	public $viewsUserName = '';
	public $viewsUserID = '';
	public $viewsCartCount = 0;
	public $viewsKeyword = '';
	
	//短信验证有效期 5分钟
	const VALIDITYTIME = 300;
	public $userType = array(
		'member'=>'reg_member' , 'enterprise'=>'reg_enterprise' , 'merchant'=>'reg_merchant',		#此行逻辑仅用于注册
		'vmember'=>'ver_member' , 'venterprise'=>'ver_enterprise' , 'vmerchant'=>'ver_merchant',	#此行逻辑仅用于会员内部验证
	);
	
	/**
	 * 设置页面SEO信息
	 * @param		array		$seo		SEO数据
	 */
	public function setPageSeo(array $seo)
	{
		$this->pageTitle		= empty($seo['seo_title'])			? $this->pageTitle			: $seo['seo_title'];
		$this->pageKeywords		= empty($seo['seo_keywords'])		? $this->pageKeywords		: $seo['seo_keywords'];
		$this->pageDescription	= empty($seo['seo_description'])	? $this->pageDescription	: $seo['seo_description'];
	}
	
	//过滤器
	public function filters()
	{
		Views::$linkCheckedName = 'current';
		
		$this->initUserInfo();
		return array('checkLogin');
	}
	
	protected function initUserInfo()
	{
		$this->viewsUserID = $this->getUid();
		$user = $this->getUser();
		$this->viewsUserName = empty($user['nickname'])?(empty($user['phone'])?'':$user['phone']):$user['nickname'];
		$session = Yii::app()->session;
		$this->viewsCartCount = empty($session[self::CARTKEY]['cartNum']) ? 0 : (int)$session[self::CARTKEY]['cartNum'];
	}
	
	/**
	 * 检查是否登录
	 * @param	CFilterChain	$filterChain
	 */
	public function filterCheckLogin(CFilterChain $filterChain)
	{
		$loginRoute = array(
			'cart/closing',
			'credits/intro',
		);
		if (in_array($this->getRoute() , $loginRoute) && !$this->getUid())
			$this->redirect($this->createUrl('home/login'));
		
		$filterChain->run();
	}
	
	//获得登录用户ID , 这里是登录人ID , 所以 , 商家ID,子账号ID 都有可能
	public function getUid()
	{
		return (int)Yii::app()->getUser()->getId();
	}
	
	//获得登录人信息
	public function getUser()
	{
		return (($user = Yii::app()->getUser()->getName()) && is_array($user)) ? $user : array();
	}
	
	//获取商家ID
	public function getMerchantID()
	{
		if ($user = $this->getUser())
		{
			if ($user['user_type'] == 3)
			{
				return empty($user['merchant_id']) ? $user['id'] : $user['merchant_id'];
			}else{
				return $user['id'];
			}
		}
		return 0;
	}
	
	//获得用户的类型 , 1=个人 , 2=企业 , 3=商家 , 0=未登录或类型错误
	public function getUserType()
	{
		if (($user = $this->getUser()) && !empty($user['user_type']))
		{
			if ($user['user_type'] >=1 || $user['user_type'] <=3)
				return (int)$user['user_type'];
		}
		return 0;
	}
	
	/**
	 * 获取 get或者post . 优先get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getParam($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getParam($name , $defaultValue);
	}
	
	/**
	 * 获取 post
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getPost($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getPost($name , $defaultValue);
	}
	
	/**
	 * 获取 get
	 * @param	string	$name			名称
	 * @param	mixed	$defaultValue	默认值
	 */
	public function getQuery($name , $defaultValue = null)
	{
		return Yii::app()->getRequest()->getQuery($name , $defaultValue);
	}
	
	/**
	 * 请求是否是post
	 */
	public function isPost()
	{
		return Yii::app()->getRequest()->isPostRequest;
	}
	
	/**
	 * 自定义 显示错误
	 * @param	array	$error		错误数据
	 * @param	string	$page		页面
	 */
	public function error($error , $page = 'error')
	{
		throw new CHttpException(404 , $error , -789);
		#$this->_viewsEnd($error, 'application.views.layouts.'.$page);
	}
	
	/**
	 * 自定义 显示提示
	 * @param	array	$message	提示数据
	 * @param	string	$page		页面
	 */
	public function message($message , $page = 'message')
	{
		$this->_viewsEnd($message, 'application.views.layouts.'.$page);
	}
	
	/**
	 * 显示 信息 , 并结束
	 * @param array/string		$data	数据
	 * @param string			$page	页面
	 *
	 * $data = array('code'=>'' , 'info' => '' , 'title' => '');
	 */
	private function _viewsEnd($data , $page)
	{
		$ary = array();
		if (is_array($data))
		{
			$ary = $data;
			$ary['info'] = isset($ary['info']) ? $ary['info'] : '';
		}else{
			$ary['info'] = $data;
		}
	
		if(Yii::app()->request->isAjaxRequest)
			echo $ary['info'];
		else
			$this->renderPartial($page , array('des'=>$ary));
		Yii::app()->end();
	}
	
	/**
	 * post 提交的数据,结束
	 * @param	object	$model
	 * @param	string	$formId
	 */
	public function exitAjaxPost($model , $formId = 'append-form')
	{
		if(isset($_POST['ajax']) && $_POST['ajax'] === $formId)
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * 设置API返回值 直接输出
	 * @param	int				$code		错误码
	 * @param	string/array	$mixed		错误信息 / 正常情况下输出的数据
	 */
	public function jsonOutput($code , $mixed = array())
	{
		$return = array('code'=>$code , 'message'=>'' , 'data'=>array());
		if ($code === 0)
		{
			$return['data'] = is_array($mixed) ? $mixed : array();
		}else{
			$return['message'] = is_string($mixed) ? $mixed : '未知错误';
		}
		Yii::app()->end(json_encode($return));
	}
	
	/**
	 * 创建 前端的URL
	 * @param		string		$route			路由
	 * @param		array		$params			参数
	 * @param		string		$ampersand		链接符
	 * @return		URL
	 */
	public function createFrontUrl($route , $params=array() , $ampersand='&')
	{
		return Yii::app()->createUrl($route , $params , $ampersand);
	}
	
	/**
	 * 创建 追加的 URL
	 * @param		CController		$controller		调用的控制器
	 * @param		array			$attrs			需要追加的参数
	 */
	public function createAppendUrl(CController $controller , array $attrs , array $dels = array())
	{
		$params = array_merge($_GET , $attrs);
		unset($params['page'] , $params['p']);
		if ($dels)
		{
			foreach ($dels as $v)
				unset($params[$v]);
		}
		return $controller->createUrl($this->route , $params);
	}
}